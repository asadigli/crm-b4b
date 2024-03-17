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

  public function comments($params)
  {
    $res = Api::call("GET",endpoint_name("products_search_comments"),$params);
    return $res;
  }

  public function addComments($params)
  {
    $res = Api::call("POST",endpoint_name("products_search_add_comments"),$params);
    return $res;
  }

  public function addPriceOffer($params)
  {
    $res = Api::call("POST",endpoint_name("products_search_add_price_offer"),$params);
    return $res;
  }

  public function newFromWarehouses($params)
  {
    $res = Api::call("GET",endpoint_name("products_new_from_warehouses"),$params);
    return $res;
  }
}
