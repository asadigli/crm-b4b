<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function editMaxOrderLimit($params)
  {
    $res = Api::call("PUT",endpoint_name("customers_edit_max_order_limit",[$params["id"]]),$params);
    return $res;
  }

  public function editMaxAllowedOrderLimit($params)
  {
    $res = Api::call("PUT",endpoint_name("customers_edit_max_allowed_order_limit",[$params["id"]]),$params);
    return $res;
  }

  public function editHasOrderLimit($params)
  {
    $res = Api::call("PUT",endpoint_name("customers_edit_has_order_limit",[$params["id"]]),$params);
    return $res;
  }

  public function editIsBlocked($params)
  {
    $res = Api::call("PUT",endpoint_name("customers_edit_is_blocked",[$params["id"]]),$params);
    return $res;
  }
}
