<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function editPrice($params)
  {
    $res = Api::call("PUT",endpoint_name("products_edit_price",[$params["id"]]),$params);
    return $res;
  }

  public function discountPrice($params){
    $res = Api::call("PUT", endpoint_name("products_discount_price",[$params["id"]]),$params);
    return $res;
  }

  public function hidePrice($params){
    $res = Api::call("PUT", endpoint_name("products_b4b_hide_price",[$params["id"]]),$params);
    return $res;
  }

  public function isNewFromWarehouse($params){
    $res = Api::call("PUT", endpoint_name("products_edit_is_new_from_warehouse",[$params["id"]]),$params);
    return $res;
  }
}
