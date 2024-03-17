<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit','1200006M');
ini_set('max_execution_time', 900); //300 seconds = 5 minutes

class Api {

  private static function callInternal($method = "GET",$path = null,$data = [],$url = NULL,$CI = null,$base_api_url = null, $usecret = null,$ukey = null){
    $lang = $CI->config->item("current_language") ?: "az";
    $path = $url ?: $base_api_url.$path;
    $path = !in_array($method,["PUT","POST"]) ? sprintf("%s?%s", $path, http_build_query($data)) : $path;
    $data = $method === "POST" && is_array($data) ? http_build_query($data) : json_encode($data, JSON_UNESCAPED_UNICODE);

    $headers = array_merge(
      [
        "Usecret:" . $usecret,
        "Ukey:" . $ukey,
        "lang:" . $lang,
        // "userid:" . $CI->session->userdata("id"),
        "useraddress:" . user_ip(),
        "userdevice:" . rtrim(device(), '/'),
        "useragent:" . (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ""),
      ]
    );
    if (!in_array($method,["POST","GET"])) {
      $headers = array_merge($headers,["Content-Type: application/json"]);
    }

    $curl_body = [
      CURLOPT_URL => $path,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,

      CURLOPT_CONNECTTIMEOUT => (isset($params["connection_timeout"]) && (int)$params["connection_timeout"] ? (int)$params["connection_timeout"] : 5),

      CURLOPT_TIMEOUT => 5,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_POSTFIELDS => $data,
      CURLOPT_HTTPHEADER => $headers,
    ];

    $curl = curl_init();
    curl_setopt_array($curl, $curl_body);
    $res = curl_exec($curl);
    // var_dump($res);die;
    if(!$res && (!isset($params["return"]) || !$params["return"])){
      // throw new Exception(curl_error($curl), curl_errno($curl));
      die("Connection Failure");
    }

    curl_close($curl);
    return json_decode($res, true);
  }

  public static function call($method = "GET",$path = null,$data = [],$url = NULL){
    $CI = get_instance();
    $base_api_url = $CI->config->item("api_path");
    $usecret = $CI->config->item("api_usecret");
    $ukey = $CI->config->item("api_ukey");

    return static::callInternal($method,$path,$data,$url,$CI,$base_api_url,$usecret,$ukey);
  }

  public static function callExt($method = "GET",$path = null,$data = [],$url = NULL){
    $CI = get_instance();
    $base_api_url = $CI->config->item("data_integration_url");
    $usecret = $CI->config->item("data_integration_usecret");
    $ukey = $CI->config->item("data_integration_ukey");

    return static::callInternal($method,$path,$data,$url,$CI,$base_api_url,$usecret,$ukey);
  }

  public static function callCs($url = null,$method = "GET",$params = [], $base_url = null){
    $CI = get_instance();
    $curl = curl_init();
    $url = ($base_url ?: $CI->config->item("data_cisct_url")) . $url;
    $langs = $CI->config->item("languages");
    $lang = isset($_SERVER["HTTP_LANG"]) && in_array($_SERVER["HTTP_LANG"],$langs) ?
              $_SERVER["HTTP_LANG"] : $CI->config->item("language");
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_CONNECTTIMEOUT => (isset($params["connection_timeout"]) && $params["connection_timeout"] ? (int)$params["connection_timeout"] : 2),
      CURLOPT_TIMEOUT => (isset($params["connection_timeout"]) && $params["connection_timeout"] ? ((int)$params["connection_timeout"] + 2) : 2),
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_POSTFIELDS => json_encode($params),
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_HTTPHEADER => array_merge(['Lang: '.$lang], $CI->config->item("data_cisct_credentials")),
    ));

    $res = curl_exec($curl);
    curl_close($curl);

    // header("Content-type: application/json");
    // echo json_encode(json_decode($res, true));die;
    return json_decode($res, true);
  }
}
