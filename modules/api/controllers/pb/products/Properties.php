<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Properties extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function carBrands()
  {
    $params = [
      "p_limit" => (int)$this->input->get("p_limit") ?: null,
      "p_offset" => (int)$this->input->get("p_offset") ?: null,
    ];

    $this->load->model("pb/products/Properties_model", "model");
    $res = $this->model->carBrands($params);
    return json_response($res);
  }

}
