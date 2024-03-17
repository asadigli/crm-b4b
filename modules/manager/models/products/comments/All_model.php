<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function index($params)
  {
    $res = Api::call("GET",endpoint_name("products_comments"),$params);
    return $res;
  }

  public function entriesList($params)
  {
    $res = Api::call("GET",endpoint_name("entries_list"),$params);
    return $res;
  }
}
