<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices_model extends CI_Model{

  public function __construct(){
    parent::__construct();
  }

  function index($params){
    $data = Api::call("GET",endpoint_name("invoices"),$params);
    return $data;
  }

  function liveInDetails($params){
    $data = Api::call("GET",endpoint_name("invoices_in_details"),$params);
    return $data;
  }

}
