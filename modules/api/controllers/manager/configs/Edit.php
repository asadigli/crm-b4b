<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("manager/configs/Edit_model","model");
  }

  function index($id){
    $params = [
      "id" => $id,
      "key" => $this->custom_input->put("key"),
      "value" => $this->custom_input->put("value"),
    ];

    validateArray($params,["id","value","key"]);

    if (in_array($params["key"]."s",array_keys(special_codes("configs"))) && in_array($params["value"],array_keys(special_codes("configs.{$params["key"]}s")))) {
        $params["value"] = special_codes("configs.{$params["key"]}s." . $params["value"]);
    }

    $res = $this->model->index($params);

    return json_response($res);
  }

  function delete($id){
    $params = [
      "id" => $id
    ];
    validateArray($params,["id"]);
    checkAdmin();
    $res = $this->model->delete($params);

    return json_response($res);
  }

  function status($id){
    $params = [
      "id" => $id,
      "is_active" => $this->custom_input->put("is_active") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE
    ];
    validateArray($params,["id"]);


    $res = $this->model->status($params);

    return json_response($res);
  }



}
