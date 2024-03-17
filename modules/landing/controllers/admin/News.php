<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends MY_Controller{

  function __construct(){
    parent::__construct();
    if (!$this->admin) {redirect(base_url('/'));}
  }


  function listView(){
    $title = lang('News list');
    $this->load->view('admin/news/list', ['title' => $title]);
  }

  function editView($id){
    $this->load->model("News_model","model");
    $params = [
      "user" => $this->auth_user,
      "id" => $id,
      "version" => "full",
    ];
    $res = $this->model->details($params);
    // return json_response($res,"no_auth");die;
    if (!isset($res['code']) || $res['code'] !== 200) {
      $this->redError(404,base_url(),lang("News not found"));
      die;
    }

    $title = lang('News control');
    $this->load->view("admin/news/edit", ["title" => $title,"data" => $res["data"],"id" => $id]);
  }

  function addView(){
    $title = lang('Create news');
    $this->load->view('admin/news/add', ['title' => $title]);
  }


  function delete(){
    $this->load->model("News_model","model");
    $params = [
      "user" => $this->auth_user,
      "news" => $this->input->post("news"),
    ];
    $res = $this->model->delete($params);
    return json_response($res);
  }

  function addNew(){
    $this->load->model("News_model","model");
    $params = [
      "user" => $this->auth_user,
      "type" => $this->input->post("type"),
      "title" => $this->input->post("title"),
      "date" => $this->input->post("date"),
      "details" => $this->input->post("details"),
      "image_link" => $this->input->post("image_link"),
      "type" => "news",
      "images" => $this->input->post("images"),
      "lang" => $this->input->post("lang"),
      "tags" => $this->input->post("tags"),
      "status" => $this->input->post("status"),
    ];
    $res = $this->model->addNew($params);
    return json_response($res);
  }


  function update($news){
    $this->load->model("News_model","model");
    $params = [
      "user" => $this->auth_user,
      "news" => $news,
      "title" => $this->input->post("title"),
      "image_link" => $this->input->post("image_link"),
      "details" => $this->input->post("details"),
      "tags" => $this->input->post("tags"),
      "images" => $this->input->post("images"),
      "exist_images" => $this->input->post("exist_images"),
      "status" => $this->input->post("status"),
    ];
    // return json_response($params);die;
    $res = $this->model->update($params);
    return json_response($res);
  }

  function changeStatus($news){
    $this->load->model("News_model","model");
    $params = [
      "user" => $this->auth_user,
      "news" => $news,
      "status" => $this->input->post("status"),
    ];
    $res = $this->model->changeStatus($params);
    return json_response($res);
  }



}
