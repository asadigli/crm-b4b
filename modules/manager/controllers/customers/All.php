<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct() {
    parent::__construct();
    Auth::check(true,["manager", "main_admin","admin"]);
  }


  public function index()
  {
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/customers/index.js",
    ];

    $params = [
      "keyword" => $this->input->get("keyword"),
      "is_inactive_customers" => $this->input->get("is_inactive_customers"),
      "inactive_customers" => $this->input->get("inactive_customers"),
      // "is_azn_customers" => $this->input->get("is_azn_customers"),
    ];

    $this->page_title = lang("Customers");
    $this->view([
      "layouts/header",
      "customers/index",
      "layouts/footer"
    ],[
      "url_params" => $params,
    ]);
  }

  public function live() {
    $params = [
      "keyword"                  => $this->input->get("keyword"),
      "due_date"                 => $this->input->get("due_date"),
      "city_id"                  => $this->input->get("city_id"),
      "currency_id"              => $this->input->get("currency_id"),
      "search_by_debts"          => $this->input->get("search_by_debts"),
      "customer_type"            => $this->input->get("customer_type"),
      "status"                   => $this->input->get("status"),
      "offset"                   => $this->input->get("offset"),
      "export"                   => $this->input->get("excel_export"),
      "is_inactive_customers"    => $this->input->get("is_inactive_customers"),
      "inactive_customers"       => $this->input->get("inactive_customers"),
    ];

    $this->load->model("customers/All_model", "model");
    $res = $this->model->index($params);
    return json_response($res);
  }

  public function list() {
    $cache_key = "b4b_customer_datas.cache";
    $cached_data = $this->cache->get($cache_key);
    if (!$cached_data) {
      $this->load->model("customers/All_model", "model");
      $res = $this->model->list([]);
      if (isset($res["code"]) && $res["code"] === Status_codes::HTTP_OK) {
        $this->cache->save($cache_key,$res,360 * 24);
      }
    } else {
      $res = $cached_data;
    }
    return json_response($res);
  }

  public function cityList(){
    $params = [];

    $this->load->model("customers/All_model", "model");
    $res = $this->model->cityList($params);
    return json_response($res);
  }
}
