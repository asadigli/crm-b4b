<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct(){
    parent::__construct();
  }

  public function index($params){
    $res = Api::call("PUT", endpoint_name("sliders_edit"), $params);
    return $res;
  }

  public function delete($id){
    $res = Api::call("DELETE",endpoint_name("sliders_delete",[$id]));
    return $res;
  }

}
