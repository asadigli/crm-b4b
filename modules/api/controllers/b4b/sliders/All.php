<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct() {
    parent::__construct();
    checkHeaders();
    $this->load->model("b4b/sliders/All_model","model");
  }

  public function index(){
    checkCompany();
    $params = [
      "today" => now(),
      "tomorrow" => date('Y-m-d H:i:s', strtotime(now() . ' +1 day')),
      "type" => special_codes("b4b_slides.types.main")
    ];
    $res = $this->model->index($params);
    return json_response($res);
  }

}
