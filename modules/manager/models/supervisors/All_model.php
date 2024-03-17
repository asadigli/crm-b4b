<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct(){
    parent::__construct();
  }

  function index($params){
    $res = Api::call("GET",endpoint_name("supervisors_all"),$params);
    return $res;
  }

  function add($params){
    $res = Api::call("POST",endpoint_name("supervisors_add"),$params);
    return $res;
  }

  function edit($params){
    $res = Api::call("PUT",endpoint_name("supervisors_edit"),$params);
    return $res;
  }

}
