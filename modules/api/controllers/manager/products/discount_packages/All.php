<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct(){
    parent::__construct();
    checkHeaders();
  }

  public function index(){
    checkAdmin();
    $params = [
      "keyword" => $this->input->get("keyword") ?: null,
    ];
    $params["keyword"] = trim(str_replace(["'", "\""], "", $params["keyword"]));

    $this->load->model("manager/products/discount_packages/All_model", "model");
    $res = $this->model->index($params);

    return json_response($res);
  }

  public function delete($id){
    checkAdmin();

    $this->load->model("manager/products/discount_packages/All_model", "model");

    $params = [
      "id" => $id,
      "now" => now(),
    ];
    $res = $this->model->delete($params);
    return json_response($res);
  }
}
