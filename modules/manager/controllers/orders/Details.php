<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Details extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true,["admin","main_admin","warehouseman", "manager"]);
  }

  public function index($id)
  {
    $params = [
      "id" => $id,
    ];

    $this->extraCSS = [
    ];
    $this->extraJS = [
      "js/pages/helpers.js",
      "js/pages/orders/details.js",
    ];

    $this->load->model("orders/Details_model", "model");
    $details = $this->model->index($params);

    $this->page_title = lang("Orders");
    $this->view([
      "layouts/header",
      "orders/details",
      "layouts/footer"
    ],[
      "details" => isset($details["code"]) && $details["code"] === 200 ? $details["data"] : [],
    ]);
  }

  public function live($id)
  {
    $params = [
      "id" => $id,
      "group_id" => $this->input->get("group_id"),
      "folder_id" => $this->input->get("folder_id"),
    ];

    $this->load->model("orders/Details_model", "model");
    $res = $this->model->index($params);

    return json_response($res);
  }
}
