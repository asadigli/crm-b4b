<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('admin/Main_model','model');
    if (!$this->admin) {redirect(base_url('/'));}
  }

  function checkUser(){
    $email = $this->input->get('email');
    $data = $this->model->checkUser($email);
    return json_response($data);
  }


  function updateInfo($user){
    $params = [
      "operator" => $this->auth_user,
      "user" => $user,
      "email" => $this->input->post("email"),
      "gender" => $this->input->post("gender"),
      "role" => $this->input->post("role")
    ];
    $data = $this->model->updateInfo($params);
    return json_response($data);
  }

  function updateStatus($user){
    $params = [
      "operator" => $this->auth_user,
      "user" => $user,
      "blocked" => $this->input->post("blocked")
    ];
    $data = $this->model->updateStatus($params);
    return json_response($data);
  }

  function deleteUser($user){
    if (!$this->main_admin) {
      return [];
    }
    $params = [
      "operator" => $this->auth_user,
      "user" => $user,
    ];
    $data = $this->model->deleteUser($params);
    return json_response($data);
  }


  function users(){
    $this->load->view('admin/user/list');
  }

  function usersLive(){
    $params = [
      "user" => $this->auth_user,
      "role" => $this->input->get('role'),
    ];
    $res = $this->model->usersLive($params);
    return json_response($res);
  }

  function homePageProducts(){
    $this->load->view('admin/product/home_prods');
  }

  function homeProducts(){
    $user = $this->auth_user;
    $res = $this->model->homeProducts($user);
    return json_response($res);
  }

  function checkCode(){
    $code = $this->input->get("code");
    $res = $this->model->checkCode($code);
    return json_response($res);
  }

  function addHP_product(){
    $params = [
      "user" => $this->auth_user,
      "product" => $this->input->post("product"),
      "code" => $this->input->post("code"),
      "reset" => $this->input->post("reset"),
    ];
    $res = $this->model->addHP_product($params);
    return json_response($res);
  }

  function updateProductOrders(){
    $params = [
      "user" => $this->auth_user,
      "list" => $this->input->post("list"),
    ];
    $res = $this->model->updateProductOrders($params);
    return json_response($res);
  }

  function updateProductLimit(){
    $params = [
      'user' => $this->auth_user,
      'config_name' => 'main_page_products',
      'config_value' => $this->input->post('limit'),
    ];
    $res = $this->model->updateProductLimit($params);
    return json_response($res);
  }

  function certificateControl(){
    $title = lang('Certificate control');
    $this->load->view('admin/certificate_control', ['title' => $title]);
  }

  function dashboard(){
    $title = lang('Dashboard');

    $this->load->model("contact/All_model", "contact_model");
    $contacts = $this->contact_model->index([]);
    $requests = $this->contact_model->request([]);

    $this->load->view(
      'admin/dashboard',
      [
        'title' => $title,
        "contacts" => isset($contacts["code"]) && $contacts["code"] === Status_codes::HTTP_OK ? $contacts["data"] : [],
        "requests" => isset($requests["code"]) && $requests["code"] === Status_codes::HTTP_OK ? $requests["data"] : []
      ]
    );
  }

  function userProfileEdit(){
    $title = lang('Profile edit');
    $this->load->view('admin/user/change', ['title' => $title]);
  }


  // editFaq
  // deleteFaq
  // editAbout

  function pageList(){
    $params = [
      "user" => $this->auth_user,
      "type" => $this->input->get("type"),
      "lang" => $this->input->get("lang"),
      "version" => "full"
    ];
    $data = $this->model->pageList($params);
    return json_response($data);
  }

  function addFaq(){
    $params = [
      "user" => $this->auth_user,
      "type" => "faq",
      "details" => $this->input->post("details"),
      "title" => $this->input->post("title"),
      "status" => $this->input->post("status"),
      "lang" => $this->input->post("lang"),
    ];
    $data = $this->model->addFaq($params);
    return json_response($data);
  }

  function deleteFaq($id){
    $params = [
      "user" => $this->auth_user,
      "id" => $id,
    ];
    $data = $this->model->deleteFaq($params);
    return json_response($data);
  }

  function addAbout(){
    $params = [
      "user" => $this->auth_user,
      "type" => "about",
      "details" => $this->input->post("details"),
      "title" => $this->input->post("title"),
      "status" => $this->input->post("status"),
      "lang" => $this->input->post("lang"),
    ];
    $data = $this->model->addFaq($params);
    return json_response($data);
  }

  function editAbout($id){
    $params = [
      "user" => $this->auth_user,
      "details" => $this->input->post("details"),
      "title" => $this->input->post("title"),
      "status" => $this->input->post("status"),
      "order" => $this->input->post("order"),
      "page" => $id
    ];
    $data = $this->model->editFaq($params);
    return json_response($data);
  }

  function editFaq($id){
    $params = [
      "user" => $this->auth_user,
      "details" => $this->input->post("details"),
      "title" => $this->input->post("title"),
      "status" => $this->input->post("status"),
      "order" => $this->input->post("order"),
      "page" => $id
    ];
    $data = $this->model->editFaq($params);
    return json_response($data);
  }

  // updatePage



}
