<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true,["main_admin"]);
  }

  public function editPrice($id)
  {
    $params = [
      "id" => $id,
      "custom_main_sale_price" => $this->custom_input->put("custom_main_sale_price"),
    ];

    $this->load->model("products/Edit_model", "model");
    $res = $this->model->editPrice($params);
    return json_response($res);
  }

  public function discountPrice($id){
    $params = [
      "id" => $id,
      "discount_price" => $this->custom_input->put("discount_price"),
      "has_discount" => $this->custom_input->put("has_discount"),
    ];

    $this->load->model("products/Edit_model", "model");
    $res = $this->model->discountPrice($params);
    return json_response($res);
  }

  public function hidePrice($id){
    $params = [
      "id" => $id,
      "is_b4b_price_hidden" => $this->custom_input->put("is_b4b_price_hidden"),
    ];

    $this->load->model("products/Edit_model", "model");
    $res = $this->model->hidePrice($params);
    return json_response($res);
  }

  public function isNewFromWarehouse($id)
  {
    $params = [
      "id" => $id,
      "is_new_from_warehouse" => $this->custom_input->put("is_new_from_warehouse"),
      "new_from_warehouse_start_date" => $this->custom_input->put("new_from_warehouse_start_date"),
      "new_from_warehouse_end_date" => $this->custom_input->put("new_from_warehouse_end_date"),
      "new_from_warehouse_image" => $this->custom_input->put("new_from_warehouse_image"),
    ];

    $this->load->model("products/Edit_model", "model");
    $res = $this->model->isNewFromWarehouse($params);
    return json_response($res);
  }
}
