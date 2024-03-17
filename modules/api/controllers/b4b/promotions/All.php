<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
    $this->load->model("b4b/promotions/All_model","model");
  }

  function index(){
    checkCompany();
    $params = [
      "limit" => $this->input->get("limit"),
      "show_main" => $this->input->get("show_main"),
    ];

    $params["type"] = special_codes("pop_up.types")["promotion"];

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
