<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller{

  function __construct(){
    parent::__construct();
  }

  function adminAuth(){
    if ($this->admin) {redirect(base_url('/'));}
    $title = lang('Login');
    $this->load->view('admin/user/login', ['title' => $title]);
  }

  function addnewuser(){
    if (!$this->main_admin) {redirect(base_url('/'));}
    $this->load->model("admin/User_model","model");
    $params = [
      "user" => $this->auth_user,
      "name" => $this->input->post("name"),
      "surname" => $this->input->post("surname"),
      "email" => $this->input->post("email"),
      "password" => $this->input->post("password"),
      "phone" => $this->input->post("phone"),
      "gender" => $this->input->post("gender"),
      "role" => $this->input->post("role"),
    ];
    // return json_response($params,"no_auth");die;
    $data = $this->model->create($params);
    return json_response($data);
  }


}
