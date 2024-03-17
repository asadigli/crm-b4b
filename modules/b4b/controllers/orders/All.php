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
    $params = [
      "end_date" => $this->input->get("end_date"),
      "start_date" => $this->input->get("start_date"),
    ];

    $this->extraCSS = [
    ];
    $this->extraJS = [
      "js/pages/orders/index.js",
    ];

    $this->page_title = lang("Orders");
    $this->view([
      "layouts/head",
      "layouts/header",
      "orders/index",
      "layouts/footer"
    ],[
      "url_params" => $params,
    ]);
  }

  public function live()
  {
    $params = [
      "end_date" => $this->input->get("end_date"),
      "start_date" => $this->input->get("start_date"),
      "offset" => $this->input->get("offset"),
    ];

    $this->load->model("orders/All_model", "model");
    $res = $this->model->index($params);

    return json_response($res);
  }
}
