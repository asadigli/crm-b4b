<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function confirmApprove($params)
  {
    $res = Api::call("PUT",endpoint_name("orders_confirm_approve"),$params);
    return $res;
  }
}
