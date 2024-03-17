<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_model extends CI_Model{
  public function __construct() {
    parent::__construct();
  }

  public function index($params){
    $res = Api::call("GET",endpoint_name("customers_account",[$params["customer_id"]]),$params);
    return $res;
  }

  public function details($params){
    $res = Api::call("GET",endpoint_name("customers_account_details",[$params["customer_id"],$params["code"]]),$params);
    return $res;
  }

}
