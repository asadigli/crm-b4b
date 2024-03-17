<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true);
  }

  public function list()
  {
    $params = [
    ];

    $this->load->model("currencies/All_model", "model");
    $res = $this->model->list($params);
    return json_response($res);
  }

}
