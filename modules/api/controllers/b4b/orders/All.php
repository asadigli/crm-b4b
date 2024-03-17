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
    checkCompany();
    $params = [
      "b4buserid" => (int)headers("b4buserid") ?: null,
      "remoteb4buserid" => (int)headers("remoteb4buserid") ?: null,
      "start_date" => $this->input->get("start_date") ?: null,
      "end_date" => $this->input->get("end_date") ?: null,
      "offset" => (int)$this->input->get("offset") ?: null,
    ];

    validateArray($params, ["b4buserid"]);
    $this->load->model("b4b/orders/All_model", "model");
    $res = $this->model->index($params);
    return json_response($res);
  }

  public function details($id)
  {
    checkCompany();
    $params = [
      "id" => $id ?: null,
      "b4buserid" => (int)headers("b4buserid") ?: null,
      "remoteb4buserid" => (int)headers("remoteb4buserid") ?: null,
      "start_date" => $this->input->get("start_date") ?: null,
      "end_date" => $this->input->get("end_date") ?: null,
      "group_id" => (int)$this->input->get("group_id") ?: null,
    ];

    validateArray($params, ["b4buserid", "id"]);
    $this->load->model("b4b/orders/All_model", "model");
    $res = $this->model->details($params);
    return json_response($res);
  }

  public function statuses()
  {
    checkCompany();

    // "pending" => "0x001",
    // "confirmed" => "0x002",
    // "on_the_way" => "0x003",
    // "shipped" => "0x004",
    // "partially_shipped" => "0x005",
    // "finished" => "0x006",

    $order_statuses = [
      "pending",
      "confirmed",
      "finished",
      "canceled",
      "orders_all",
    ];

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success")
    );
  }
}
