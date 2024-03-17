<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificate_model extends CI_Model{

  function __construct(){
    parent::__construct();
  }

  function getAll($params){
    $data = Base::callAPI("GET","certificate/list",$params);
    return $data;
  }

  function addNew($params){
    $data = Base::callAPI("POST","certificate/add",$params);
    return $data;
  }


  function delete($params){
    $data = Base::callAPI("DELETE","certificate/delete",$params);
    return $data;
  }

}
