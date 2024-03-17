<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('path_local')) {
  function path_local($path = null){
    $CI = get_instance();
    $lang = $CI->config->item("current_language") ?: "az";
    $lang_path = $lang !== "az" ? $lang . "/" : "";
    return base_url() . $lang_path . ($path ?: "");
  }
}


if (!function_exists("cache_save")) {
  function cache_save($key = null,$data = null, $time = 3600){
    $CI = get_instance();
    $key = (isset($_SESSION["organization_id"]) ? "org-" . $_SESSION["organization_id"] . "." : "") . $key . ".cache";
    return $CI->cache->save($key, $data, $time);
  }
}


if (!function_exists("cache_get")) {
  function cache_get($key = null){
    $CI = get_instance();
    $key = (isset($_SESSION["organization_id"]) ? "org-" . $_SESSION["organization_id"] . "." : "") . $key . ".cache";
    return $CI->cache->get($key);
  }
}


if (!function_exists("endpoint_name")) {
  function endpoint_name($list,$params = []){
    if(!$list) return null;
    $CI = get_instance();
    $config_list = $CI->config->item("endpoints_list");
    $path = NULL;

    if (isset($config_list[$list])) {
      $path = $config_list[$list];
      $path = $path ? explode("/",$path) : NULL;
      foreach ($path as $key => $item) {
        if (startsWith($item, '$')) {
          $path[$key] = isset($params[str_replace("$","",$item) - 1]) ? $params[str_replace("$","",$item) - 1] : $item;
        }
      }
    }
    return $path ? implode("/",$path) : null;
  }
}
