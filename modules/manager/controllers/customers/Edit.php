<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true,["manager", "main_admin","admin"]);
  }

  public function editMaxOrderLimit($id)
  {
    $params = [
      "id" => $id,
      "max_order_limit" => $this->custom_input->put("max_order_limit")
    ];

    $this->load->model("customers/Edit_model", "model");
    $res = $this->model->editMaxOrderLimit($params);
    return json_response($res);
  }

  public function editMaxAllowedOrderLimit($id)
  {
    $params = [
      "id" => $id,
      "max_allowed_order_limit" => $this->custom_input->put("max_allowed_order_limit")
    ];

    $this->load->model("customers/Edit_model", "model");
    $res = $this->model->editMaxAllowedOrderLimit($params);
    return json_response($res);
  }

  public function editHasOrderLimit($id)
  {
    $params = [
      "id" => $id,
      "has_order_limit" => $this->custom_input->put("has_order_limit")
    ];

    $this->load->model("customers/Edit_model", "model");
    $res = $this->model->editHasOrderLimit($params);
    return json_response($res);
  }

  public function editIsBlocked($id)
  {
    $params = [
      "id" => $id,
      "is_blocked" => $this->custom_input->put("is_blocked")
    ];

    $this->load->model("customers/Edit_model", "model");
    $res = $this->model->editIsBlocked($params);
    return json_response($res);
  }

}
