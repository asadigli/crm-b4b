<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function list($params)
  {
    $res = Api::call("GET",endpoint_name("currencies_list"),$params);
    return $res;
  }
}
