<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store extends MY_Controller{

  function __construct(){
    parent::__construct();
    $this->load->model("Store_model","model");
  }

  function updateStores(){
    $key = $this->input->get('key');
    if ($key !== $this->config->item("ARM_update_key")) {
      $this->response(rest_response(Status_codes::HTTP_UNAUTHORIZED,"not authorized",[]));die;
    }
    $data = $this->model->updateStores($this->date);
    $this->response($data);
  }

  function getList(){
    $params = [
      "keyword" => $this->input->get("keyword"),
      "city" => $this->input->get("city"),
      "region" => $this->input->get("region"),
      "carbrand" => $this->input->get("carbrand"),
      "storetag" => $this->input->get("storetag"),
      // for pagination
      "limit" => $this->input->get("limit"),
      "offset" => $this->input->get("offset")
    ];
    $data = $this->model->getList($params);
    $this->response($data);
  }

  function getListFullData(){
    $params = [
      "user" => $this->input->get("user"),
      "city" => $this->input->get("city"),
      "limit" => $this->input->get("limit"),
      "offset" => $this->input->get("offset")
    ];
    $data = $this->model->getListFullData($params);
    $this->response($data);
  }

  function addDetail(){
    $params = [
      "user" => $this->input->post("user"),
      "store_id" => $this->input->post("store_id"),
      "type" => $this->input->post("type"),
      "value" => $this->input->post("value")
    ];
    $data = $this->model->addDetail($params);
    $this->response($data);
  }

  function updateStoreDescription(){
    $params = [
      "user" => $this->custom_input->put("user"),
      "store_id" => $this->custom_input->put("store_id"),
      "description" => $this->custom_input->put("description")
    ];
    $data = $this->model->updateStoreDescription($params);
    $this->response($data);
  }

  function changeName(){
    $params = [
      "user" => $this->custom_input->put("user"),
      "store_id" => $this->custom_input->put("store_id"),
      "name" => $this->custom_input->put("name")
    ];
    $data = $this->model->changeName($params);
    $this->response($data);
  }

  function deleteDetail(){
    $params = [
      "user" => $this->custom_input->delete("user"),
      "store_id" => $this->custom_input->delete("store_id"),
      "detail_id" => $this->custom_input->delete("detail_id"),
      "date" => $this->date
    ];
    // $this->response($params);die;
    $data = $this->model->deleteDetail($params);
    $this->response($data);
  }

  function tagList(){
    $params = [
      "lang" => $this->input->get("lang")
    ];
    $data = $this->model->tagList($params);
    $this->response($data);
  }

  function updateDetail(){
    $params = [
      "user" => $this->custom_input->put("user"),
      "store_id" => $this->custom_input->put("store_id"),
      "detail_id" => $this->custom_input->put("detail_id"),
      "type" => $this->custom_input->put("type"),
      "value" => $this->custom_input->put("value")
    ];
    $data = $this->model->updateDetail($params);
    $this->response($data);
  }

  function updateBusinessHours(){
    $params = [
      "user" => $this->custom_input->put("user"),
      "store_id" => $this->custom_input->put("store_id"),
      "week_day" => $this->custom_input->put("week_days")
    ];
    $data = $this->model->updateBusinessHours($params);
    $this->response($data);
  }

  function updateAvatar(){
    $params = [
      "user" => $this->custom_input->put("user"),
      "store_id" => $this->custom_input->put("store_id"),
      "image" => $this->custom_input->put("image")
    ];
    // $this->response($params);die;
    $data = $this->model->updateAvatar($params);
    $this->response($data);
  }

  function updateStatus(){
    $params = [
      "user" => $this->custom_input->put("user"),
      "store_id" => $this->custom_input->put("store_id"),
      "status" => (string)$this->custom_input->put("status") === "0" ? "0" : "1"
    ];
    // $this->response($params);die;
    $data = $this->model->updateStatus($params);
    $this->response($data);
  }

  function regionList(){
    $data = $this->model->regionList();
    $this->response($data);
  }

  function storeDetails(){
    $slug = $this->input->get("slug");
    $store_id = $this->input->get("store_id");
    $data = $this->model->storeDetails($slug,$store_id);
    $this->response($data);
  }

  function createStore(){
    $params = [
      "user" => $this->custom_input->post("user"),
      "name" => $this->custom_input->post("name",["html" => false]),
      "city" => $this->custom_input->post("city",["strip_html" => true,"symbols" => false]),
      "phone" => $this->custom_input->post("phone",["strip_html" => true,"symbols" => false]),
      "email" => $this->custom_input->post("email",["html" => false]),
      "status" => (string)$this->input->post("status") === "0" ? "0" : "1",
      "description" => $this->custom_input->post("description"),
      "date" => $this->date
    ];
    $data = $this->model->createStore($params);
    $this->response($data);
  }

}
