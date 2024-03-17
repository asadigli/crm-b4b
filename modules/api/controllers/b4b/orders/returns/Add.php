<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct(){
    parent::__construct();
    checkHeaders();
  }

  function action() {
    checkCompany();
    $params = [
      "id" => $this->input->post("id") ?: null,
      "b4buserid" => (int)headers("b4buserid") ?: null,
      "remoteb4buserid" => (int)headers("remoteb4buserid") ?: null,
      "quantity" => $this->input->post("quantity") ?: null,
      "description" => $this->input->post("description") ?: null,
    ];
    validateArray($params, ["id","quantity"]);
    $this->load->model("b4b/orders/returns/Add_model","model");
    $res = $this->model->action($params);
    return json_response($res);
  }

}
