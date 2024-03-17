<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct(){
    checkHeaders();
    parent::__construct();
    $this->load->model("manager/b4b/news/All_model", "model");
  }

  public function index(){
    checkAdmin();
    $params = [];
    $res = $this->model->index($params);
    return json_response($res);
  }

  public function types(){
    checkAdmin();
    $types = $this->config->item("pop_up_types");
    if(!$types){
      return json_response(rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No pop up type found")
      ));
    }

    return json_response(rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $types
    ));
  }
}
