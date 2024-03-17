<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Properties extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function brands()
  {
    checkCompany();
    $params = [
      "b4buserid" => (int)headers("b4buserid") ?: null,
    ];

    $this->load->model("b4b/products/Properties_model", "model");
    $res = $this->model->brands($params);
    return json_response($res);
  }

  public function carBrands()
  {
    $params = [
      "b4buserid" => (int)headers("b4buserid") ?: null,
    ];

    $this->load->model("b4b/products/Properties_model", "model");
    $res = $this->model->carBrands($params);
    return json_response($res);
  }

  public function banners()
  {
    $params = [
      "b4buserid" => (int)headers("b4buserid") ?: null,
      "date" => now(),
    ];

    $this->load->model("b4b/products/Properties_model", "model");
    $res = $this->model->banners($params);
    return json_response($res);
  }
}
