<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function index()
  {
    checkAdmin();
    $params = [];

    $this->load->model("manager/warehouses/All_model", "model");
    $res = $this->model->index($params);
    return json_response($res);
  }
}
