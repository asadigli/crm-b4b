<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true);
  }

  public function index()
  {
    $params = [];
    $this->load->model("warehouses/All_model", "model");
    $res = $this->model->index($params);

    return json_response($res);
  }

}
