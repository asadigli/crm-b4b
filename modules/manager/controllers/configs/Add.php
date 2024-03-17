<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true,["main_admin"]);
    $this->load->model("configs/Add_model", "model");
  }

  public function index()
  {
    $params = [
      "group" => $this->input->post("group"),
      "key" => $this->input->post("key"),
      "type" => $this->input->post("type"),
      "value" => $this->input->post("value"),
      "resource" => $this->input->post("resource"),
      "is_active" => $this->input->post("is_active"),
    ];
    $res = $this->model->index($params);
    return json_response($res);
  }

}
