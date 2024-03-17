<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model{

  function __construct(){
    parent::__construct();
  }

  function create($params){
    $data = Base::callApi("POST","admin/user/add",$params);
    return $data;
  }

}
