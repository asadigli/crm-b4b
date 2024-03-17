<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchases_model extends CI_Model{

  public function __construct() {
    parent::__construct();
  }

  public function index($params){
    $res = Api::call("GET",endpoint_name("purchase_invoices"),$params);
    return $res;
  }

  public function details($params){
    $res = Api::call("GET",endpoint_name("invoices_purchases_details",[$params["code"]]),$params);
    return $res;
  }

}
