<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function applyDiscount(){
    return json_response($this->index("put"));
  }

  public function hidePrice(){
    return json_response($this->index("put"));
  }

  public function index($method = "get")
  {
    $others = configs(["group" => "other"]);
    // $auth_user = checkAdmin(null, true);

    $now = now();
    $params = [
      "userid" => (int)headers("userid") ?: null,
      "keyword" => $this->custom_input->{$method}("keyword",[
        "symbols" => ["'","\"","\\","/","`"]
      ]),
      "brand" => $this->custom_input->{$method}("brand") ?: null,
      "filter" => $this->custom_input->{$method}("filter") ?: null,
      "search_type" => $this->custom_input->{$method}("search_type") ?: null,
      "car_brand" => $this->custom_input->{$method}("car_brand") ?: null,
      "product_resource" => $this->custom_input->{$method}("product_resource") ?: null,
      "model" => $this->custom_input->{$method}("model") ?: null,
      "in_stock" => $this->custom_input->{$method}("in_stock") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "cr_search" => $this->custom_input->{$method}("cr_search") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "is_dead_stock" => $this->custom_input->{$method}("is_dead_stock") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "operation_date" => $now,
      "now" => $now,
      "entry_ip" => headers("useraddress") ?: "127.0.0.1",
      "entry_device" => headers("userdevice") ?: null,
      "entry_agent" => headers("useragent") ?: null,
      "offset" => (int)$this->custom_input->{$method}("offset") ?: null,
      // "auth_user" => $auth_user,
      "export" => $this->custom_input->{$method}("export") ? 1 : 0,
      "dead_stock"            => $this->custom_input->{$method}("dead_stock") ?: NULL,
      "only_warehouse" => $this->custom_input->{$method}("only_warehouse") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "warehouse_id"            => $this->custom_input->{$method}("warehouse_id") ?: NULL,
      "min_search_quantity"     => empty($this->custom_input->{$method}("min_search_quantity")) && $this->custom_input->{$method}("min_search_quantity") !== '0' ? NULL : $this->custom_input->{$method}("min_search_quantity"),
      "max_search_quantity"     => empty($this->custom_input->{$method}("max_search_quantity")) && $this->custom_input->{$method}("max_search_quantity") !== '0' ? NULL : $this->custom_input->{$method}("max_search_quantity"),
      "discount_package_id"     => $this->custom_input->{$method}("discount_package_id") ?: NULL,
      "discount_rate" => isset($others["other"]["b4b_discount"]) && $others["other"]["b4b_discount"] ? (int)$others["other"]["b4b_discount"][0] : null,
      "name" => $this->custom_input->{$method}("name") ?: NULL,
      "apply_discount" => $this->custom_input->{$method}("apply_discount") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "hide_price" => $this->custom_input->{$method}("hide_price") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
    ];

    if (
        !$params["keyword"] &&
        !$params["brand"] &&
        !$params["discount_package_id"] &&
        !$params["car_brand"] &&
        !$params["warehouse_id"] &&
        (is_null($params["min_search_quantity"]) && is_null($params["max_search_quantity"])) &&
        ($params["is_dead_stock"] === STATUS_DEACTIVE)
      )
    {
      validateArray(["keyword" => null,"brand" => null, "car_brand" => null, "discount_package_id" => null],["keyword","brand","car_brand","discount_package_id"]);
    }

    validateArray($params, ["userid"]);

    if ($params["keyword"] && (strlen($params["keyword"]) < 2)) {
      return json_response(rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Enter minimum 2 symbols")
      ));
    }

    // if(!in_array($params["search_type"], [SEARCH_TYPE_QUICK,SEARCH_TYPE_FULL])) {
    //   $params["search_type"] = SEARCH_TYPE_FULL;
    // }
    //
    // $products_filters = [
    //   FILTER_STOCK_BAKU_ASC,
    //   FILTER_STOCK_BAKU_DESC,
    //   FILTER_STOCK_GANJA_ASC,
    //   FILTER_STOCK_GANJA_DESC,
    //   FILTER_CART_ASC,
    //   FILTER_CART_DESC,
    //   FILTER_PRICE_EUR_ASC,
    //   FILTER_PRICE_EUR_DESC,
    //   FILTER_PRICE_AZN_ASC,
    //   FILTER_PRICE_AZN_DESC,
    //   FILTER_BRAND_NAME_ASC,
    //   FILTER_BRAND_NAME_DESC,
    //   FILTER_PRODUCT_NAME_ASC,
    //   FILTER_PRODUCT_NAME_DESC,
    // ];
    //
    // if(!in_array($params["filter"], $products_filters)){
    //   $params["filter"] = FILTER_STOCK_BAKU_DESC;
    // }

    $params["product_resource"] = $params["product_resource"] && isset(special_codes("products.resources")[$params["product_resource"]]) ? special_codes("products.resources")[$params["product_resource"]] : NULL;

    $params["min_search_quantity"] = ($params["min_search_quantity"] !== "" && !is_null($params["min_search_quantity"])) ? (int)$params["min_search_quantity"] : NULL;
    $params["max_search_quantity"] = ($params["max_search_quantity"] !== "" && !is_null($params["max_search_quantity"])) ? (int)$params["max_search_quantity"] : NULL;

    $this->load->model("manager/products/Search_model", "model");
    $res = $this->model->index($params);
    return json_response($res);
  }

  public function tecdocCrosses()
  {
    checkAdmin();
    $params = [
      "userid" => (int)headers("userid") ?: null,
      "keyword" => $this->custom_input->get("keyword",[
        "symbols" => ["'","\"","\\","/","`"]
      ]),
    ];

    validateArray($params, ["userid", "keyword"]);

    $this->load->model("manager/products/Search_model", "model");
    $res = $this->model->tecdocCrosses($params);
    return json_response($res);
  }
  public function comments(){
    checkAdmin();

    $params = [
      "product_id" => (int)$this->input->get("product_id") ?: NULL,
      // "remoteb4buserid" => (int)headers("remoteb4buserid"),
    ];

    validateArray($params,["product_id"]);

    $this->load->model("manager/products/Search_model", "model");
    $res = $this->model->comments($params);
    return json_response($res);
  }

  public function priceOffers(){
    checkAdmin();

    $params = [
      "product_id" => (int)$this->input->get("product_id") ?: NULL,
      // "remoteb4buserid" => (int)headers("remoteb4buserid"),
    ];

    validateArray($params,["product_id"]);

    $this->load->model("manager/products/Search_model", "model");
    $res = $this->model->priceOffers($params);
    return json_response($res);
  }


}
