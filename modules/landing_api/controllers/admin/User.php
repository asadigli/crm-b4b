<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('admin/User_model','model');
  }

  function checkUserExist(){
    $email = $this->input->get('email');
    $res = $this->model->checkUserExist($email);
    $this->response($res);
  }

  function getAll(){
    $params = [
      "user" => $this->input->get("user"),
      "role" => $this->input->get("role")
    ];
    $result = $this->model->getAll($params);
    $this->response($result);
  }

  function updateInfo($user){
    $params = [
      "user" => $user,
      "operator" => $this->custom_input->put("operator"),
      "email" => $this->custom_input->put("email"),
      "gender" => $this->custom_input->put("gender"),
      "role" => $this->custom_input->put("role"),
    ];
    $data = $this->model->updateInfo($params);
    $this->response($data);
  }

  function updateStatus($user){
    $params = [
      "user" => $user,
      "operator" => $this->custom_input->put("operator"),
      "blocked" => $this->custom_input->put("blocked"),
    ];
    $data = $this->model->updateStatus($params);
    $this->response($data);
  }

  function delete($user){
    $params = [
      "user" => $user,
      "operator" => $this->custom_input->delete("operator"),
      "date" => $this->date,
    ];
    $data = $this->model->delete($params);
    $this->response($data);
  }


  function addNew(){
    $params = [
      "user" => $this->input->post("user"),
      "name" => $this->input->post("name"),
      "surname" => $this->input->post("surname"),
      "email" => $this->input->post("email"),
      "password" => $this->input->post("password"),
      "phone" => $this->input->post("phone"),
      "gender" => $this->input->post("gender"),
      "role" => $this->input->post("role"),
   ];
   $data = $this->model->addNew($params);
   $this->response($data);
  }


}
