<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Properties extends MY_Controller{

  public function __construct() {
    parent::__construct();
    checkHeaders();
  }

  public function brands() {
    // checkAdmin();
    $params = [];

    $this->load->model("manager/products/Properties_model", "model");
    $res = $this->model->brands($params);
    return json_response($res);
  }

  public function carBrands() {
    // checkAdmin();

    $params = [];

    $this->load->model("manager/products/Properties_model", "model");
    $res = $this->model->carBrands($params);
    return json_response($res);
  }

  public function productResources(){
    $resources = $this->config->item("product_resources") ?: [];
    if(!$resources){
      return json_response(rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No product resource found")
      ));
    }

    return json_response(rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $resources
    ));
  }


}
