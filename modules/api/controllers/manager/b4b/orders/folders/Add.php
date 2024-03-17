<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function index()
  {
    $auth_user = checkAdmin(null, true);
    $params = [
      "is_dev" => (($auth_user["role"] === special_codes("system_users.roles.developer")) || ($auth_user["is_developer"] === STATUS_ACTIVE)) ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "system_user_id" => (int)headers("userid"),
      "order" => (int)$this->custom_input->post("order"),
      "name" => trim($this->custom_input->post("name",[
        "symbols" => false,
      ])),
      "description" => null,
      "icon" => null,
      "color" => null,
      "is_active" => STATUS_ACTIVE,
    ];

    validateArray($params, ["system_user_id", "name"]);

    $this->load->model("manager/b4b/orders/folders/Add_model", "model");
    $res = $this->model->index($params);
    return json_response($res);
  }

  public function addOrder($id)
  {
    $auth_user = checkAdmin(null, true);
    $params = [
      "is_dev" => (($auth_user["role"] === special_codes("system_users.roles.developer")) || ($auth_user["is_developer"] === STATUS_ACTIVE)) ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "system_user_id" => (int)headers("userid"),
      "auth_user" => $auth_user,
      "folder_id" => (int)$id,
      "order_id" => $this->custom_input->post("order_id"),
    ];

    validateArray($params, ["system_user_id", "order_id", "folder_id"]);

    $this->load->model("manager/b4b/orders/folders/Add_model", "model");
    $res = $this->model->addOrder($params);
    return json_response($res);
  }
}
