<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends MY_Controller {

  public function __construct(){
    parent::__construct();
    Auth::check(true,["manager", "main_admin","admin"]);
  }


  /*
  *
  *
  */
  public function index() {
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/invoices/sales.js",
    ];

    $this->page_title = lang("Sales");

    $this->view([
      "layouts/header",
      "invoices/sales/index",
      "layouts/footer",
    ]);
  }


  /*
  *
  *
  */
  public function live() {
    $params = [
      "start_date"    => $this->input->get("start_date"),
      "end_date"      => $this->input->get("end_date"),
      "offset"        => $this->input->get("offset"),
      "brand"         => $this->input->get("brand"),
      "brand_code"    => $this->input->get("brand_code"),
      "oem_code"      => $this->input->get("oem_code"),
      "warehouse"     => $this->input->get("warehouse"),
      "currency"      => $this->input->get("currency"),
      "export"        => $this->input->get("excel_export"),
    ];
    $this->load->model("invoices/Sales_model","model");
    $res = $this->model->index($params);
    return json_response($res);
  }


  /*
  *
  *
  */
  public function daily_list() {
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/invoices/daily_sales.js",
    ];

    $this->page_title = lang("Daily sales");

    $this->view([
      "layouts/header",
      "invoices/sales/daily_list",
      "layouts/footer",
    ]);
  }

  /*
  *
  *
  */
  public function daily_list_live() {
    $params = [
      "start_date"    => $this->input->get("start_date"),
      "end_date"      => $this->input->get("end_date"),
      "offset"        => $this->input->get("offset"),
      "brand"         => $this->input->get("brand"),
      "brand_code"    => $this->input->get("brand_code"),
      "oem_code"      => $this->input->get("oem_code"),
      "warehouse"     => $this->input->get("warehouse"),
      "currency"      => $this->input->get("currency"),
      "export"        => $this->input->get("excel_export"),
    ];
    $this->load->model("invoices/Sales_model","model");
    $res = $this->model->daily_list($params);
    return json_response($res);
  }


  /*
  *
  *
  */
  public function details($code) {
    $this->page_title = lang("Invoice details");

    $this->extraJS = [
    ];

    $params = [
      "code" => $code,
    ];
    $this->load->model("invoices/Sales_model","model");
    $res = $this->model->details($params);

    $this->load->model("customers/All_model", "customer_model");
    $customer = $this->customer_model->index(["customer_id" => isset($res["data"][0]["customer_id"]) ? $res["data"][0]["customer_id"] : -1]);

    $this->view([
        "layouts/header",
        "invoices/sales/details",
        "layouts/footer",
    ],[
      "code" => $code,
      "customer" => isset($customer["code"]) && $customer["code"] === Status_codes::HTTP_OK && isset($customer["data"]["list"][0]) ? $customer["data"]["list"][0] : [],
      "list" => isset($res["data"]) ? $res["data"] : []
    ]);
  }

}
