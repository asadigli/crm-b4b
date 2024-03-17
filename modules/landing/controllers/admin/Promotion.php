<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promotion extends MY_Controller{

  function __construct(){
    parent::__construct();
    if (!$this->admin) {redirect(base_url('/'));}
  }

  function addView(){
    $title = lang('Promotion control');
    $this->load->view('admin/promotion/add', ['title' => $title]);
  }

  function editView($id){
    $this->load->model("Promotion_model","model");
    $params = [
      "user" => $this->auth_user,
      "id" => $id,
      "version" => "full",
    ];
    $res = $this->model->details($params);
    // return json_response($res,"no_auth");die;
    if (!isset($res['code']) || $res['code'] !== 200) {
      $this->redError(404,base_url(),lang("Promotion not found"));
      die;
    }

    $title = lang('Promotion edit');
    $this->load->view('admin/promotion/edit', ["title" => $title,"data" => $res["data"],"id" => $id]);
  }

  function listView(){
    $title = lang('Promotion list');
    $this->load->view('admin/promotion/list', ['title' => $title]);
  }


  function delete($promotion){
    $this->load->model("Promotion_model","model");
    $params = [
      "user" => $this->auth_user,
      "news" => $promotion,
    ];
    $res = $this->model->delete($params);
    return json_response($res);
  }

  function addNew(){
    $this->load->model("Promotion_model","model");
    $params = [
      "user" => $this->auth_user,
      "type" => $this->input->post("type"),
      "title" => $this->input->post("title"),
      "date" => $this->input->post("date"),
      "details" => $this->input->post("details"),
      "type" => "promotion",
      "images" => $this->input->post("images"),
      "lang" => $this->input->post("lang"),
      "tags" => $this->input->post("tags"),
      "status" => $this->input->post("status"),
    ];
    $res = $this->model->addNew($params);
    return json_response($res);
  }

  function update($promotion){
    $this->load->model("Promotion_model","model");
    $params = [
      "user" => $this->auth_user,
      "news" => $promotion,
      "title" => $this->input->post("title"),
      "details" => $this->input->post("details"),
      "tags" => $this->input->post("tags"),
      "images" => $this->input->post("images"),
      "exist_images" => $this->input->post("exist_images"),
      "status" => $this->input->post("status"),
    ];
    $res = $this->model->update($params);
    return json_response($res);
  }

  function changeStatus($promotion){
    $this->load->model("Promotion_model","model");
    $params = [
      "user" => $this->auth_user,
      "news" => $promotion,
      "status" => $this->input->post("status"),
    ];
    $res = $this->model->changeStatus($params);
    return json_response($res);
  }


}
