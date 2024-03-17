<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function transferOrder($id)
  {
    $auth_user = checkAdmin(null, true);
    $params = [
      "id" => $id,
      "userid" => (int)headers("userid") ?: null,
      "auth_user" => $auth_user,
      "group_id" => $this->custom_input->put("group_id",[
        "integer" => true,
      ]),
      "comment" => $this->custom_input->put("comment",[
        "symbols" => ["'","\"","\\","/","`"]
      ]),
      "order_detail_list" => $this->custom_input->put("order_detail_list"),
      "now" => now(),
    ];

    validateArray($params, ["group_id", "id", "order_detail_list"]);


    $this->load->model("manager/b4b/orders/Edit_model", "model");
    $res = $this->model->transferOrder($params);
    return json_response($res);
  }

  public function editStatus($id)
  {
    $auth_user = checkAdmin(null, true);
    $params = [
      "id" => $id,
      "userid" => (int)headers("userid") ?: null,
      "group_id" => $this->custom_input->put("group_id",[
        "integer" => true,
      ]),
      "status" => (string)$this->custom_input->put("status"),
    ];

    validateArray($params, ["group_id", "id", "status"]);

    checkAdminOrderGroup($params["group_id"],$auth_user);

    $order_statuses = special_codes("b4b_orders.statuses");
    if(!in_array($params["status"], array_keys($order_statuses))){
      return json_response(rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Status not found")
      ));
    }

    $this->load->model("manager/b4b/orders/Edit_model", "model");
    $res = $this->model->editStatus($params);
    return json_response($res);
  }

  public function statusConfirm($id)
  {
    $auth_user = checkAdmin(null, true);
    $params = [
      "id" => $id,
      "userid" => (int)headers("userid") ?: null,
      "group_id" => $this->custom_input->put("group_id",[
        "integer" => true,
      ]),
    ];

    validateArray($params, ["group_id", "id"]);

    checkAdminOrderGroup($params["group_id"],$auth_user);

    $this->load->model("manager/b4b/orders/Edit_model", "model");
    $res = $this->model->statusConfirm($params);
    return json_response($res);
  }

  public function statusFinish($id)
  {
    $auth_user = checkAdmin(null, true);
    $params = [
      "id" => $id,
      "userid" => (int)headers("userid") ?: null,
      "group_id" => $this->custom_input->put("group_id",[
        "integer" => true,
      ]),
    ];

    validateArray($params, ["group_id", "id"]);

    checkAdminOrderGroup($params["group_id"],$auth_user);

    $this->load->model("manager/b4b/orders/Edit_model", "model");
    $res = $this->model->statusFinish($params);
    return json_response($res);
  }

  public function statusCancel($id)
  {
    $auth_user = checkAdmin(null, true);
    $params = [
      "id" => $id,
      "userid" => (int)headers("userid") ?: null,
      "group_id" => $this->custom_input->put("group_id",[
        "integer" => true,
      ]),
    ];

    validateArray($params, ["group_id", "id"]);

    checkAdminOrderGroup($params["group_id"],$auth_user);

    $this->load->model("manager/b4b/orders/Edit_model", "model");
    $res = $this->model->statusCancel($params);
    return json_response($res);
  }
}
