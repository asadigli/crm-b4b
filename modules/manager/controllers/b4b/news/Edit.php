<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct(){
    parent::__construct();
    Auth::check(true);
  }

  public function index($id){
    $this->load->model("b4b/news/Edit_model", "model");
    $params = [
      "id" => $id,
      "title" => $this->custom_input->put("title"),
      "body" => $this->custom_input->put("body"),
      "start_date" => $this->custom_input->put("start_date"),
      "end_date" => $this->custom_input->put("end_date"),
      "image" => $this->custom_input->put("image"),
      "type" => $this->custom_input->put("type"),
      "is_active" => $this->custom_input->put("is_active"),
      "is_popup" => $this->custom_input->put("is_popup"),
      "deleted_image" => $this->custom_input->put("deleted_image"),
    ];

    $res = $this->model->index($params);
    return json_response($res);
  }

  public function delete($id){
    $this->load->model("b4b/news/Edit_model", "model");
    $params = [
      "id" => $id
    ];
    $res = $this->model->delete($params);
    return json_response($res);
  }

}
