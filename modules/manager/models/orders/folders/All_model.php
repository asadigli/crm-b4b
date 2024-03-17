<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function index($params)
  {
    $res = Api::call("GET",endpoint_name("orders_folders"),$params);
    return $res;
  }

  public function list($params)
  {
    $res = Api::call("GET",endpoint_name("orders_folders_list"),$params);
    return $res;
  }

  public function ordersList($params)
  {
    $res = Api::call("GET",endpoint_name("orders_folders_orders_list",[$params["id"]]),$params);
    return $res;
  }
}
