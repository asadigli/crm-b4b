<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Properties_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function brands($params)
  {
    $res = Api::call("GET",endpoint_name("product_brands"),$params);
    return $res;
  }

  public function carBrands($params)
  {
    $res = Api::call("GET",endpoint_name("product_car_brands"),$params);
    return $res;
  }

  public function banners($params)
  {
    $res = Api::call("GET",endpoint_name("product_banners"),$params);
    return $res;
  }

  public function brand_sliders($params)
  {
    $res = Api::call("GET",endpoint_name("brand_sliders"),$params);
    return $res;
  }
}
