<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends MY_Controller{

  public function __construct(){
    parent::__construct();
    checkHeaders();
  }

  function index($code) {
    checkCompany();
    $params = [
      "customer_id" => $code,
      "remoteb4buserid" => (int)headers("remoteb4buserid") ?: NULL, # it is same thing with customer_id, don't ask why.
      "b4buserid" => (int)headers("b4buserid"),
      "start_date" => $this->input->get("start_date") ?: NULL,
      "end_date" => $this->input->get("end_date") ?: NULL,
      "offset" => (int)$this->input->get("offset") ?: NULL,
      "brand" => $this->input->get("brand") ?: NULL,
      "brand_code" => $this->input->get("brand_code") ?: NULL,
      "oem_code" => $this->input->get("oem_code") ?: NULL,
      "is_excel_export" => $this->input->get("is_excel_export") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
    ];

    validateArray($params, ["customer_id"]);
    $this->load->model("b4b/customers/Account_model","model");
    $res = $this->model->index($params);
    return json_response($res);
  }

  function details($code) {
    checkCompany();
    $params = [
      "customer_id" => $this->input->get("remote_id"),
      "remoteb4buserid" => (int)headers("remoteb4buserid") ?: NULL, # it is same thing with customer_id, don't ask why.
      "code" => $code,
    ];
    validateArray($params, ["code","customer_id"]);
    $this->load->model("b4b/customers/Account_model","model");
    $res = $this->model->details($params);
    return json_response($res);
  }

}
