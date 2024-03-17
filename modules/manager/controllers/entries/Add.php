<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true,["main_admin","admin"]);
    $this->load->model("entries/All_model","model");
  }

  function index()
  {
    $params = [
      "name" => $this->input->post("name"),
      "entry_name" => $this->input->post("entry_name"),
      "surname" => $this->input->post("surname"),
      "phone" => $this->input->post("phone"),
      "email" => $this->input->post("email"),
      "supervisor" => $this->input->post("supervisor"),
      "address" => $this->input->post("address"),
      "city_id" => $this->input->post("city"),
      "depo_id" => $this->input->post("warehouse"),
      "customers" => $this->input->post("customer"),
      "password" => $this->input->post("password"),
      "is_active" => $this->input->post("is_active"),
      "stock_show" => $this->input->post("stock_show"),
      "is_store_active" => $this->input->post("is_store_active"),
      "limit" => $this->input->post("limit"),
    ];

    $res = $this->model->add($params);

    return json_response($res);
  }

}
