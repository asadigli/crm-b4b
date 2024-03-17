<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Details extends MY_Controller{

  public function __construct(){
    parent::__construct();
    Auth::check(true,["manager", "main_admin","admin"]);
  }

  public function index($id,$code) {
    $this->page_title = lang("Invoice details");

    $this->extraJS = [
      "js/pages/customers/account/details.js",
    ];

    $params = [
      "customer_id" => $id,
      "code" => $code,
    ];

    $this->load->model("customers/All_model", "customer_model");
    $customer = $this->customer_model->index(["customer_id" => $id]);

    $this->page_title = lang("Customer account");
    if(isset($customer["code"]) && $customer["code"] === Status_codes::HTTP_OK && isset($customer["data"]["list"][0])){
      $this->page_title = $customer["data"]["list"][0]["name"] ?: "";
    }

    $this->load->model("customers/Account_model","model");
    $res = $this->model->details($params);

    $this->view([
        "layouts/header",
        "customers/account/details",
        "layouts/footer",
    ],[
      "code" => $code,
      "customer" => isset($customer["code"]) && $customer["code"] === Status_codes::HTTP_OK && isset($customer["data"]["list"][0]) ? $customer["data"]["list"][0] : [],
      "list" => isset($res["data"]) ? $res["data"] : []
    ]);
  }
}
