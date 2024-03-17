<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  function __construct() {
    parent::__construct();
    $this->load->model("manager/users/All_model","model");
  }

  function index()
  {
    $params = [
      "keyword" => $this->custom_input->get("keyword",[
        "symbols" => ["'","\"","\\","/","`"]
      ]),
      "is_developer" => $this->input->get("is_developer")
    ];

    $res = $this->model->index($params);

    return json_response($res);
  }

  function groups()
  {
    $params = [
    ];

    $res = $this->model->groups($params);

    return json_response($res);
  }

  function orderGroups()
  {
    $params = [
    ];

    $res = $this->model->orderGroups($params);

    return json_response($res);
  }

  function roles(){
    $roles = array_keys(special_codes("system_users.roles"));

    return json_response(
      rest_response(
        Status_codes::HTTP_OK,
        lang("Success"),
        $roles
        )
      );
  }





}
