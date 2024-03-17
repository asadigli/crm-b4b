<?php
defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('memory_limit','1200006M');
ini_set('max_execution_time', 900); //300 seconds = 5 minutes

class Product_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }


  function getProductsAjax($params){
    $products = Base::callAPI('GET','products/latest',$params);
    if (!isset($products['data'])) {
      return [];
    }
    return $products['data'];
  }

  function getSimilars($params){
    $data = Base::callAPI("GET","product/similar/list",$params);
    if (!isset($data["data"]) || $data["code"] !== 200) {
      return [];
    }


    $images_list = Services::getProductImages($data["data"]["codes"]);


    $new_list = [];
    foreach ($data["data"]["products"] as $key => $product) {
      $new_list[] = [
        "id" => $product["id"],
        "slug" => $product["slug"],
        "price" => $product["price"],
        "name" => $product["product_name"],
        "brand" => $product["brand"],
        "images" => $product["images"] ? $product["images"] : (isset($images_list[trim($product["brand_code"])]) ? $images_list[trim($product["brand_code"])] : null),
        "OEM" => $product["OEM"]
      ];
    }
    return $new_list;
  }

  function categoryByGroup($params){
    $categories = Base::callAPI('GET','admin/product/category/list',$params);
    return $categories;
  }

  function categoryByGroup2($params){
    $data = Base::callAPI("GET",'product/group/all-list',$params);
    return $data;
  }

  /*
  * Home page product list
  */
  function homeProducts($params){
    $home_products = Services::getHomeProducts($params);
    return $home_products;
  }


  function updateHomeProduct($params){
    $res = Base::callAPI('POST','product/home-list/update',$params);
    return $res;
  }

  function productView($params){
    $res = Base::callAPI('GET','product/details',$params);
    return $res;
  }

  function allCategories($params){
    $res = Base::callAPI('GET','product/group/all-list',$params);
    return $res;
  }


  function groupProducts($params){
    $res = Base::callAPI('GET','product/group-list',$params);
    return $res;
  }


  function changeName($params){
    $data = Base::callApi("PUT","product/name/update",$params);
    return $data;
  }

  function changeDescription($params){
    $data = Base::callApi("PUT","product/description/update",$params);
    return $data;
  }

  function updateDetails($params){
    $data = Base::callApi("PUT","product/details/update",$params);
    return $data;
  }



  // DATA CISCT SERVICES
  function getCrossReference($params){
    $res = Base::callAPI2('GET',$this->config->item('data_cisct_url').'services/cross/crossreferences',$params);
    $new_list = [];
    if($res["code"] !== 200){return ["code" => $res["code"]];}
    foreach ($res["data"] as $key => $item) {
      $new_list[] = [
        "brand" => $item["number"],
        "brand_code" => $item["brand"],
        "car_brand" => $item["oembrand"],
        "OEM" => $item["oemnumber"],
        "group" => $item["group"],
        "product" => $item["product"],
      ];
    }
    return ["code" => 200,"data" => $new_list];
  }

  function getPartDetails($params){
    $res = Base::callAPI2("GET",$this->config->item("data_cisct_url")."services/parts/partscri",$params);
    return $res;
  }

  function similarOEMs($params){
    $res = Base::callAPI2("GET",$this->config->item("data_cisct_url")."services/cross/crossreferencessearch",$params);
    return $res;
  }

  function compatibleCars($params){
    $res = Base::callAPI2("GET",$this->config->item("data_cisct_url")."services/parts/other_cars",$params);
    return $res;
  }

  function regions(){
    $regions = Services::getRegions();
    return isset($regions["data"]) ? $regions["data"] : $regions;
  }

}
