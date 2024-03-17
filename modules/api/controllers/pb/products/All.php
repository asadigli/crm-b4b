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
      "p_limit" => (int)$this->input->get("p_limit") ?: null,
      "p_offset" => (int)$this->input->get("p_offset") ?: null,
      "carbrand" => (string)$this->input->get("carbrand") ?: null,
      "brand" => (string)$this->input->get("brand") ?: null,
    ];

    $params["carbrand"] = trim(addslashes($params["carbrand"]));
    $params["brand"] = trim(addslashes($params["brand"]));

    $this->load->model("pb/products/All_model", "model");
    $res = $this->model->list($params);
    return json_response($res);
  }

}
