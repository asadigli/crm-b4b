<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends MY_Controller{

  function __construct(){
    parent::__construct();
    $this->load->model("News_model","model");
  }

  function getNewsList(){
    $params = [
      "keyword" => $this->custom_input->get("keyword",[
        "strip_html" => TRUE
      ]),
      "limit" => (int)$this->input->get("limit"),
      "offset" => (int)$this->input->get("offset"),
      "user" => $this->input->get("user"),
      "version" => $this->input->get("version"),
      "type" => "news",
      "lang" => $this->lang,
    ];
    $res = $this->model->getList($params);
    $this->response($res);
  }

  function getPromotionsList(){
    $params = [
      "keyword" => $this->custom_input->get("keyword",[
        "strip_html" => TRUE
      ]),
      "limit" => (int)$this->input->get("limit"),
      "offset" => (int)$this->input->get("offset"),
      "user" => $this->input->get("user"),
      "version" => $this->input->get("version"),
      "type" => "promotion",
      "lang" => $this->lang,
    ];
    $res = $this->model->getList($params);
    $this->response($res);
  }

  function addNew(){
    $params = [
      "user" => $this->input->post("user"),
      "type" => $this->input->post("type"),
      "title" => $this->custom_input->post("title",[
        "strip_html" => TRUE
      ]),
      "details" => $this->input->post("details") ?: NULL,
      "date" => $this->input->post("date") ?: $this->date,
      "status" => $this->input->post("status") == "0" ? "0" : "1",
      "image_link" => $this->input->post("image_link"),
      "images" => $this->input->post("images"),
      "tags" => $this->input->post("tags"),
      "lang" => $this->input->post("lang"),
    ];
    $res = $this->model->addNew($params);
    $this->response($res);
  }

  function update(){
    $params = [
      "user" => $this->custom_input->put("user"),
      "news" => $this->custom_input->put("news"),
      "title" => htmlentities($this->custom_input->put("title")),
      "details" => $this->custom_input->put("details") ?: NULL,
      "status" => $this->custom_input->put("status") == "0" ? "0" : "1",
      "images" => $this->custom_input->put("images"),
      "image_link" => $this->custom_input->put("image_link"),
      "tags" => $this->custom_input->put("tags"),
      "exist_images" => $this->custom_input->put("exist_images"),
      "date" => $this->date,
    ];
    $res = $this->model->update($params);
    $this->response($res);
  }

  function changeStatus($news){
    $params = [
      "user" => $this->custom_input->put("user"),
      "news" => $news,
      "status" => $this->custom_input->put("status"),
    ];
    $res = $this->model->changeStatus($params);
    $this->response($res);
  }

  function delete(){
    $params = [
      "user" => $this->custom_input->delete("user"),
      "news" => $this->custom_input->delete("news"),
      "date" => $this->date
    ];
    $res = $this->model->delete($params);
    $this->response($res);
  }

  function details(){
    $params = [
      "slug" => $this->input->get("slug"),
      "id" => $this->input->get("id"),
      "user" => $this->input->get("user"),
      "version" => $this->input->get("version"),
      "lang" => $this->lang,
    ];
    $res = $this->model->details($params);
    $this->response($res);
  }

}
