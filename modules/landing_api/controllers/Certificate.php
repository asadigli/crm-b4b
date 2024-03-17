<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificate extends MY_Controller{

  function __construct()
  {
    parent::__construct();
    $this->load->model("Certificate_model","model");
  }

  function addNew(){
    $params = [
      "user" => $this->input->post("user"),
      "name" => $this->input->post("name"),
      "description" => $this->input->post("description"),
      "order" => $this->input->post("order"),
      "status" => $this->input->post("status"),
      "files" => $this->input->post("files")
    ];
    $data = $this->model->addNew($params);
    $this->response($data);
  }

  function getList(){
    $params = [
      "limit" => $this->input->get("limit"),
      "offset" => $this->input->get("offset")
    ];
    $data = $this->model->getList($params);
    $this->response($data);
  }

  function delete(){
    $params = [
      "user" => $this->custom_input->delete("user"),
      "certificate" => $this->custom_input->delete("certificate"),
      "date" => $this->date
    ];
    $data = $this->model->delete($params);
    $this->response($data);
  }

}
