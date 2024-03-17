<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function statusConfirm($params)
  {
    $res = Api::call("PUT",endpoint_name("orders_status_confirm",[$params["id"]]),$params);
    return $res;
  }

  public function statusFinish($params)
  {
    $res = Api::call("PUT",endpoint_name("orders_status_finish",[$params["id"]]),$params);
    return $res;
  }

  public function statusCancel($params)
  {
    $res = Api::call("PUT",endpoint_name("orders_status_cancel",[$params["id"]]),$params);
    return $res;
  }

  public function editStatus($params)
  {
    $res = Api::call("PUT",endpoint_name("orders_status_edit",[$params["id"]]),$params);
    return $res;
  }

  public function transferOrder($params)
  {
    $res = Api::call("PUT",endpoint_name("orders_transfer_order",[$params["id"]]),$params);
    return $res;
  }
}
