<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filters extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true);
  }

  public function list()
  {
    $params = [
    ];

    $filters_cache_key = "b4b_cart_filter.cache";
    $filters = $this->cache->get($filters_cache_key);
    if(!$filters) {
      $this->load->model("cart/properties/Filters_model", "model");
      $filters = $this->model->list($params);
      $this->cache->save($filters_cache_key, $filters, 3600 * 24);
    }
    return json_response($filters);
  }
}
