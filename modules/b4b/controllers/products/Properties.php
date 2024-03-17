<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Properties extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true);
  }

  public function brands()
  {
    $params = [];

    $cache_key = "b4b_product_brands_datas.cache";
    $cached_data = $this->cache->get($cache_key);
    if (!$cached_data) {
      $this->load->model("products/Properties_model", "model");
      $res = $this->model->brands($params);
      if (isset($res["code"]) && $res["code"] === Status_codes::HTTP_OK) {
        $this->cache->save($cache_key,$res,360 * 24);
      }
    } else {
      $res = $cached_data;
    }


    return json_response($res);
  }

  public function carBrands()
  {
    $params = [];

    $cache_key = "b4b_product_carbrands_datas.cache";
    $cached_data = $this->cache->get($cache_key);
    if (!$cached_data) {
      $this->load->model("products/Properties_model", "model");
      $res = $this->model->carBrands($params);
      if (isset($res["code"]) && $res["code"] === Status_codes::HTTP_OK) {
        $this->cache->save($cache_key,$res,360 * 24);
      }
    } else {
      $res = $cached_data;
    }
    return json_response($res);
  }
}
