<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct(){
    parent::__construct();
  }

  public function index($params){
    $res = Api::call("GET", endpoint_name("b4b_news"),$params);
    return $res;
  }

  public function types($params){
    $res = Api::call("GET", endpoint_name("b4b_news_types"),$params);
    return $res;
  }

}
