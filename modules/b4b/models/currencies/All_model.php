<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }
  function index($params){
    $res = Api::call("GET",endpoint_name("cached_currencies"),$params);
    return $res;
  }

}
