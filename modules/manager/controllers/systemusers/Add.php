<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true,["main_admin"]);
    $this->load->model("systemusers/Add_model", "model");
  }

  public function index()
  {
    $params = [
      "name" => $this->input->post("name"),
      "surname" => $this->input->post("surname"),
      "email" => $this->input->post("email"),
      "phone" => $this->input->post("phone"),
      "group" => $this->input->post("group"),
      "role" => $this->input->post("role"),
      "dashboard" => $this->input->post("dashboard"),
      "password" => $this->input->post("password"),
      "creator_id" => Auth::user(),
      "creator_name" => Auth::fullname() ,
    ];
    $res = $this->model->index($params);
    return json_response($res);
  }

}
