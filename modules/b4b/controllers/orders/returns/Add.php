<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct(){
    parent::__construct();
    Auth::check(true);
  }

  function action() {
    $params = [
      "id" => $this->input->post("id"),
      "quantity" => $this->input->post("quantity"),
      "description" => $this->input->post("description"),
    ];
    $this->load->model("b4b/orders/returns/Add_model","model");
    $res = $this->model->action($params);
    return json_response($res);
  }

}
