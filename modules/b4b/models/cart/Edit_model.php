<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct() {
    parent::__construct();
  }

  public function editQuantity($params) {
    $res = Api::call("PUT",endpoint_name("cart_edit_quantity",[$params["id"]]),$params);
    return $res;
  }

  public function editNote($params){
    $res = Api::call("PUT",endpoint_name("cart_edit_note",[$params["id"]]),$params);
    return $res;
  }

  public function delete($params) {
    $res = Api::call("DELETE",endpoint_name("cart_delete",[$params["id"]]),$params);
    return $res;
  }

  public function deleteItems($params) {
    $res = Api::call("DELETE",endpoint_name("cart_delete_items"),$params);
    return $res;
  }
}
