<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct(){
    parent::__construct();
    Auth::check(true,["developer", "main_admin"]);
  }

  public function index(){
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/b4b/brand_sliders/index.js"
    ];
    $this->load->model("b4b/brand_sliders/Add_model", "model");

    $params = [
      "title" => $this->input->post("title"),
      "description" => $this->input->post("description"),
      "start_date" => $this->input->post("start_date"),
      "end_date" => $this->input->post("end_date"),
      "url" => $this->input->post("url"),
      "image" => $this->input->post("image"),
      "is_active" => STATUS_ACTIVE,
      "is_clickable" => CLICKABLE,
    ];

    $res = $this->model->index($params);
    return json_response($res);

  }

}
