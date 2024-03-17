<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function index($params)
  {
    $res = Api::call("GET",endpoint_name("orders"),$params);
    return $res;
  }

  public function statuses($params)
  {
    $res = Api::call("GET",endpoint_name("orders_statuses"),$params);
    return $res;
  }

  public function order_groups($params) {
    $res = Api::call("GET",endpoint_name("order_group_list"),$params);
    return $res;
  }
}
