<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configs_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }
  function index(){
    $res = Api::call("GET",endpoint_name("configs"));
    return $res;
  }

}
