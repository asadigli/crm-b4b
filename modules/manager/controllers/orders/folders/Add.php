<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true);
  }

  public function index()
  {
    $params = [
      "name" => $this->input->post("name"),
      "description" => $this->input->post("description"),
      "order" => $this->input->post("order"),
      "is_active" => $this->input->post("is_active"),
      "icon" => $this->input->post("icon"),
      "color" => $this->input->post("color"),
    ];

    $this->load->model("orders/folders/Add_model", "model");
    $res = $this->model->index($params);

    return json_response($res);
  }


  public function addOrder($id)
  {
    $params = [
      "order_id" => $this->input->post("order_id"),
      "id" => $id,
    ];

    $this->load->model("orders/folders/Add_model", "model");
    $res = $this->model->addOrder($params);

    return json_response($res);
  }
}
