<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("manager/configs/All_model","model");
  }

  function index()
  {
    $auth_user = checkAdmin(null, true);
    $auth_role = array_search($auth_user["role"], special_codes("system_users.roles"));
    $params = [
      "keyword" => $this->custom_input->get("keyword",[
        "symbols" => ["'","\"","\\","/","`"]
      ]),
      "group" => $this->input->get("group") ?: null,
      "auth_role" => $auth_role
    ];

    $res = $this->model->index($params);

    return json_response($res);
  }

  function properties(){
    $params = [
      "resources" => array_keys(special_codes("configs.resources")),
      "types" => array_keys(special_codes("configs.types")),
      "groups" => array_keys(special_codes("configs.groups")),
    ];

    return json_response(
      rest_response(
        Status_codes::HTTP_OK,
        lang("Success"),
        $params
        )
      );
  }

}
