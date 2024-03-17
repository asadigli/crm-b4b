<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct(){
    parent::__construct();
    Auth::check(true,["developer","main_admin"]);
    $this->load->model("b4b/sliders/Edit_model", "model");
  }

  public function index(){
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/b4b/sliders/index.js"
    ];

    $params = [
      "id" => $this->custom_input->put("id"),
      "title" => $this->custom_input->put("title") ?: null,
      "description" => $this->custom_input->put("description") ?: null,
      "start_date" => $this->custom_input->put("start_date"),
      "end_date" => $this->custom_input->put("end_date"),
      "url" => $this->custom_input->put("url"),
      "image" => $this->custom_input->put("image"),
      "is_active" => $this->custom_input->put("is_active"),
    ];

    $res = $this->model->index($params);
    return json_response($res);
  }

  public function delete($id){
    $res = $this->model->delete($id);
    return json_response($res);
  }


}
