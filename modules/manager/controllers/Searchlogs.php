<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Searchlogs extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true,["main_admin"]);
    $this->load->model("Searchlogs_model", "model");
  }



  public function index(){

    $this->extraJS = [
      "js/helpers.js",
      "js/pages/searchlogs/index.js",
    ];
    $this->page_title = lang("Search logs");
    $this->view([
      "layouts/header",
      "searchlogs/index",
      "layouts/footer",
    ],[
    ]);
  }

  public function action()
  {
    $params = [
      "start_date" => $this->input->get("start_date"),
      "end_date" => $this->input->get("end_date"),
      "customer_id" => $this->input->get("customer_id"),
      "offset" => $this->input->get("offset"),
    ];
    $res = $this->model->index($params);
    return json_response($res);

  }
  public function onlyCustomersAction(){
    $params = [
      "start_date" => $this->input->get("start_date"),
      "end_date" => $this->input->get("end_date"),
      "customer_id" => $this->input->get("customer_id"),

      "search_code" => $this->input->get("search_code"),
      "search_brand" => $this->input->get("search_brand"),
      "search_marka" => $this->input->get("search_marka"),
    ];
    $res = $this->model->onlyCustomers($params);
    return json_response($res);
  }
}
