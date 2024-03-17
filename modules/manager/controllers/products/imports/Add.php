<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true);
  }

  public function index()
  {
    $params = [
      "description" => $this->input->post("description"),
      "excel_file" => $this->input->post("excel_file"),
      "brand_price_rate" => $this->input->post("brand_price_rate"),
      "brand_id" => $this->input->post("brand_id"),
      "currency_id" => $this->input->post("currency_id"),
      "excel_file_64" => $this->input->post("excel_file_64"),
      // "b2b_active" => $this->input->post("b2b_active"),
    ];

    $this->load->model("products/imports/Add_model", "model");
    $res = $this->model->index($params);

    return json_response($res);
  }
}
