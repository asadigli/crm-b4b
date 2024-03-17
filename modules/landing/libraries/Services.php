<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Services {

  public static function callApi($method,$path = null,$data = null,$url = null){
    $CI = get_instance();
    $lang = $CI->config->item("current_language");

    $base_api_url = $CI->config->item("service_url");
    $path = $url ? $url : $base_api_url.$path;
    $path = !in_array($method,['PUT','POST']) ? sprintf("%s?%s", $path, http_build_query($data)) : $path;
    $data = $method === "POST" ? http_build_query($data) : json_encode($data);
    $header_list = $CI->config->item("service_credentials") + ["version:".$CI->config->item("system_version_int"),"Content-Type: application/json","lang:$lang"];
    $curl_body = [
      CURLOPT_URL => $path,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      // CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_POST => 1,
      CURLOPT_POSTFIELDS => $data,
      CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
      CURLOPT_HTTPHEADER => $header_list,
    ];

    // return $curl_body;

    $curl = curl_init();
    curl_setopt_array($curl, $curl_body);
    $res = curl_exec($curl);
    if(!$res){
      die("Connection Failure");
    }
    curl_close($curl);
    return json_decode($res, true);
  }

  public static function callApiDataCisct($method,$url,$data = null,$lang = NULL){
    $CI = get_instance();
    // self::getlang(self::langs(),$CI->config->item('language'));

    $lang = isset($_SESSION["language"]) ? $_SESSION["language"] : "az";
    $data_string = json_encode($data);
    $ch = curl_init($CI->config->item('data_cisct_url').$url);
    $body = array_merge($CI->config->item("data_cisct_credentials"),[
                                      'Content-Type:application/json',
                                      'Lang:'. $lang,
                                      'Content-Length:'. strlen($data_string)
                                    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $body);
    $res = curl_exec($ch);
    return json_decode($res,true);
  }

  public static function getProductImages($codes = []){
    $CI = get_instance();
    $images_list = [];

    if ($CI->config->item("external-product-images")) {
      $images_data = [];
      if ($codes) {
        $images_data = self::callApiDataCisct("GET",'services/parts/images',["codes" => $codes]);
      }

      if (isset($images_data["data"])) {
        foreach ($images_data["data"] as $img) {
          $images_list = $images_list + [$img['code'] => $img['image']];
        }
      }
    }
    return $images_list;
  }

  public static function getHomeProducts($params = [],$clear = false){
    $CI = get_instance();
    $cache_key = "home_product_list.cache";
    $home_products = $CI->cache->get($cache_key);
    $cache_home_products = $CI->config->item("cache-home-products");
    if (!$home_products || $clear || $cache_home_products){

      $data = self::callApi("GET","products/home-page-list",$params);

      if (!isset($data["data"])) {
        $CI->cache->save($cache_key, $new_list, 0);
        return [];
      }


      $images_list = self::getProductImages($data["data"]["codes"]);


      $home_products = [];
      foreach ($data["data"]["products"] as $key => $product) {
        $home_products[] = [
          "id" => $product["id"],
          "slug" => $product["slug"],
          "price" => $product["price"],
          "prod_name" => $product["prod_name"],
          "brand" => $product["brand"],
          "images" => $product["images"] ? $product["images"] : (isset($images_list[trim($product["code"])]) ? $images_list[trim($product["code"])] : null),
          "OEM" => $product["OEM"]
        ];
      }

      $cache_home_products ? $CI->cache->save($cache_key, $home_products, 24 * 3600) : "";
    }

    return $home_products;
  }

  public static function getHomeBrands($params = [],$clear = false){
    $CI = get_instance();
    $cache_key = "home_brand_list.cache";
    $home_brands = $CI->cache->get($cache_key);
    $cache_home_brands = $CI->config->item("cache-home-brands");
    if (!$home_brands || $clear || $cache_home_brands){

      $home_brands = self::callAPI("GET","brand/list",$params);

      $cache_home_brands ? $CI->cache->save($cache_key, $home_brands, 24 * 3600) : "";
    }
    return $home_brands;
  }

  public static function getRegions(){
    $CI = get_instance();
    $cache_key = "region_list.cache";
    $region_list = $CI->cache->get($cache_key);
    if (!$region_list){
      $region_list = self::callApiDataCisct("GET","services/country/citylist");

      $CI->cache->save($cache_key, $region_list, 7 * 24 * 3600);
    }
    return $region_list;
  }
}
