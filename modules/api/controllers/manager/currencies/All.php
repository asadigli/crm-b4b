<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
    $this->load->model("manager/currencies/All_model","model");
  }

  function index()
  {
    // checkCompany();
    $params = [
      "b4buserid" => headers("b4buserid"),
    ];

    $res = $this->model->index($params);
    return json_response($res);
  }

  public function list()
  {
    checkAdmin();
    $params = [];
    $res = $this->model->list($params);
    return json_response($res);
  }
}
