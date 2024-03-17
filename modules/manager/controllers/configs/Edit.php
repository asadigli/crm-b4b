<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true,["main_admin"]);
    $this->load->model("configs/Edit_model", "model");
  }

  public function index($id)
  {
    $params = [
      "id" => $id,
      "key" => $this->custom_input->put("key"),
      "value" => $this->custom_input->put("value"),
    ];

    $res = $this->model->index($params);
    return json_response($res);
  }


  public function delete($id)
  {
    $params = [
      "id" => $id
    ];
    $res = $this->model->delete($params);
    return json_response($res);
  }

  function status($id){
    $params = [
      "id" => $id,
      "is_active" => $this->custom_input->put("is_active"),
    ];
    $res = $this->model->status($params);

    return json_response($res);
  }
}
