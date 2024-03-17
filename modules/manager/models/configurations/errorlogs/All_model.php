<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function index($params)
  {
    $res = Api::call("GET",endpoint_name("static_error_logs"),$params);
    return $res;
  }

  public function logsPaths($params)
  {
    $res = Api::call("GET",endpoint_name("static_error_logs_paths"),$params);
    return $res;
  }
}
