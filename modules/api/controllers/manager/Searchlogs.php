<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Searchlogs extends MY_Controller{

  function __construct() {
    parent::__construct();
    $this->load->model("manager/Searchlogs_model","model");
  }





  function index(){

    $params = [
      "start_date" => $this->input->get("start_date"),
      "end_date" => $this->input->get("end_date"),
      "customer_id" => $this->input->get("customer_id"),
      "offset" => $this->input->get("offset"),
    ];

    $res = $this->model->index($params);
    return json_response($res);
  }

  function onlyCustomers(){
    $params = [
      "start_date" => $this->input->get("start_date"),
      "end_date" => $this->input->get("end_date"),
      "customer_id" => $this->input->get("customer_id"),

      "search_code" => $this->input->get("search_code"),
      "search_brand" => $this->input->get("search_brand") ?: NULL,
      "search_marka" => $this->input->get("search_marka") ?: NULL,
    ];

    $res = $this->model->onlyCustomers($params);
    return json_response($res);
  }


}
