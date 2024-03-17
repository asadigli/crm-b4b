<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct() {
    parent::__construct();
    Auth::check(true,["main_admin"]);
    $this->load->model("banners/All_model", "model");
  }

  public function index() {
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/banners/index.js",
    ];

    $this->page_title = lang("Banners");
    $this->view([
      "layouts/header",
      "banners/index",
      "layouts/footer",
    ],[
      "page_title" => lang("Banners"),
    ]);
  }

  function live(){
    $params = [];
    $res = $this->model->index($params);
    return json_response($res);
  }

  function add(){
    $params = [
      "title" => $this->input->post("title"),
      "description" => $this->input->post("description"),
      "start_date" => $this->input->post("start_date"),
      "end_date" => $this->input->post("end_date"),
      "url" => $this->input->post("url"),
      "image" => $this->input->post("image"),
      "is_active" => STATUS_ACTIVE,
    ];
    $res = $this->model->add($params);
    return json_response($res);
  }

  function edit(){
    $params = [
      "id" => $this->custom_input->put("id"),
      "title" => $this->custom_input->put("title"),
      "description" => $this->custom_input->put("description"),
      "start_date" => $this->custom_input->put("start_date"),
      "end_date" => $this->custom_input->put("end_date"),
      "url" => $this->custom_input->put("url"),
      "image" => $this->custom_input->put("image"),
      "is_active" => STATUS_ACTIVE,
    ];
    $res = $this->model->edit($params);
    return json_response($res);
  }

  function delete($id){
    $res = $this->model->delete($id);
    return json_response($res);
  }

}
