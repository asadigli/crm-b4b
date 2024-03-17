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
    checkAdmin();
    $params = [
      "b4buserid" => (int)headers("b4buserid") ?: null,
      "start_date" => $this->input->get("start_date") ?: null,
      "end_date" => $this->input->get("end_date") ?: null,
    ];

    validateArray($params, ["b4buserid"]);
    $this->load->model("manager/b4b/orders/returns/All_model", "model");
    $res = $this->model->index($params);
    return json_response($res);
  }

  public function details($id)
  {
    checkAdmin();
    $params = [
      "id" => $id ?: null,
      "b4buserid" => (int)headers("b4buserid") ?: null,
      "start_date" => $this->input->get("start_date") ?: null,
      "end_date" => $this->input->get("end_date") ?: null,
    ];

    validateArray($params, ["b4buserid", "id"]);
    $this->load->model("manager/b4b/orders/returns/All_model", "model");
    $res = $this->model->details($params);
    return json_response($res);
  }
}
