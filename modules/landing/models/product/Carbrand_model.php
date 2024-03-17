<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Carbrand_model extends CI_Model{

  function __construct(){
    parent::__construct();
  }


  function getCarBrands(){
    // $res = cacheInJson('car_brand_list');
    // if (!$res) {
    $res = Base::callAPI2('GET',$this->config->item('data_cisct_url').'services/car_catalog/brands',["slug" => "yes"]);
    // }
    // echo json_encode($res);die;
    return isset($res['data']) ? $res['data'] : [];
  }

  function getBrandYears($brand = null){
    $list = ["brand_id" => $brand,"slug" => "yes"];
    $res = Base::callAPI2('GET',$this->config->item('data_cisct_url').'services/car_catalog/year',$list);
    return isset($res['data']) ? $res['data'] : [];
  }

  function getBrandModels($brand = null,$year = null){
    $list = [
      "brand_id" => $brand,
      "year" => $year,
      "slug" => "yes"
    ];
    $res = Base::callAPI2('GET',$this->config->item('data_cisct_url').'services/car_catalog/model',$list);
    return isset($res['data']) ? $res['data'] : [];
  }

  function getBrandEngine($model = null,$year = null){
    $list = [
      "model_id" => $model,
      "year" => $year,
      "slug" => "yes"
    ];
    $res = Base::callAPI2('GET',$this->config->item('data_cisct_url').'services/car_catalog/engine',$list);
    return isset($res['data']) ? $res['data'] : [];
  }

}
