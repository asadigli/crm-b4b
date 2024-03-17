<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
    $this->load->model("b4b/news/All_model","model");
  }

  function index(){
    checkCompany();
    $params = [
      "start_date" => $this->input->get("start_date"),
      "end_date" => $this->input->get("end_date")
    ];

    $params["type"] = special_codes("pop_up.types")["news"];

    $res = $this->model->index($params);
    return json_response($res);
  }

  function details($id){
    checkCompany();
    $params = [
      "id" => $id
    ];

    $res = $this->model->details($params);
    return json_response($res);
  }

}
