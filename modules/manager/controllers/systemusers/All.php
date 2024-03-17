<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true,["main_admin"]);
    $this->load->model("systemusers/All_model", "model");
  }

  public function index()
  {
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/systemusers/index.js",
    ];
    $roles = $this->model->roles([]);
    $this->page_title = lang("System users");
    $this->view([
      "layouts/header",
      "systemusers/index",
      "layouts/footer",
    ],[
      "page_title" => lang("System users"),
      "roles" => isset($roles["code"]) && $roles["code"] === Status_codes::HTTP_OK ? $roles["data"] : []
    ]);
  }

  public function live()
  {
    $params = [
      "keyword" => $this->input->get("keyword")
    ];
    $res = $this->model->index($params);

    if (isset($res["code"]) && $res["code"] === Status_codes::HTTP_OK) {
      $res["is_developer"] = Auth::isDeveloper();
    }


    return json_response($res);
  }

  public function groups() {
    $params = [];
    $res = $this->model->groups($params);

    return json_response($res);
  }

  public function roles() {
    $params = [];
    $res = $this->model->roles($params);

    return json_response($res);
  }

  public function orderGroups() {
    $params = [];
    $res = $this->model->orderGroups($params);

    return json_response($res);
  }

  public function searchLogsView(){

    $this->extraJS = [
      "js/helpers.js",
      "js/pages/systemusers/search_logs.js",
    ];
    $this->page_title = lang("Search logs");
    $this->view([
      "layouts/header",
      "systemusers/search_logs",
      "layouts/footer",
    ],[
    ]);
  }

  public function searchLogsAction()
  {
    $params = [
       "start_date" => $this->input->get("start_date"),
       "end_date" => $this->input->get("end_date"),
       "customer_id" => $this->input->get("customer_id"),
       "offset" => $this->input->get("offset"),
    ];
    $res = $this->model->searchLogs($params);
    return json_response($res);

  }
  public function searchLogsOnlyCustomersAction(){
    $params = [
      "start_date" => $this->input->get("start_date"),
      "end_date" => $this->input->get("end_date"),
      "customer_id" => $this->input->get("customer_id"),

      "search_code" => $this->input->get("search_code"),
      "search_brand" => $this->input->get("search_brand"),
      "search_marka" => $this->input->get("search_marka"),
    ];
    $res = $this->model->searchLogsOnlyCustomers($params);
    return json_response($res);
  }
}
