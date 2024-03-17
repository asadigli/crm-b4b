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
    checkCompany();
    $params = [
      "b4buserid" => (int)headers("b4buserid"),
      "remoteb4buserid" => (int)headers("remoteb4buserid"),
      "user_ip" => headers("useraddress"),
      "b4busercurrency" => headers("b4busercurrency"),
      "user_access_token" => $this->input->post("user_access_token") ?: NULL,
      "product_id" => (int)$this->input->post("product_id") ?: NULL,
      "cart_id" => (int)$this->input->post("cart_id") ?: NULL,
      "product_quantity" => (int)$this->input->post("product_quantity") ?: NULL,
      "product_resource" => $this->input->post("product_resource") ?: NULL,
      "product_sale_price" => $this->input->post("product_sale_price") ?: NULL,
      "product_name" => $this->input->post("product_name") ?: NULL,
      "product_brand_name" => $this->input->post("product_brand_name") ?: NULL,
      "product_brand_code" => $this->input->post("product_brand_code") ?: NULL,
      "product_OEM" => $this->input->post("product_OEM") ?: NULL,
      "product_currency" => $this->input->post("product_currency") ?: NULL,
      "product_description" => $this->input->post("product_description") ?: NULL,
      "final_currency" => $this->input->post("final_currency") ?: NULL,
    ];
    validateArray($params,["product_id", "product_quantity", "b4buserid", "b4busercurrency", "remoteb4buserid"]);

    if($params["product_quantity"] <= 0) {
      return json_response(rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Product count could not be less or equal than zero")
      ));
    }

    if($params["final_currency"] !== $params["b4busercurrency"]) {
      return json_response(rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("for_adding_this_product_you_should_choose_" . $params["product_currency"])
      ));
    }

    validateItemByGroup($params["product_resource"], array_values(special_codes("products.resources")));

    $this->load->model("b4b/cart/Add_model", "model");
    $res = $this->model->index($params);
    return json_response($res);
  }
}
