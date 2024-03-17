<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function refresh($params)
  {
    $res = Api::call("PUT",endpoint_name("configurations_integration_caches_refresh",[$params["type"],$params["request_type"]]),$params);
    return $res;
  }
}
