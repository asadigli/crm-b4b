<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("manager/users/Edit_model","model");
  }

  function index($id){
    $params = [
      "id" => $id,
      "firstname" => $this->custom_input->put("firstname") ?: null,
      "lastname" => $this->custom_input->put("lastname") ?: null,
      "email" => $this->custom_input->put("email") ?: null,
      "phone" => $this->custom_input->put("phone") ?: null,
      // "group_id" => $this->custom_input->put("group_id") ?: null,
      "role" => $this->custom_input->put("role") ? strtolower($this->custom_input->put("role")) : null,
      "blocked" => $this->custom_input->put("blocked") ?: null,
      "dashboard" => $this->custom_input->put("dashboard") ?: null
    ];


    validateArray($params,["id","email"]);

    $params["role"] = $params["role"] && in_array($params["role"],array_keys(special_codes("system_users.roles"))) ? special_codes("system_users.roles." . $params["role"]) : null;

    checkAdmin($params["id"]);

    $res = $this->model->index($params);

    return json_response($res);
  }

  function delete($id){
    $params = [
      "id" => $id
    ];

    validateArray($params,["id"]);
    checkAdmin($params["id"]);
    $res = $this->model->delete($params);

    return json_response($res);
  }

  function password($user_id){
    $params = [
      "admin_id" => headers("userid"),
      "user_id" => $user_id,
      "password" => $this->custom_input->put("password"),
      "type" => $this->custom_input->put("type") ?: null
    ];

    if ($params["type"] === "edit-password") {
      $params["old_password"] = $this->custom_input->put("old_password");
      $params["confirm_password"] = $this->custom_input->put("confirm_password");
      validateArray($params,["old_password","confirm_password"]);

      if ($params["confirm_password"] !== $params["password"]) {
        return json_response(
          rest_response(
            Status_codes::HTTP_BAD_REQUEST,
            lang("Passwords don't match")
            )
          );
      }
    }

    validateArray($params,["user_id","password"]);

    checkAdmin($params["user_id"]);

    $res = $this->model->password($params);

    return json_response($res);
  }


    function editGroup($id){
      $params = [
        "admin_id" => headers("userid"),
        "id" => $id,
        "groups" => $this->custom_input->put("groups"),
      ];
      validateArray($params,["id"]);

      checkAdmin($params["id"]);

      $params["groups"] = is_array($params["groups"]) ? $params["groups"] : explode(",",$params["groups"]);
      $res = $this->model->editGroup($params);

      return json_response($res);
    }



}
