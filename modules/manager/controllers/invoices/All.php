<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct(){
    parent::__construct();
    Auth::check(true,["admin","main_admin"]);
    $this->load->model("Invoices_model", "model");
  }

  /*
  *
  */
  function brandReportsInDetails() {
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/brand_reports/details.js",
    ];

    $this->page_title = lang("Brand report in details");
    $this->view([
      "layouts/header",
      "invoices/brand_report/details",
      "layouts/footer",
    ],[
      "page_title" => lang("Brand report in details"),
    ]);
  }

  /*
  *
  */
  function liveInDetails(){
    $params = [
      "start_date" => $this->input->get("start_date"),
      "end_date" => $this->input->get("end_date"),
      "brand_code" => $this->input->get("brand_code"),
      "brand" => $this->input->get("brand"),
      "customer_id" => $this->input->get("customer"),
      "offset" => $this->input->get("offset"),
    ];
    $res = $this->model->liveInDetails($params);
    return json_response($res);
  }

  /*
  *
  */
  function brandReports() {
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/brand_reports/all.js",
    ];

    $this->page_title = lang("Brand report");
    $this->view([
      "layouts/header",
      "invoices/brand_report/all",
      "layouts/footer",
    ],[
      "page_title" => lang("Brand report"),
    ]);
  }

  /*
  *
  */
  function live(){
    $params = [
      "start_date" => $this->input->get("start_date"),
      "end_date" => $this->input->get("end_date"),
      "brand_code" => $this->input->get("brand_code"),
      "brand" => $this->input->get("brand"),
      "customer_id" => $this->input->get("customer"),
      "offset" => $this->input->get("offset"),
    ];
    $res = $this->model->index($params);
    return json_response($res);
  }

}
