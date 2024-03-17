<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct(){
    checkHeaders();
    parent::__construct();
    $this->load->model("manager/b4b/sliders/All_model", "model");
  }

  public function index(){
    checkAdmin();
    $params = [
      "type" => special_codes("b4b_slides.types.main"),
    ];
    $res = $this->model->index($params);
    return json_response($res);
  }

}
