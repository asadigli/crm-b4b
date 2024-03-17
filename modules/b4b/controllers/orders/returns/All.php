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
      "js/pages/orders/returns/index.js",
    ];

    $this->page_title = lang("Orders");
    $this->view([
      "layouts/head",
      "layouts/header",
      "orders/returns/index",
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
    ];

    $this->load->model("orders/returns/All_model", "model");
    $res = $this->model->index($params);

    return json_response($res);
  }
}
