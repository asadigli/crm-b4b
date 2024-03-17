<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promotions extends MY_Controller{

  function __construct(){
    parent::__construct();
  }

  protected $path = "promotions/";

  function allList(){

    $this->load->view($this->path."all");
  }

  function addNew(){
    $this->load->view($this->path."add_new");
  }

  function details($slug){
    $this->load->model("Promotion_model","model");
    $params = [
      "slug" => $slug
    ];
    $res = $this->model->details($params);
    if (!isset($res['code']) || $res['code'] !== 200) {
      $this->redError(404,base_url(),lang("Promotion not found"));
      die;
    }
    if ($this->admin && $this->input->get("action") === "edit") {
      $this->load->view($this->path."edit",["slug" => $slug,"data" => $res["data"]]);
    }else{
      $this->load->view($this->path."details",["slug" => $slug,"data" => $res["data"]]);
    }
  }

  function getAll(){
    $this->load->model("Promotion_model","model");
    $params = [
      "user" => $this->auth_user,
      "version" => $this->input->get("version") ?: null,
      "limit" => $this->input->get("limit") ?: 5,
      "offset" => $this->input->get("page") && $this->input->get("page") > 1 ? ($this->input->get("page") - 1)*5 : 0,
      "language" => $this->language
    ];
    $res = $this->model->getAll($params);
    return json_response($res,"no_auth");
  }

  function delete(){
    $this->load->model("Promotion_model","model");
    $params = [
      "user" => $this->auth_user,
      "promotion" => $this->input->post("promotion"),
    ];
    $res = $this->model->delete($params);
    return json_response($res);
  }

  function addNewAction(){
    $this->load->model("Promotion_model","model");
    $params = [
      "user" => $this->auth_user,
      "type" => $this->input->post("type"),
      "title" => $this->input->post("title"),
      "date" => $this->input->post("date"),
      "details" => $this->input->post("details"),
      "type" => "promotion",
      "cover_image" => $this->input->post("cover_image"),
      "status" => $this->input->post("status"),
    ];
    $res = $this->model->addNew($params);
    return json_response($res);
  }


  function update(){
    $this->load->model("Promotion_model","model");
    $params = [
      "user" => $this->auth_user,
      "promotion" => $this->input->post("promotion"),
      "title" => $this->input->post("title"),
      "details" => $this->input->post("details"),
      "cover_image" => $this->input->post("cover_image"),
      "status" => $this->input->post("status"),
    ];
    $res = $this->model->update($params);
    return json_response($res);
  }

}
