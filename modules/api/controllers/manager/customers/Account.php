<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends MY_Controller{

  public function __construct(){
    parent::__construct();
    checkHeaders();
  }

  public function index($id) {
    checkAdmin();
    $params = [
      "start_date" => $this->input->get("start_date") ?: null,
      "end_date" => $this->input->get("end_date") ?: null,
      "customer_id" => (int)$id,
      "offset" => (int)$this->input->get("offset") ?: null,
      "brand" => $this->input->get("brand") ?: null,
      "brand_code" => $this->input->get("brand_code") ?: null,
      "oem_code" => $this->input->get("oem_code") ?: null,
    ];

    validateArray($params, ["customer_id"]);

    $this->load->model("manager/customers/Account_model","model");
    $res = $this->model->index($params);
    return json_response($res);
  }

  public function brandReportsInDetails() {
    checkAdmin();
    $params = [
      "start_date"    => $this->input->get("start_date") ?: null,
      "end_date"      => $this->input->get("end_date") ?: null,
      "offset"        => (int)$this->input->get("offset") ?: null,
      "brand"         => $this->input->get("brand") ?: null,
      "brand_code"    => $this->input->get("brand_code") ?: null,
      "customer_id"   => $this->input->get("customer_id") ?: null,
    ];
    // validateArray($params, []);
    $this->load->model("manager/customers/Account_model","model");
    $res = $this->model->brandReportsInDetails($params);
    return json_response($res);
  }


  public function brandReports() {
    checkAdmin();
    $params = [
      "start_date"    => $this->input->get("start_date") ?: null,
      "end_date"      => $this->input->get("end_date") ?: null,
      "offset"        => (int)$this->input->get("offset") ?: null,
      "brand"         => $this->input->get("brand") ?: null,
      "brand_code"    => $this->input->get("brand_code") ?: null,
      "customer_id"   => $this->input->get("customer_id") ?: null,
    ];

    $this->load->model("manager/customers/Account_model","model");
    $res = $this->model->brandReports($params);
    return json_response($res);
  }

  public function details($id,$code) {
    checkAdmin();
    $params = [
      "customer_id" => (int)$id,
      "code" => $code,
    ];

    validateArray($params, ["code", "customer_id"]);

    $this->load->model("manager/customers/Account_model","model");
    $res = $this->model->details($params);
    return json_response($res);
  }
}
