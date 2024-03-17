<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Online_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function index($params){
    $res = Api::call("POST",endpoint_name("onlines"),$params);
    return $res;
  }

}
