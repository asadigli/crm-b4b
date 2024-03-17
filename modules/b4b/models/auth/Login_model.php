<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  function index($params){
    $res = Api::call("POST",endpoint_name("b4b_login"),$params);
    return $res;
  }

}
