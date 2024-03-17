<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("manager/users/Add_model","model");
  }

  function index()
  {
    $params = [
      "name" => $this->input->post("name"),
      "surname" => $this->input->post("surname"),
      "email" => $this->input->post("email"),
      "phone" => $this->input->post("phone"),
      // "group" => $this->input->post("group"),
      "role" => $this->input->post("role") ? strtolower($this->input->post("role")) : null,
      "dashboard" => $this->input->post("dashboard"),
      "password" => $this->input->post("password"),
      "creator_id" => $this->input->post("creator_id"),
      "creator_name" => $this->input->post("creator_name"),
    ];

    $params["role"] = $params["role"] && in_array($params["role"],array_keys(special_codes("system_users.roles"))) ? special_codes("system_users.roles." . $params["role"]) : null;

    validateArray($params,["name","phone","surname","email","phone","password","role"]);

    validateEmail($params["email"]);


    $params["phone"] = preg_replace('/[^0-9.]+/', '', $params["phone"]);
    $res = $this->model->index($params);

    return json_response($res);
  }

}
