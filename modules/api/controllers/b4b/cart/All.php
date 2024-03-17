<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function index()
  {
    $auth_user = checkCompany(NULL, true);
    $params = [
      "b4buserid" => (int)headers("b4buserid") ?: NULL,
      "remoteb4buserid" => (int)headers("remoteb4buserid") ?: NULL,
      "b4busercurrency" => headers("b4busercurrency") ?: NULL,
      "auth_user" => $auth_user,
      "wid" => $this->input->get("wid"),
      "resource" => $this->input->get("resource"),
      "filter_type" => $this->input->get("filter_type"),
      "is_remote" => $this->input->get("is_remote"),
    ];

    validateArray($params,["b4buserid", "b4busercurrency"]);

    $this->load->model("b4b/cart/All_model", "model");
    $res = $this->model->index($params);
    return json_response($res);
  }

  public function cartCount()
  {
    checkCompany();
    $params = [
      "b4buserid" => (int)headers("b4buserid") ?: NULL,
      "remoteb4buserid" => (int)headers("remoteb4buserid") ?: NULL,
      "b4busercurrency" => headers("b4busercurrency") ?: NULL,
    ];

    validateArray($params,["b4buserid", "b4busercurrency"]);

    $this->load->model("b4b/cart/All_model", "model");
    $res = $this->model->cartCount($params);
    return json_response($res);
  }
}
