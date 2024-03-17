<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct(){
    parent::__construct();
  }

  public function list($params)
  {
    $res = Api::call("GET",endpoint_name("customers_list"),$params);
    return $res;
  }

  public function index($params)
  {
    $res = Api::call("GET",endpoint_name("customers"),$params);
    return $res;
  }

  public function cityList($params)
  {
    $res = Api::call("GET",endpoint_name("city_list"),$params);
    return $res;
  }
}
