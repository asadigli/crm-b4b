<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Details_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function index($params)
  {
    $res = Api::call("GET",endpoint_name("orders_details",[$params["id"]]),$params);
    return $res;
  }
}
