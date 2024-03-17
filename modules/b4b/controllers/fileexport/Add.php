<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true);
  }

  public function addToHistory()
  {
    $params = [
      "name" => $this->input->post("name"),
      "key" => $this->input->post("key"),
      "params" => $this->input->post("params"),
    ];

    $this->load->model("fileexport/Add_model", "model");
    $res = $this->model->addToHistory($params);
    return json_response($res);
  }
}
