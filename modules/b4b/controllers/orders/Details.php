<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Details extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true);
  }

  public function index($id)
  {
    $params = [
      "id" => $id,
      "group_id" => $this->input->get("group_id"),
    ];

    $this->extraCSS = [
    ];
    $this->extraJS = [
      "js/pages/orders/details.js",
    ];

    $this->load->model("orders/Details_model", "model");
    $details = $this->model->index($params);

    $this->page_title = lang("Orders");
    $this->view([
      "layouts/head",
      "layouts/header",
      "orders/details",
      "layouts/footer"
    ],[
      "details" => isset($details["code"]) && $details["code"] === 200 ? $details["data"] : [],
    ]);
  }

}
