<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function addToHistory($params){
    $res = Api::call("POST",endpoint_name("file_export_add_to_history"),$params);
    return $res;
  }
}
