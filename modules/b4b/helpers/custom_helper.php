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


if (!function_exists("get_client_ip")) {
  function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
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


if (!function_exists("limits")) {
  function limits($key = null){
    $CI = get_instance();
    $site_limits = $CI->config->item("site_limits");
    return isset($site_limits[$key]) ? $site_limits[$key] : NULL;
  }
}
