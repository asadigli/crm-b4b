<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function index($params)
  {
    $res = Api::call("GET",endpoint_name("configs"),$params);
    return $res;
  }

  public function roles($params)
  {
    $res = Api::call("GET",endpoint_name("configs_roles"),$params);
    return $res;
  }

  public function properties($params)
  {
    $res = Api::call("GET",endpoint_name("configs_properties"),$params);
    return $res;
  }
}
