<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function list($params){
    $res = Api::call("GET",endpoint_name("news_list"),$params);

    return $res;
  }

  function details($params){
    $res = Api::call("GET",endpoint_name("news_details",[$params["id"]]),$params);

    return $res;
  }

}
