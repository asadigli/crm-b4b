<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("manager/configs/Add_model", "model");
  }

  public function index()
  {
    $auth_user = checkAdmin(null, true);
    $auth_role = array_search($auth_user["role"], special_codes("system_users.roles"));

    $params = [
      "group" => $this->input->post("group"),
      "key" => $this->input->post("key"),
      "type" => $this->input->post("type"),
      "value" => $this->input->post("value"),
      "resource" => $this->input->post("resource"),
      "is_active" => $this->input->post("is_active") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "is_dev" => $auth_role === ROLE_DEVELOPER
    ];

    validateArray($params,["group","key","type","value","resource"]);

    $params["type"] = special_codes("configs.types.".$params["type"]);
    $params["resource"] = special_codes("configs.resources.".$params["resource"]);
    $params["group"] = special_codes("configs.groups.".$params["group"]);

    $res = $this->model->index($params);
    return json_response($res);
  }

}
