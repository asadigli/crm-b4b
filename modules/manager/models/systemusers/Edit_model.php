<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function index($params)
  {
    $res = Api::call("PUT",endpoint_name("system_users_edit",[$params["id"]]),$params);
    return $res;
  }

  public function editPassword($params)
  {
    $res = Api::call("PUT",endpoint_name("system_users_edit_password",[$params["id"]]),$params);
    return $res;
  }

  public function editGroup($params)
  {
    $res = Api::call("PUT",endpoint_name("system_users_edit_group",[$params["id"]]),$params);
    return $res;
  }

  public function delete($params)
  {
    $res = Api::call("DELETE",endpoint_name("system_users_delete",[$params["id"]]),$params);
    return $res;
  }
}
