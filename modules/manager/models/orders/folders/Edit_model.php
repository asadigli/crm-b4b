<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function editName($params)
  {
    $res = Api::call("PUT",endpoint_name("orders_folders_edit_name",[$params["id"]]),$params);
    return $res;
  }

  public function editDescription($params)
  {
    $res = Api::call("PUT",endpoint_name("orders_folders_edit_description",[$params["id"]]),$params);
    return $res;
  }

  public function editIsActive($params)
  {
    $res = Api::call("PUT",endpoint_name("orders_folders_edit_is_active",[$params["id"]]),$params);
    return $res;
  }

  public function delete($params)
  {
    $res = Api::call("DELETE",endpoint_name("orders_folders_delete",[$params["id"]]),$params);
    return $res;
  }

  public function removeOrder($params)
  {
    $res = Api::call("DELETE",endpoint_name("orders_folders_remove_order",[$params["id"]]),$params);
    return $res;
  }
}
