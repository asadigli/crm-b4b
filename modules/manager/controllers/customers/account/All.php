<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct(){
    parent::__construct();
    Auth::check(true,["manager", "main_admin","admin"]);
  }

  public function index($id) {


    $this->extraJS = [
      "js/helpers.js",
      "js/pages/customers/account/all.js",
    ];

    $this->load->model("customers/All_model", "model");
    $customer = $this->model->index(["customer_id" => $id]);

    $this->page_title = lang("Customer account");
    if(isset($customer["code"]) && $customer["code"] === Status_codes::HTTP_OK && isset($customer["data"]["list"][0])){
      $this->page_title = $customer["data"]["list"][0]["name"] ?: "";
    }
    $this->view([
        "layouts/header",
        "customers/account/list",
        "layouts/footer",
    ],[
      "customer" => isset($customer["code"]) && $customer["code"] === Status_codes::HTTP_OK && isset($customer["data"]["list"][0]) ? $customer["data"]["list"][0] : [],
    ]);
  }

  public function live($id) {
    $params = [
      "customer_id" => $id,
      "start_date" => $this->input->get("start_date"),
      "end_date" => $this->input->get("end_date"),
      "offset" => $this->input->get("offset"),
      "brand" => $this->input->get("brand"),
      "brand_code" => $this->input->get("brand_code"),
      "oem_code" => $this->input->get("oem_code"),
    ];
    $this->load->model("customers/Account_model","model");
    $res = $this->model->index($params);
    return json_response($res);
  }

}
