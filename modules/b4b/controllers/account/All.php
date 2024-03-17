<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct(){
    parent::__construct();
    Auth::check(true);
  }

  function index() {
    $this->page_title = lang("Reports");

    $this->extraJS = [
      "js/pages/account/all.js",
    ];

    $this->view([
        "layouts/head",
        "layouts/header",
        "account/list",
        "layouts/footer",
    ]);
  }

  function live() {
    $params = [
      "remote_id" => Auth::remote_id(),
      "start_date" => $this->input->get("start_date"),
      "end_date" => $this->input->get("end_date"),
      "offset" => $this->input->get("offset"),
      "brand" => $this->input->get("brand"),
      "brand_code" => $this->input->get("brand_code"),
      "oem_code" => $this->input->get("oem_code"),
      "is_excel_export" => $this->input->get("is_excel_export"),
    ];
    $this->load->model("customer/Account_model","model");
    $res = $this->model->index($params);
    return json_response($res);
  }

}
