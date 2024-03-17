<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct(){
    checkHeaders();
    parent::__construct();
    $this->load->model("manager/caches/All_model", "model");
  }

  function history() {
    $params = [];

    $res = $this->model->history($params);
    return json_response($res);
  }

}
