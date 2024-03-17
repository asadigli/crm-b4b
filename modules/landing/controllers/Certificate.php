<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificate extends MY_Controller{

  function __construct(){
    parent::__construct();
    $this->load->model("Certificate_model","model");
  }

  function getAll(){
    $params = [
      "limit" => 1000
    ];
    $data = $this->model->getAll($params);
    return json_response($data);
  }

  function addNew(){
    $params = [
      "user" => $this->auth_user,
      "name" => $this->input->post("name"),
      "description" => $this->input->post("description"),
      "files" => $this->input->post("files"),
    ];
    $data = $this->model->addNew($params);
    return json_response($data);
  }

  function delete(){
    $params = [
      "user" => $this->auth_user,
      "certificate" => $this->input->post("certificate"),
    ];
    $data = $this->model->delete($params);
    return json_response($data);
  }

}
