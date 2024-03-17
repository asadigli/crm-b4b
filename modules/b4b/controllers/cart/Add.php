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
      "product_id" => $this->input->post("product_id"),
      "cart_id" => $this->input->post("cart_id"),
      "product_quantity" => $this->input->post("product_quantity"),
      "product_resource" => $this->input->post("product_resource"),
      "product_sale_price" => $this->input->post("product_sale_price"),
      "product_name" => $this->input->post("product_name"),
      "product_brand_name" => $this->input->post("product_brand_name"),
      "product_brand_code" => $this->input->post("product_brand_code"),
      "product_OEM" => $this->input->post("product_OEM"),
      "product_currency" => $this->input->post("product_currency"),
      "product_description" => $this->input->post("product_description"),
      "final_currency" => $this->input->post("final_currency"),
      "user_access_token" => $this->input->cookie('access_token', TRUE),
    ];

    $this->load->model("cart/Add_model", "model");
    $res = $this->model->index($params);
    return json_response($res);
  }
}
