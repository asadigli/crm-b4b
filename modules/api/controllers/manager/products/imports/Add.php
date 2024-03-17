<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function index()
  {
    $auth_user = checkAdmin(null, true);

    $params = [
      "description" => htmlentities(trim($this->input->post("description"))) ?: NULL,
      "excel_file" => $this->input->post("excel_file") ?: NULL,
      "excel_file_64" => $this->input->post("excel_file_64") ?: NULL,
      "brand_price_rate" => (float)$this->input->post("brand_price_rate") ?: NULL,
      "brand_id" => (int)$this->input->post("brand_id"),
      "currency_id" => (int)$this->input->post("currency_id"),
      // "b2b_active" => $this->input->post("b2b_active") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "deleted_at" => now(),
      "operation_date" => now(),
      "is_dev" => $auth_user["role"] === ROLE_DEVELOPER ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "creator_id" => (int)headers("userid"),
      "creator_ip" => headers("useraddress"),
      "creator_name" => $auth_user["first_name"] . " " . $auth_user["last_name"],
    ];
    validateArray($params, ["description", "excel_file", "excel_file_64", "brand_id"]);

    $params["brand_price_rate"] = $params["brand_price_rate"] ? (float)$params["brand_price_rate"] / 100 : 0;

    $this->load->model("manager/products/imports/Add_model", "model");
    $res = $this->model->index($params);

    return json_response($res);
  }
}
