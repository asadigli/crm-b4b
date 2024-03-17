<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    checkCompany();
    $params = [];

    $this->load->model("b4b/warehouses/All_model", "model");
    $res = $this->model->index($params);
    return json_response($res);
  }
}
