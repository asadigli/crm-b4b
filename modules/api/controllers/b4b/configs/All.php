<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function index()
  {
    $this->load->model("b4b/configs/All_model","model");
    $res = $this->model->index([]);

    return json_response($res);
  }

}
