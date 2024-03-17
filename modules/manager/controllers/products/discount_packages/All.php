<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct(){
    parent::__construct();
    Auth::check(true);
  }

  public function index(){
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/products/discount_packages/index.js",
    ];


    $this->page_title = lang("Discount packages");

    $this->view([
        "layouts/header",
        "products/discount_packages/index",
        "layouts/footer",
    ]);
  }

  public function live(){
    $params = [
      "keyword" => $this->input->get("keyword"),
    ];
// var_dump($params);die;
    $this->load->model("products/discount_packages/All_model", "model");
    $res = $this->model->index($params);
    return json_response($res);
  }

  public function delete($id){
    $this->load->model("products/discount_packages/All_model", "model");
    $params = [
      "id" => $id
    ];
    $res = $this->model->delete($params);
    return json_response($res);
  }

}
