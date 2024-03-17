<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function index()
  {
    $auth_user = checkAdmin(null, true);
    $params = [
      "is_dev" => ($auth_user["role"] === special_codes("system_users.roles.developer")) || ($auth_user["is_developer"] === STATUS_ACTIVE),
      "system_user_id" => (int)headers("userid"),
      "auth_user" => $auth_user,
    ];

    $this->load->model("manager/b4b/orders/folders/All_model", "model");
    $res = $this->model->index($params);
    return json_response($res);
  }

  public function list()
  {
    $auth_user = checkAdmin(null, true);
    $params = [
      "is_dev" => ($auth_user["role"] === special_codes("system_users.roles.developer")) || ($auth_user["is_developer"] === STATUS_ACTIVE),
      "system_user_id" => (int)headers("userid"),
      "order_id" => $this->input->get("order_id"),
      "auth_user" => $auth_user,
    ];

    $this->load->model("manager/b4b/orders/folders/All_model", "model");
    $res = $this->model->list($params);
    return json_response($res);
  }

  public function ordersList($id)
  {
    $auth_user = checkAdmin(null, true);
    $params = [
      "is_dev" => ($auth_user["role"] === special_codes("system_users.roles.developer")) || ($auth_user["is_developer"] === STATUS_ACTIVE),
      "system_user_id" => (int)headers("userid"),
      "folder_id" => (int)$id,
    ];

    validateArray($params, ["folder_id"]);

    $this->load->model("manager/b4b/orders/folders/All_model", "model");
    $res = $this->model->ordersList($params);
    return json_response($res);
  }
}
