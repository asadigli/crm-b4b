<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand extends MY_Controller{

  function __construct(){
    parent::__construct();
    $this->load->model("product/Brand_model","model");
  }

  function getAll(){
    $params = [
      "limit" => (int)$this->input->get("limit"),
      "offset" => (int)$this->input->get("offset"),
      "user" => $this->input->get("user"),
      "version" => $this->input->get("version")
    ];
    $res = $this->model->getAll($params);
    $this->response($res);
  }

  function addNew(){
    $params = [
      "user" => $this->input->post("user") ?: null,
      "order" => $this->input->post("order") ?: 0,
      "name" => $this->input->post("name") ?: null,
      "image" => $this->input->post("image") ?: null,
      "description" => $this->input->post("description") ?: null,
      "status" => (string)$this->input->post("status") === "0" ? "0" : "1",
    ];
    $res = $this->model->addNew($params);
    $this->response($res);
  }

  function update(){
    $params = [
      "user" => $this->custom_input->put("user") ?: null,
      "brand" => $this->custom_input->put("brand") ?: null,
      "order" => $this->custom_input->put("order") ?: 0,
      "name" => $this->custom_input->put("name") ?: null,
      "image" => $this->custom_input->put("image") ?: null,
      "description" => $this->custom_input->put("description") ?: null,
      "status" => (string)$this->custom_input->put("status") === "0" ? "0" : "1",
    ];
    $res = $this->model->update($params);
    $this->response($res);
  }

  function details(){
    $params = [
      "brand" => $this->input->get("brand") ?: null,
    ];
    $res = $this->model->details($params);
    $this->response($res);
  }

  function delete(){
    $params = [
      "user" => $this->custom_input->delete("user") ?: null,
      "brand" => $this->custom_input->delete("brand") ?: null,
      "date" => $this->date
    ];
    $res = $this->model->delete($params);
    $this->response($res);
  }


  function ordering(){
    $params = [
      "user" => $this->custom_input->put("user") ?: null,
      "list" => $this->custom_input->put("list")
    ];
    $res = $this->model->ordering($params);
    $this->response($res);
  }

}
