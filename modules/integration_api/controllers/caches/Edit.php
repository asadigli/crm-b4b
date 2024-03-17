<?php
defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('max_execution_time', 1); //300 seconds = 5 minutes

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  function checkCroneTime($params){
    $month_days     = isset($params["month_days"]) ? $params["month_days"] : "*";
    $week_days      = isset($params["week_days"]) ? $params["week_days"] : "*";
    $hours          = isset($params["hours"]) ? $params["hours"] : "*";
    $minutes        = isset($params["minutes"]) ? $params["minutes"] : "*";
    $is_month_day   = $month_days === "*" || $month_days  === (int)date("m")        || in_array((int)date("m"),$month_days);
    $is_week_day    = $week_days  === "*" || $week_days   === ((int)date("w") ?: 7) || in_array(((int)date("w") ?: 7),$week_days);
    $is_hour        = $hours      === "*" || $hours       === (int)date("H")        || in_array((int)date("H"),$hours);
    $is_minute      = $minutes    === "*" || $minutes     === (int)date("i")        || in_array((int)date("i"),$minutes);

    return $is_month_day && $is_week_day && $is_hour && $is_minute;
  }

  function refreshAll() {
    $integration_types = $this->config->item("cache_types");
    $cached_list = [];
    foreach ($integration_types as $key => $item) {
      // $current_time = floor(strtotime(now()) - strtotime(date("Y-01-01 00:00:00",strtotime(now())))/60);
      if ($this->checkCroneTime([
        "month_days" => isset($item["auto-refresh-time"]["month_days"]) ? $item["auto-refresh-time"]["month_days"] : "*",
        "week_days" => isset($item["auto-refresh-time"]["week_days"]) ? $item["auto-refresh-time"]["week_days"] : "*",
        "hours" => isset($item["auto-refresh-time"]["hours"]) ? $item["auto-refresh-time"]["hours"] : "*",
        "minutes" => isset($item["auto-refresh-time"]["minutes"]) ? $item["auto-refresh-time"]["minutes"] : "*",
      ])) {
        if ($this->refresh($key,REQUEST_TYPE_AUTO,isset($item["body"]) ? $item["body"] : [])) {
          $cached_list[] = $key;
        }
      }
    }
    if (!$cached_list) {
      return json_response(rest_response(Status_codes::HTTP_NO_CONTENT,lang("Not cached")));
    }
    return json_response(rest_response(Status_codes::HTTP_OK,"Cached",$cached_list));
  }

  public function refresh($type,$request_type,$body = []) {
    $params = [
      "type" => $type,
      "deleted_at" => now(),
      "request_type" => $request_type,
      "creator_id" => (int)headers("userid") ?: null,
      "creator_ip" => headers("useraddress") ?: null,
      "limit_hour" => isset($body["limit_hour"]) && $body["limit_hour"] ? $body["limit_hour"] : (float)$this->custom_input->put("limit_hour"),
      "date" => now(),
      "start_date" => $this->custom_input->put("start_date") ?: null,
      "end_date" => $this->custom_input->put("end_date") ?: null,
      "current_time" => date("H:i"),
      "validate_limit_hour" => true,
    ];

    validateArray($params, ["type", "request_type"]);

    validateItemByGroup($params["request_type"], [REQUEST_TYPE_AUTO, REQUEST_TYPE_MANUAL], "request_type");

    $cache_types = $this->config->item("cache_types");
    validateItemByGroup($params["type"], $cache_types, "type", true);

    if(!isset($cache_types[$params["type"]]["table"])) {
      return json_response(rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Integration table not found")
      ));
    }

    if(!isset($cache_types[$params["type"]]["execute"][$request_type])) {
      return json_response(rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("This request type is not in executable list")
      ));
    }

    if(!$cache_types[$params["type"]]["execute"][$request_type]) {
      return json_response(rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("This cache type is not executable")
      ));
    }

    # checking for no cache time
    if(isset($cache_types[$params["type"]]["no_cache_time"])) {
      $no_cache_time = $cache_types[$params["type"]]["no_cache_time"];
      if($params["current_time"] >= $no_cache_time["start_time"] && $params["current_time"] <= $no_cache_time["end_time"]){
        return false;
      }
    }

    # checking for caching for all time
    if(isset($cache_types[$params["type"]]["cache_for_all_time"])) {
      $checking_time = $cache_types[$params["type"]]["cache_for_all_time"];
      if($params["current_time"] >= $checking_time["start_time"] && $params["current_time"] <= $checking_time["end_time"]){
        $params["validate_limit_hour"] = false;
      }
    }

    #checking if there is special cache time
    if(isset($cache_types[$params["type"]]["cache_special_dates"])) {
      $checking_time = $cache_types[$params["type"]]["cache_special_dates"];
      if($params["current_time"] >= $checking_time["cache_at_from"] && $params["current_time"] <= $checking_time["cache_at_to"]) {
        $params["limit_hour"] = NULL;
        $params["start_date"] = $checking_time["start_date"];
        $params["end_date"] = $checking_time["end_date"];
      }
    }

    if($params["validate_limit_hour"]) {
      if (!$params["limit_hour"]) {
        validateArray($params,["start_date","end_date"]);
      }
      if ($params["start_date"]) {
        validateDate($params["start_date"]);
      }
      if ($params["end_date"]) {
        validateDate($params["end_date"]);
      }
    }

    # deleting list datas from cached local data caches
    refreshLocalCachedListCache($params["type"]);
    if($params["type"] === INTEG_TYPE_PRODUCTS) {
      $this->load->model("caches/Products_model","products_model");
      $res = $this->products_model->refreshProducts($params);
    } elseif ($params["type"] === INTEG_TYPE_CUSTOMERS) {
      $this->load->model("caches/Customer_model","customer_model");
      $res = $this->customer_model->index($params);
    } elseif ($params["type"] === INTEG_TYPE_CUSTOMER_ACCOUNT) {
      $this->load->model("caches/Account_model","account_model");
      $res = $this->account_model->index($params);
    } elseif ($params["type"] === INTEG_TYPE_INVOICES) {
      $this->load->model("caches/Account_model","account_model");
      $res = $this->account_model->details($params);
    } elseif ($params["type"] === INTEG_TYPE_CURRENCIES) {
      $this->load->model("caches/Currency_model","currency_model");
      $res = $this->currency_model->index($params);
    } elseif ($params["type"] === INTEG_TYPE_MODELS) {
      $this->load->model("caches/Properties_model","properties_model");
      $res = $this->properties_model->models($params);
    } elseif ($params["type"] === INTEG_TYPE_CARBRANDS) {
      $this->load->model("caches/Properties_model","properties_model");
      $res = $this->properties_model->carbrands($params);
    } elseif ($params["type"] === INTEG_TYPE_PRODUCT_BRANDS) {
      $this->load->model("caches/Properties_model","properties_model");
      $res = $this->properties_model->productBrands($params);
    } elseif ($params["type"] === INTEG_TYPE_CASHBOXES) {
      $this->load->model("caches/Cashbox_model","cashbox_model");
      $res = $this->cashbox_model->index($params);
    }

    if($params["request_type"] === REQUEST_TYPE_AUTO) {
      return $res;
    }
    return json_response($res);
  }
}
