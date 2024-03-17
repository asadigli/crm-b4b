<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("manager/order_groups/All_model","model");
  }

  function list(){
    $params = [
      "keyword" => $this->custom_input->get("keyword",[
        "symbols" => ["'","\"","\\","/","`"]
      ])
    ];

    $res = $this->model->list($params);

    return json_response($res);
  }

  function add(){
    $params = [
      "creator_id" => $this->input->post("creator_id") ?: headers("userid"),
      "name" => $this->input->post("name"),
      "description" => $this->input->post("description"),
      "details" => $this->input->post("details"),
      "default_start_date" => $this->input->post("default_start_date"),
      "is_remote" => $this->input->post("is_remote"),
      "warehouse_id" => $this->input->post("warehouse_id"),
      "is_active" => $this->custom_input->post("is_active"),
    ];

    checkAdmin($params["creator_id"]);
    $res = $this->model->add($params);

    return json_response($res);
  }


  function detail($id){
    $params = [
      "id" => $id,
      "key" => $this->custom_input->put("key"),
      "value" => $this->custom_input->put("value")
    ];
    $res = $this->model->detail($params);
    return json_response($res);
  }

  function delete($id){
    $params = [
      "id" => $id,
    ];

    $res = $this->model->delete($params);

    return json_response($res);
  }

  function warehouses(){
    $params = [
    ];

    $res = $this->model->warehouses($params);

    return json_response($res);
  }

}
