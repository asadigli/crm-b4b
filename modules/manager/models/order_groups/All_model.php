<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function index($params){
    $res = Api::call("GET",endpoint_name("order_group_list"),$params);
    return $res;
  }

  function warehouses($params){
    $res = Api::call("GET",endpoint_name("order_group_warehouses"),$params);
    return $res;
  }
  function add($params){
    $res = Api::call("POST",endpoint_name("order_group_add"),$params);
    return $res;
  }

  function delete($params){
    $res = Api::call("DELETE",endpoint_name("order_group_delete",[$params["id"]]),$params);
    return $res;
  }

  function detail($params){
    $res = Api::call("PUT",endpoint_name("order_group_detail",[$params["id"]]),$params);
    return $res;
  }


}
