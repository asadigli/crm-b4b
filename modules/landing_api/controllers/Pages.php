<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends MY_Controller{

  function __construct(){
    parent::__construct();
    $this->load->model("Pages_model","model");
  }

  function getList(){
    $params = [
      "user" => $this->input->get("user"),
      "version" => $this->input->get("version"),
      "type" => $this->input->get("type"),
      "lang" => $this->input->get("lang") ?: $this->lang,
    ];
    $data = $this->model->getList($params);
    $this->response($data);
  }

  function addNew(){
    $params = [
      "user" => $this->input->post("user"),
      "type" => $this->input->post("type"),
      "order" => $this->input->post("order"),
      "title" => $this->input->post("title"),
      "details" => $this->input->post("details"),
      "status" => $this->input->post("status"),
      "lang" => $this->input->post("lang"),
    ];
    $data = $this->model->addNew($params);
    $this->response($data);
  }

  function update(){
    $params = [
      "user" => $this->input->post("user"),
      "page" => $this->input->post("page"),
      "order" => $this->input->post("order"),
      "title" => $this->input->post("title"),
      "details" => $this->input->post("details"),
      "status" => $this->input->post("status"),
      "lang" => $this->input->post("lang"),
    ];
    $data = $this->model->update($params);
    $this->response($data);
  }

  function delete($page){
    $params = [
      "user" => $this->input->post("user"),
      "page" => $page,
      "date" => $this->date
    ];
    $data = $this->model->delete($params);
    $this->response($data);
  }

}
