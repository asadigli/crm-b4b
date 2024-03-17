<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Searchlogs_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }


  public function index($params){
    $res = Api::call("GET",endpoint_name("search_logs"),$params);
    return $res;
  }
  public function onlyCustomers($params){
    $res = Api::call("GET",endpoint_name("search_logs_only_customers"),$params);
    return $res;
  }

}
