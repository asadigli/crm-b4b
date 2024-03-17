<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true,["main_admin","admin"]);
    $this->load->model("entries/All_model","model");
  }

  function password($id){
    $params = [
      "id" => $id,
      "email" => $this->custom_input->put("email"),
      "password" => $this->custom_input->put("password"),
    ];

    $res = $this->model->password($params);

    return json_response($res);
  }

  function delete($id)
  {
    $params = [
      "id" => $id
    ];

    $res = $this->model->delete($params);
    return json_response($res);
  }

  function stock($id){
    $params = [
      "id" => $id,
      "stock_show" => $this->custom_input->put("stock_show"),
    ];
    $res = $this->model->stock($params);

    return json_response($res);
  }

  function storeActive($id){
    $params = [
      "id" => $id,
      "is_store_active" => $this->custom_input->put("is_store_active"),
    ];
    $res = $this->model->storeActive($params);
    return json_response($res);
  }

  function detail($id){
    $params = [
      "id" => $id,
      "key" => $this->custom_input->put("key"),
      "value" => $this->custom_input->put("value"),
    ];

    $res = $this->model->detail($params);

    return json_response($res);
  }

  function addCustomer($id){
    $params = [
      "id" => $id,
      "customers" => $this->custom_input->put("customer"),
    ];
    $res = $this->model->addCustomer($params);

    return json_response($res);
  }

  function entryLimit($id){
    $params = [
      "id" => $id,
      "limit" => $this->custom_input->put("limit"),
      "reset" => $this->custom_input->put("reset"),
    ];

    $res = $this->model->entryLimit($params);

    return json_response($res);
  }

  function editProperties($id){

    $params = [
      "id" => $id,
      "person_name" => $this->custom_input->put("person_name"),
      "person_surname" => $this->custom_input->put("person_surname"),
      "address" => $this->custom_input->put("address"),
      "curator_id" => $this->custom_input->put("supervisor"),
      "city_id" => $this->custom_input->put("city"),
      "depo_id" => $this->custom_input->put("warehouse"),
    ];


    $res = $this->model->editProperties($params);

    return json_response($res);
  }

  public function editIsBlocked($id){
    $params = [
      "id" => $id,
      "is_blocked" => $this->custom_input->put("is_blocked"),
    ];

    $res = $this->model->editIsBlocked($params);
    return json_response($res);
  }

}
