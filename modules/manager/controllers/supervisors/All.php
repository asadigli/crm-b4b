<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct(){
    parent::__construct();
    Auth::check(true,["admin","main_admin"]);
    $this->load->model("supervisors/All_model", "model");
  }

  function index() {
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/supervisors/index.js",
    ];

    $this->page_title = lang("Supervisors");
    $this->view([
      "layouts/header",
      "supervisors/index",
      "layouts/footer",
    ]);
  }

  function live(){
    $params = [
      "is_developer" => Auth::isDeveloper() || Auth::isMainDeveloper()
    ];
    $res = $this->model->index($params);
    return json_response($res);
  }

  function add(){
    $params = [
      "name" => $this->input->post("name"),
      "surname" => $this->input->post("surname"),
      "ava_name" => $this->input->post("ava_name"),
      "phone" => $this->input->post("phone"),
      "email" => $this->input->post("email"),
      "whatsapp" => $this->input->post("whatsapp"),
      "image" => $this->input->post("image"),
    ];
    $res = $this->model->add($params);
    return json_response($res);
  }

  function edit(){
    $params = [
      "id" => $this->custom_input->put("id"),
      "name" => $this->custom_input->put("name"),
      "surname" => $this->custom_input->put("surname"),
      "ava_name" => $this->custom_input->put("ava_name"),
      "phone" => $this->custom_input->put("phone"),
      "email" => $this->custom_input->put("email"),
      "whatsapp" => $this->custom_input->put("whatsapp"),
      "image" => $this->custom_input->put("image"),
    ];
    $res = $this->model->edit($params);
    return json_response($res);
  }

}
