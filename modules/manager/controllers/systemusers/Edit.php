<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true,["main_admin"]);
    $this->load->model("systemusers/Edit_model", "model");
  }

  public function index($id)
  {
    $params = [
      "id" => $id,
      "firstname" => $this->custom_input->put("firstname"),
      "lastname" => $this->custom_input->put("lastname"),
      "email" => $this->custom_input->put("email"),
      "phone" => $this->custom_input->put("phone"),
      "dashboard" => $this->custom_input->put("dashboard"),
      "group_id" => $this->custom_input->put("group_id"),
      "role" => $this->custom_input->put("role"),
      "blocked" => $this->custom_input->put("blocked"),
    ];

    $res = $this->model->index($params);
    return json_response($res);
  }

  public function editPassword($id)
  {
    $params = [
      "id" => $id,
      "type" => $this->custom_input->put("type"),
      "password" => $this->custom_input->put("password"),
    ];
    if ($params["type"] === "edit-password") {
      $params["old_password"] = $this->custom_input->put("old_password");
      $params["confirm_password"] = $this->custom_input->put("confirm_password");
    }
    $res = $this->model->editPassword($params);
    return json_response($res);
  }

  public function delete($id)
  {
    $params = [
      "id" => $id
    ];
    $res = $this->model->delete($params);
    return json_response($res);
  }

  function editGroup($id){
    $params = [
      "id" => $id,
      "groups" => $this->custom_input->put("groups"),
    ];
    $res = $this->model->editGroup($params);

    return json_response($res);
  }
}
