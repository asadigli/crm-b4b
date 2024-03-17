<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_model extends CI_Model{

  public function __construct() {
    parent::__construct();
  }

  public function daily_list($params){
    $res = Api::call("GET",endpoint_name("daily_sales"),$params);
    return $res;
  }

  public function index($params){
    $res = Api::call("GET",endpoint_name("sale_invoices"),$params);
    return $res;
  }

  public function details($params){
    $res = Api::call("GET",endpoint_name("invoices_sales_details",[$params["code"]]),$params);
    return $res;
  }

}
