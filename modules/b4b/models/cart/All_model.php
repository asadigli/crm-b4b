<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function index($params)
  {
    $res = Api::call("GET",endpoint_name("cart"),$params);
    return $res;
  }

  public function cartCount($params)
  {
    $res = Api::call("GET",endpoint_name("cart_count"),$params);
    return $res;
  }
}