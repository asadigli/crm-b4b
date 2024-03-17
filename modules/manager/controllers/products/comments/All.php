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
      "start_date" => $this->input->get("start_date"),
      "end_date" => $this->input->get("end_date"),
      "keyword" => $this->input->get("keyword"),
    ];

    $this->extraCSS = [
    ];
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/products/comments/index.js",
    ];


    $this->page_title = lang("Products comments");
    $this->view([
      "layouts/header",
      "products/comments/index",
      "layouts/footer"
    ],[
      "url_params" => $params,
    ]);
  }

  public function live()
  {
    $params = [
      "start_date" => $this->input->get("start_date"),
      "end_date" => $this->input->get("end_date"),
      "keyword" => $this->input->get("keyword"),
      "brand_id" => $this->input->get("brand_id"),
      "entry_id" => $this->input->get("entry_id"),
    ];

    $this->load->model("products/comments/All_model", "model");
    $res = $this->model->index($params);

    return json_response($res);
  }

  public function entriesList(){
    $params = [];

    $this->load->model("products/comments/All_model", "model");
    $res = $this->model->entriesList($params);

    return json_response($res);
  }
}
