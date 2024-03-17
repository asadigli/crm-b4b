<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function index($params)
  {
    $res = Api::call("GET",endpoint_name("products_search"),$params);
    return $res;
  }

  public function tecdocCrosses($params)
  {
    $res = Api::call("GET",endpoint_name("products_search_tecdoc_crosses"),$params);
    return $res;
  }

  public function applyDiscount($params){
    $res = Api::call("PUT", endpoint_name("products_apply_discount"), $params);
    return $res;
  }

  public function hidePrice($params){
    $res = Api::call("PUT", endpoint_name("products_hide_price"), $params);
    return $res;
  }

  public function comments($params)
  {
    $res = Api::call("GET",endpoint_name("products_search_comments_manager"),$params);
    return $res;
  }

  public function priceOffers($params)
  {
    $res = Api::call("GET",endpoint_name("products_search_offered_price"),$params);
    return $res;
  }
}
