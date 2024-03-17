<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_model extends CI_Model{

  public function __construct() {
    parent::__construct();
  }

  function index($params){
    $res = Api::call("GET",endpoint_name("customer_account",[$params["remote_id"]]),$params);
    return $res;
  }

  function details($params){
    $res = Api::call("GET",endpoint_name("customer_account_details",[$params["code"]]),$params);
    return $res;
  }

  function return($params){
    $res = Api::call("POST",endpoint_name("customer_account_return"),$params);
    return $res;
  }

}
