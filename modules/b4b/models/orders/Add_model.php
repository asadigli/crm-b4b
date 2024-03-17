<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function isApprove($params)
  {
    $res = Api::call("POST",endpoint_name("orders_is_approve"),$params);
    return $res;
  }
}
