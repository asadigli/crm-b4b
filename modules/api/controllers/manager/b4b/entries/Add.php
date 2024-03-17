<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("manager/b4b/entries/Add_model","model");
  }

  function index()
  {
    $params = [
      "name" => $this->input->post("name"),
      "entry_name" => $this->input->post("entry_name"),
      "surname" => $this->input->post("surname"),
      "email" => $this->input->post("email"),
      "phone" => $this->input->post("phone"),
      "supervisor" => $this->input->post("supervisor"),
      "address" => $this->input->post("address"),
      "city_id" => $this->input->post("city_id"),
      "depo_id" => $this->input->post("depo_id"),
      "customers" => $this->input->post("customers"),
      "password" => $this->input->post("password"),
      "is_active" => $this->input->post("is_active"),
      "stock_show" => $this->input->post("stock_show"),
      "is_store_active" => $this->input->post("is_store_active"),
      "limit" => $this->input->post("limit")
    ];
    validateArray($params,["name","phone","entry_name","surname","email","customers"]);

    validateEmail($params["email"]);

    $params["customers"] = is_array($params["customers"]) ? $params["customers"] : explode(",",$params["customers"]);

    $params["phone"] = preg_replace('/[^0-9.]+/', '', $params["phone"]);

    $res = $this->model->index($params);

    return json_response($res);
  }

}
