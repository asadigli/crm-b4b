<?php
defined('BASEPATH') OR exit('No direct script access allowed');
defined('HOUR')  OR define('HOUR', 3600);
include_once APPPATH . "../modules/api/libraries/Api.php";

class Dtcisct extends Api {

  private static $prefix = "data.cisct_";


  public static function getCodesByCode($param_code, $type = "string"){
    $code_string = "";
    $CI = get_instance();
    $key = "cross_" . md5(json_encode($param_code)) . ".cache";
    if (!$code_string = $CI->cache->get($key)) {
      $codes_res = self::callCs("services/cross/crossreferencessearch","GET",["code" => $param_code]);
      if (isset($codes_res["code"]) && $codes_res["code"] === 200) {
        $codes = isset($codes_res['data']) ? $codes_res['data'] : [];
        $codes = array_unique($codes);
        $code_string = implode(",",$codes);
        $CI->cache->save($key, $code_string, isset($codes_res["code"]) && $codes_res["code"] !== 200 ? 0 : 3600 * 24);
      }
    }
    if ($type === "string") {
      return "'".implode("','",explode(",",$code_string))."'";
    }
    return array_filter(explode(",",$code_string));
  }

  public static function getRonaxProducts($param_code, $type = "string"){
    $CI = get_instance();
    // $key = "ronax_products_" . md5(json_encode($param_code)) . ".cache";
    // $products = $CI->cache->get($key);
    // if (!$products) {
      $products_res = self::callCs("services/ronax/parts","GET",["code" => $param_code, "connection_timeout" => 2], $CI->config->item("data_new_cisct_url"));
      if (isset($products_res["code"]) && $products_res["code"] === 200) {
        $products = isset($products_res['data']) ? $products_res['data'] : [];
        // $products = array_unique($products);
        // $CI->cache->save($key, $products, isset($products_res["code"]) && $products_res["code"] !== 200 ? 0 : 3600 * 24);
      } else {
        $products = [];
      }
    // }
    if ($type === "string") {
      return "'".implode("','",explode(",",$products))."'";
    }
    return $products;
  }

  public static function getStoreProducts($param_code, $type = "string"){
    $CI = get_instance();
    $key = "store_products_" . md5(json_encode($param_code)) . ".cache";
    $products = $CI->cache->get($key);
    if (!$products) {
      $products_res = self::callCs("services/store_price/search_store","GET",["code" => $param_code], $CI->config->item("data_cisct_url"));
      if (isset($products_res["code"]) && $products_res["code"] === 200) {
        $products = isset($products_res['data']) ? $products_res['data'] : [];
        // $products = array_unique($products);
        $CI->cache->save($key, $products, isset($products_res["code"]) && $products_res["code"] !== 200 ? 0 : 3600 * 24);
      } else {
        $products = [];
      }
    }
    if ($type === "string") {
      return "'".implode("','",explode(",",$products))."'";
    }
    return $products;
  }

  public static function getCrossReference($param_code, $type = "string"){
    $code_string = "";
    $codes = self::callCs("services/cross/crossreferences","GET",["code" => $param_code]);
    if (isset($codes["code"]) && $codes["code"] === 200) {
      $codes = isset($codes['data']) ? $codes['data'] : [];
      return $codes;
    }
    return [];
  }


}
