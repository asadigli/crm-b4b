<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends Ci_Model{

  function __construct() {
    parent::__construct();
  }

  function index($params){
    $res = Api::call("GET",endpoint_name("entry_list"),$params);
    return $res;
  }
  function properties($params){
    $res = Api::call("GET",endpoint_name("entry_properties"),$params);
    return $res;
  }

  function add($params){
    $res = Api::call("POST",endpoint_name("entry_add"),$params);
    return $res;
  }

  function edit($params){
    $res = Api::call("PUT",endpoint_name("entry_edit",[$params["entry_id"]]),$params);
    return $res;
  }

  function editProperties($params){
    $res = Api::call("PUT",endpoint_name("entry_edit_properties",[$params["id"]]),$params);
    return $res;
  }

  function password($params){
    $res = Api::call("PUT",endpoint_name("entry_edit_password",[$params["id"]]),$params);
    return $res;
  }
  function delete($params){
    $res = Api::call("DELETE",endpoint_name("entry_delete",[$params["id"]]),$params);
    return $res;
  }
  function stock($params){
    $res = Api::call("PUT",endpoint_name("entry_stock_update",[$params["id"]]),$params);
    return $res;
  }

  function storeActive($params){
    $res = Api::call("PUT",endpoint_name("entry_store_active",[$params["id"]]),$params);
    return $res;
  }

  function detail($params){
    $res = Api::call("PUT",endpoint_name("entry_detail_update",[$params["id"]]),$params);
    return $res;
  }

  function addCustomer($params){
    $res = Api::call("PUT",endpoint_name("entry_add_customer",[$params["id"]]),$params);
    return $res;
  }

  function entryLimit($params){
    $res = Api::call("PUT",endpoint_name("entry_limit_update",[$params["id"]]),$params);
    return $res;
  }

  function supervisors($params){
    $res = Api::call("GET",endpoint_name("supervisors_all"),$params);
    return $res;
  }

  function customers($params){
    $res = Api::call("GET",endpoint_name("customers_list"),$params);
    return $res;
  }

  # this edit methods should transfer to edit model
  public function editIsBlocked($params)
  {
    $res = Api::call("PUT",endpoint_name("entry_edit_is_blocked",[$params["id"]]),$params);
    return $res;
  }
}
