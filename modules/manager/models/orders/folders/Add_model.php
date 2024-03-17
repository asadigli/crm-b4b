<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function index($params)
  {
    $res = Api::call("POST",endpoint_name("orders_folders_add"),$params);
    return $res;
  }

  public function addOrder($params)
  {
    $res = Api::call("POST",endpoint_name("orders_folders_add_order",[$params["id"]]),$params);
    return $res;
  }
}
