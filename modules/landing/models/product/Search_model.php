<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  // function find()
  // {
  //   $products = [];
  //   return $products;
  // }

  function getBrands($params){
    $data = Base::callAPI('GET','product/brands/list',$params);
    return $data;
  }

  function getCarBrands($params){
    $data = Base::callAPI('GET','product/car-brands/list',$params);
    return $data;
  }
  //
  // function getSuggestions($keyword = NULL){
  //   $list = ['keyword' => $keyword];
  //   $data = Base::callAPI('GET','product/suggest-name',$list);
  //   return $data;
  // }

  function searchAjax($params){
    $updated_params = [
      "sort_by" => $params["sort_by"],
      "keyword" => $params["keyword"],
      "brands" => $params["brands"],
      "carbrands" => $params["carbrands"],

      "custom_groups" => $params["custom_groups"],
      "custom_brands" => $params["custom_brands"],
      "custom_categories" => $params["custom_categories"],
      "custom_second_categories" => $params["custom_second_categories"],


      "limit" => $params["limit"] ?: 12,
      "offset" => $params["page"] && $params["page"] > 1 ? ($params["page"] - 1)*12 : 0,
    ];
    // return $updated_params;
    $data = Base::callAPI('GET','products/latest',$updated_params);

    if (!isset($data['data'])) {
      return [];
    }


    $images_list = Services::getProductImages($data["data"]["codes"]);


    $products_new = [];
    foreach ($data["data"]["products"] as $prod) {
      $products_new[] = [
        "id" => $prod["id"],
        "slug" => $prod["slug"],
        "price" => $prod["price"],
        "product_name" => $prod["product_name"],
        "brand" => $prod["brand"],
        "short_name" => $prod["short_name"],
        "OEM" => $prod["OEM"],
        "images" => $prod["images"] ? $prod["images"] : (isset($images_list[trim($prod["brand_code"])]) ? $images_list[trim($prod["brand_code"])] : null),
      ];
    }

    return ["products" => $products_new,"count" => $data["data"]["count"]];
  }

  function listProducts($key = null){
    $list = [
      'unique_key' => $key
    ];
    $prods = Base::callApi('GET','/panel-search/list/products',$list);
    return $prods;
  }

  // function panelSearchAjax($model = null,$marka = null,$year = null){
  //   $list = [
  //     'model' => $model,
  //     'marka' => $marka,
  //     'year' => $year,
  //   ];
  //   $ct = Base::callApi('GET','/panel-search/categories',$list);
  //   return isset($ct['data']) ? $ct['data'] : [];
  // }

}
