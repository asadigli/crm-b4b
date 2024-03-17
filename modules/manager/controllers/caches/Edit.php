<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true,["main_admin"]);
    $this->load->model("caches/Edit_model", "model");
  }

  public function refresh($type)
  {
    $params = [
      "warehouse_id" => $this->custom_input->put("warehouse_id"),
      "type" => $type,
      "request_type" => REQUEST_TYPE_MANUAL
    ];

    $res = $this->model->refresh($params);
    return json_response($res);
  }

}
