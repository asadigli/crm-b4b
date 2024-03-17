<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News_model extends CI_Model{

  function __construct(){
    parent::__construct();
  }

  function getAll($params){
    $res = Base::callAPI("GET","news/list",$params);
    return $res;
  }

  function details($params){
    $res = Base::callAPI("GET","news/details",$params);
    return $res;
  }

  function addNew($params){
    $res = Base::callAPI("POST","news/add",$params);
    return $res;
  }

  function update($params){
    $res = Base::callApi("PUT","news/update",$params);
    return $res;
  }

  function changeStatus($params){
    $res = Base::callApi("PUT","news/".$params["news"]."/status/update",$params);
    return $res;
  }

  function delete($params){
    $res = Base::callApi("DELETE","news/delete",$params);
    return $res;
  }

}
