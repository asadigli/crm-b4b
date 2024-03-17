<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct() {
    parent::__construct();
    checkHeaders();
    $this->load->model("b4b/banners/All_model","model");
  }

  function index(){
    checkCompany();
    $params = [];
    $res = $this->model->index($params);
    return json_response($res);
  }


}
