<?php
defined('BASEPATH') OR exit('No direct script access allowed');
defined("HOUR")  OR define("HOUR", 3600);

class Services extends Api {

  private static $cache_times = [
    "global" => 3 * HOUR, // mostly used
    "currencies" => 1 * HOUR, // 24 hours,
  ];

  static function configs($params = [],$cache_clear = false){
    $CI = get_instance();
    $key = md5($params["group"])."_configs.cache";
    $res = $CI->cache->get($key);
    if (!$res || $cache_clear){

      $res = self::call("GET",endpoint_name("configs"));

      if (isset($res["data"][$params["group"]]) && isset($res["code"]) && $res["code"] === 200) {
        $res = $res["data"][$params["group"]];

        $CI->cache->save($key, $res, self::$cache_times["global"]);
      }
    }
    return isset($res["data"]) ? (isset($params["group"]) ? [] : $res["data"]) : $res;
  }

}
