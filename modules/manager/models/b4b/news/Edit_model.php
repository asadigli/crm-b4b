<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct(){
    parent::__construct();
  }

  public function index($params){
    $res = Api::call("PUT",endpoint_name("b4b_news_edit",[$params["id"]]),$params);
    return $res;
  }

  public function delete($params){
    $res = Api::call("DELETE",endpoint_name("b4b_news_delete",[$params["id"]]),$params);
    return $res;
  }

}
