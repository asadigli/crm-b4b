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
      "js/pages/products/price_offers/index.js",
    ];


    $this->page_title = lang("Products price offers");
    $this->view([
      "layouts/header",
      "products/price_offers/index",
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

    $this->load->model("products/price_offers/All_model", "model");
    $res = $this->model->index($params);

    return json_response($res);
  }
}
