<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct() {
    parent::__construct();
  }

  function refresh($params){
    $res = Api::callExt("PUT",endpoint_name("integration_cache_refresh",[$params["type"]]),$params);
    return $res;
  }

  function history($params){
    $res = Api::call("GET",endpoint_name("caches_history"),$params);
    return $res;
  }

}
