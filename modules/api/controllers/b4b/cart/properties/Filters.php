<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filters extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function list()
  {
    checkCompany();
    $params = [
      // "is_group" => $this->input->get("is_group"),
    ];

    $this->load->model("b4b/cart/properties/Filters_model", "model");
    $res = $this->model->list($params);
    return json_response($res);
  }
}
