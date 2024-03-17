<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function editName($id)
  {
    $auth_user = checkAdmin(null, true);
    $params = [
      "is_dev" => (($auth_user["role"] === special_codes("system_users.roles.developer")) || ($auth_user["is_developer"] === STATUS_ACTIVE)) ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "system_user_id" => (int)headers("userid"),
      "name" => trim($this->custom_input->put("name",[
        "symbols" => false,
      ])),
      "id" => (int) $id,
    ];

    validateArray($params, ["name", "id"]);

    $this->load->model("manager/b4b/orders/folders/Edit_model", "model");
    $res = $this->model->editName($params);
    return json_response($res);
  }

  public function editDescription($id)
  {
    $auth_user = checkAdmin(null, true);
    $params = [
      "is_dev" => (($auth_user["role"] === special_codes("system_users.roles.developer")) || ($auth_user["is_developer"] === STATUS_ACTIVE)) ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "system_user_id" => (int)headers("userid"),
      "description" => trim($this->custom_input->put("description",[
        "symbols" => false,
      ])),
      "id" => (int) $id,
    ];

    validateArray($params, ["description", "id"]);

    $this->load->model("manager/b4b/orders/folders/Edit_model", "model");
    $res = $this->model->editDescription($params);
    return json_response($res);
  }


  public function editIsActive($id)
  {
    $auth_user = checkAdmin(null, true);
    $params = [
      "is_dev" => (($auth_user["role"] === special_codes("system_users.roles.developer")) || ($auth_user["is_developer"] === STATUS_ACTIVE)) ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "system_user_id" => (int)headers("userid"),
      "is_active" => $this->custom_input->put("is_active") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "id" => (int) $id,
    ];

    validateArray($params, ["id"]);

    $this->load->model("manager/b4b/orders/folders/Edit_model", "model");
    $res = $this->model->editIsActive($params);
    return json_response($res);
  }

  public function delete($id)
  {
    $auth_user = checkAdmin(null, true);
    $params = [
      "is_dev" => (($auth_user["role"] === special_codes("system_users.roles.developer")) || ($auth_user["is_developer"] === STATUS_ACTIVE)) ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "system_user_id" => (int)headers("userid"),
      "id" => (int) $id,
      "deleted_at" => now(),
    ];

    validateArray($params, ["id"]);

    $this->load->model("manager/b4b/orders/folders/Edit_model", "model");
    $res = $this->model->delete($params);
    return json_response($res);
  }

  public function removeOrder($id)
  {
    $auth_user = checkAdmin(null, true);
    $params = [
      "is_dev" => (($auth_user["role"] === special_codes("system_users.roles.developer")) || ($auth_user["is_developer"] === STATUS_ACTIVE)) ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "system_user_id" => (int)headers("userid"),
      "id" => (int) $id,
      "deleted_at" => now(),
      "order_id" => $this->custom_input->delete("order_id",[
        "symbols" => false
      ]),
    ];

    validateArray($params, ["id", "order_id"]);

    $this->load->model("manager/b4b/orders/folders/Edit_model", "model");
    $res = $this->model->removeOrder($params);
    return json_response($res);
  }
}
