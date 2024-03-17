<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true);
  }

  public function index()
  {
    $params = [];

    $this->load->model("orders/folders/All_model", "model");
    $res = $this->model->index($params);

    return json_response($res);
  }

  public function list()
  {
    $params = [
      "order_id" => $this->input->get("order_id"),
    ];

    $this->load->model("orders/folders/All_model", "model");
    $res = $this->model->list($params);

    return json_response($res);
  }

  public function ordersList($id)
  {
    $params = [
      "id" => $id
    ];

    $this->load->model("orders/folders/All_model", "model");
    $res = $this->model->list($params);

    return json_response($res);
  }
}
