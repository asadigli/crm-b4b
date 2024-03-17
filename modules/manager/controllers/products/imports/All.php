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
      "type" => $this->input->get("type"),
    ];

    $this->extraCSS = [
    ];
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/products/imports/index.js",
    ];


    $this->page_title = lang("Products import");
    $this->view([
      "layouts/header",
      "products/imports/index",
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
    ];

    $this->load->model("products/imports/All_model", "model");
    $res = $this->model->index($params);

    return json_response($res);
  }

  public function delete($id){
    $params = [
      "id" => $id
    ];
    $this->load->model("products/imports/All_model", "model");
    $res = $this->model->delete($params);
    return json_response($res);
  }
}
