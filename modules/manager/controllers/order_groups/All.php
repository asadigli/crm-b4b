<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("order_groups/All_model","model");
  }

  function index()
  {
    Auth::check(true,["admin","main_admin"]);
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/order_groups/index.js",
    ];

    $this->view([
      "layouts/header",
      "order_groups/index",
      "layouts/footer"
    ]);
  }

  function list(){
    $params = [
      "keyword" => $this->input->get("keyword")
    ];

    $res = $this->model->index($params);

    return json_response($res);
  }

  function warehouses(){
    Auth::check(true,["admin","main_admin"]);
    $params = [
    ];

    $res = $this->model->warehouses($params);

    return json_response($res);
  }

  function add(){
    Auth::check(true,["admin","main_admin"]);
    $params = [
      "creator_id" => Auth::user(),
      "name" => $this->input->post("name"),
      "description" => $this->input->post("description"),
      "details" => $this->input->post("details"),
      "default_start_date" => $this->input->post("default_start_date"),
      "is_remote" => $this->input->post("is_remote"),
      "warehouse_id" => $this->input->post("warehouse"),
      "is_active" => $this->input->post("is_active"),
    ];
    $res = $this->model->add($params);
    return json_response($res);
  }


  function detail($id){
    Auth::check(true,["admin","main_admin"]);
    $params = [
      "id" => $id,
      "key" => $this->custom_input->put("key"),
      "value" => $this->custom_input->put("value"),
    ];
    $res = $this->model->detail($params);

    return json_response($res);
  }


  function delete($id)
  {
    Auth::check(true,["admin","main_admin"]);
    $params = [
      "id" => $id
    ];

    $res = $this->model->delete($params);
    return json_response($res);
  }


}
