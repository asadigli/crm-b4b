<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Details extends MY_Controller{

  public function __construct(){
    parent::__construct();
    Auth::check(true);
  }

  function index($code) {
    $this->page_title = lang("Invoice details");

    $this->extraJS = [
      "js/pages/account/details.js",
    ];

    $params = [
      "remote_id" => Auth::remote_id(),
      "code" => $code,
    ];
    $this->load->model("customer/Account_model","model");
    $res = $this->model->details($params);
    // var_dump($res);die;

    $this->view([
        "layouts/head",
        "layouts/header",
        "account/details",
        "layouts/footer",
    ],[
      "code" => $code,
      "list" => isset($res["data"]) ? $res["data"] : []
    ]);
  }

  function return(){
    $params = [
      "id" => $this->input->post("id"),
      "quantity" => $this->input->post("quantity"),
      "description" => $this->input->post("description"),
    ];
    $this->load->model("customer/Account_model","model");
    $res = $this->model->return($params);
    return json_response($res);
  }

}
