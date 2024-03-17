<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function index($params)
  {
    $res = Api::call("GET",endpoint_name("system_users"),$params);
    return $res;
  }

  public function groups($params)
  {
    $res = Api::call("GET",endpoint_name("system_users_groups"),$params);
    return $res;
  }

  public function roles($params)
  {
    $res = Api::call("GET",endpoint_name("system_users_roles"),$params);
    return $res;
  }

  public function orderGroups($params)
  {
    $res = Api::call("GET",endpoint_name("system_users_order_groups"),$params);
    return $res;
  }

  public function searchLogs($params){
    $res = Api::call("GET",endpoint_name("search_logs"),$params);
    return $res;
  }
  public function searchLogsOnlyCustomers($params){
    $res = Api::call("GET",endpoint_name("search_logs_only_customers"),$params);
    return $res;
  }

}
