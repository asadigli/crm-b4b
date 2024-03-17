<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function index($params)
  {
    $res = Api::call("GET",endpoint_name("products_imports"),$params);
    return $res;
  }

  public function delete($params){
    $res = Api::call("DELETE",endpoint_name("products_imports_delete", [$params["id"]]),$params);
    return $res;
  }
}
