<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct(){
    parent::__construct();
    Auth::check(true);
  }

  public function index(){
    $this->load->model("b4b/news/Add_model", "model");

    $params = [
      "title" => $this->input->post("title"),
      "body" => $this->input->post("body"),
      "start_date" => $this->input->post("start_date"),
      "end_date" => $this->input->post("end_date"),
      "image" => $this->input->post("image"),
      "type" => $this->input->post("type"),
      "is_active" => $this->input->post("is_active"),
      "is_popup" => $this->input->post("is_popup"),
    ];

    $res = $this->model->index($params);
    return json_response($res);
  }

}
