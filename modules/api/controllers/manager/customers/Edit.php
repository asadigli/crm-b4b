<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function editMaxOrderLimit($id)
  {
    checkAdmin();
    $params = [
      "id" => (int)$id,
      "max_order_limit" => $this->custom_input->put("max_order_limit") || $this->custom_input->put("max_order_limit") !== "0" ? (float)$this->custom_input->put("max_order_limit") : NULL,
    ];

    validateArray($params, ["id"]);
    if(is_null($params["max_order_limit"])){
      validateArray(["max_order_limit" => null], ["max_order_limit"]);
    }

    $this->load->model("manager/customers/Edit_model", "model");
    $res = $this->model->editMaxOrderLimit($params);

    return json_response($res);
  }

  public function editMaxAllowedOrderLimit($id)
  {
    checkAdmin();
    $params = [
      "id" => (int)$id,
      "max_allowed_order_limit" => $this->custom_input->put("max_allowed_order_limit") || $this->custom_input->put("max_allowed_order_limit") !== "0" ? (float)$this->custom_input->put("max_allowed_order_limit") : NULL,
    ];

    validateArray($params, ["id"]);
    if(is_null($params["max_allowed_order_limit"])){
      validateArray(["max_allowed_order_limit" => null], ["max_allowed_order_limit"]);
    }

    $this->load->model("manager/customers/Edit_model", "model");
    $res = $this->model->editMaxAllowedOrderLimit($params);

    return json_response($res);
  }

  public function editHasOrderLimit($id)
  {
    checkAdmin();
    $params = [
      "id" => (int)$id,
      "has_order_limit" => $this->custom_input->put("has_order_limit") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
    ];

    $this->load->model("manager/customers/Edit_model", "model");
    $res = $this->model->editHasOrderLimit($params);

    return json_response($res);
  }

  public function editIsBlocked($id)
  {
    checkAdmin();
    $params = [
      "id" => (int)$id,
      "is_blocked" => (string)$this->custom_input->put("is_blocked") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
    ];

    $this->load->model("manager/customers/Edit_model", "model");
    $res = $this->model->editIsBlocked($params);

    return json_response($res);
  }

}
