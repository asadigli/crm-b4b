<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("news_popup/All_model","model");
    Auth::check(true);
  }

  function index(){
    $params = [];

    $res = $this->model->index($params);

    return json_response($res);

  }

}
