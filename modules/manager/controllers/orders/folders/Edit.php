<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true);
  }

  public function editName($id)
  {
    $params = [
      "id" => $id,
      "name" => $this->custom_input->put("name"),
    ];

    $this->load->model("orders/folders/Edit_model", "model");
    $res = $this->model->editName($params);

    return json_response($res);
  }

  public function editDescription($id)
  {
    $params = [
      "id" => $id,
      "description" => $this->custom_input->put("description"),
    ];

    $this->load->model("orders/folders/Edit_model", "model");
    $res = $this->model->editDescription($params);

    return json_response($res);
  }

  public function editIsActive($id)
  {
    $params = [
      "id" => $id,
      "is_active" => $this->custom_input->put("is_active"),
    ];

    $this->load->model("orders/folders/Edit_model", "model");
    $res = $this->model->editIsActive($params);

    return json_response($res);
  }

  public function delete($id)
  {
    $params = [
      "id" => $id,
    ];

    $this->load->model("orders/folders/Edit_model", "model");
    $res = $this->model->delete($params);

    return json_response($res);
  }

  public function removeOrder($id)
  {
    $params = [
      "id" => $id,
      "order_id" => $this->custom_input->put("order_id"),
    ];

    $this->load->model("orders/folders/Edit_model", "model");
    $res = $this->model->delete($params);

    return json_response($res);
  }
}
