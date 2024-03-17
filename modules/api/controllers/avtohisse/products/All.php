<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function list()
  {
    $params = [
      "limit" => (int)$this->input->get("limit") ?: NULL
    ];


    $this->load->model("avtohisse/products/All_model","model");

    $res = $this->model->list($params);
    return json_response($res);
  }

}
