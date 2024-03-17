<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function index()
  {
    $auth_user = checkAdmin(null, true);
    $params = [
      "end_date" => trim(str_replace("'", "\"", $this->input->get("end_date"))),
      "start_date" => trim(str_replace("'", "\"", $this->input->get("start_date"))),
      "keyword" =>  strip_tags(trim(str_replace(["'", "\""], "", $this->input->get("keyword")))),
      "brand_id" => (int)$this->input->get("brand_id"),
      "is_dev" => $auth_user["role"] === ROLE_DEVELOPER,
      "auth_user" => $auth_user,
    ];

    validateArray($params, ["start_date", "end_date"]);

    $this->load->model("manager/products/imports/All_model", "model");
    $res = $this->model->index($params);

    return json_response($res);
  }

  public function delete($id){
    checkAdmin();
    $params = [
      "id" => $id,
      "now" => now(),
    ];
    validateArray($params,["id"]);
    $this->load->model("manager/products/imports/All_model", "model");
    $res = $this->model->delete($params);
    return json_response($res);
  }
}
