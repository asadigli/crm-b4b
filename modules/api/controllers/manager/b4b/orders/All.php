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
      "userid" => (int)headers("userid") ?: null,
      "start_date" => $this->input->get("start_date") ?: null,
      "end_date" => $this->input->get("end_date") ?: null,
      "status" => $this->input->get("status") ?: null,
      "folder_id" => $this->input->get("folder_id") ?: null,
      "no_date_filter" => $this->input->get("no_date_filter") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "offset" => (int)$this->input->get("offset") ?: null,
      "group_id" => (int)$this->input->get("group_id") ?: null,
      "auth_user" => $auth_user,
      "keyword" => $this->custom_input->get("keyword",[
        "symbols" => ["'","\"","\\","/","`"]
      ]),
    ];

    validateArray($params, ["userid", "group_id"]);
    checkAdminOrderGroup($params["group_id"],$auth_user);

    validateDate($params["start_date"]);
    validateDate($params["end_date"]);

    $order_statuses = $this->config->item("temprorary_manager_orders_statuses");
    if($params["status"] != STATUS_NEW_ORDERS && !in_array($params["status"], $order_statuses)){
      $params["status"] = "";
    }

    $this->load->model("manager/b4b/orders/All_model", "model");
    $res = $this->model->index($params);
    return json_response($res);
  }

  public function details($id)
  {
    $auth_user = checkAdmin(null, true);
    $params = [
      "id" => $id ?: null,
      "group_id" => (int)$this->input->get("group_id") ?: null,
      "folder_id" => (int)$this->input->get("folder_id") ?: null,
      "userid" => (int)headers("userid") ?: null,
      "auth_user" => $auth_user,
      // "start_date" => $this->input->get("start_date") ?: null,
      // "end_date" => $this->input->get("end_date") ?: null,
    ];

    validateArray($params, ["userid","group_id", "id"]);
    checkAdminOrderGroup($params["group_id"],$auth_user);

    $this->load->model("manager/b4b/orders/All_model", "model");
    $res = $this->model->details($params);
    return json_response($res);
  }

  public function statuses()
  {
    $auth_user = checkAdmin(null, true);

    // "pending" => "0x001",
    // "confirmed" => "0x002",
    // "on_the_way" => "0x003",
    // "shipped" => "0x004",
    // "partially_shipped" => "0x005",
    // "finished" => "0x006",
    $order_statuses = $this->config->item("temprorary_manager_orders_statuses");
    array_unshift($order_statuses, STATUS_NEW_ORDERS);

    return json_response(rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $order_statuses
    ));
  }
}
