<?php



if (!function_exists('callDataService')) {
  function callDataService($method,$path = null,$data = null,$url = null){
    $CI = get_instance();
    $data_string = json_encode($data);
    $url = $url ? $url : $CI->config->item("data_cisct_url").$path;
    $ch = curl_init($url);
    $headers = array_merge($CI->config->item("data_cisct_credentials"),[
                                      'Content-Type:application/json',
                                      'Lang:'. (isset($_SESSION['language']) ? $_SESSION['language'] : 'az'),
                                      'Content-Length:'. strlen($data_string)
                                    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $res = curl_exec($ch);
    return json_decode($res,true);
  }
}


if (!function_exists('regions_list')) {
  function regions_list(){
    $regions = callDataService('GET','services/country/citylist',[]);
    $regions = isset($regions['data']) ? $regions['data'] : $regions;
    $region_list = [];
    foreach ($regions as $key => $region) {
      $region_list = $region_list + [$region["id"] => $region["name"]];
    }
    return $region_list;
  }
}


if (!function_exists('carbrand_list')) {
  function carbrand_list(){
    $brands = callDataService('GET','services/car_catalog/brands',[]);
    $brands = isset($brands['data']) ? $brands['data'] : $brands;
    $brand_list = [];
    foreach ($brands as $key => $brand) {
      $brand_list = $brand_list + [$brand["id"] => $brand["name"]];
    }
    return $brand_list;
  }
}

if (!function_exists('carbrands_byids')) {
  function carbrands_byids($ids){
    $ids = is_array($ids) ? $ids : [$ids];
    $models = callDataService('GET','services/car_catalog/info_by_id',$ids);
    $models = isset($models['data']) ? $models['data'] : $models;
    return $models;
  }
}

if (!function_exists('getCodesByCode')) {
  function getCodesByCode($ids){
    $ids = is_array($ids) ? implode(",",$ids) : $ids;
    $codes = callDataService('GET','services/cross/cross_references_search',["codes" => $ids]);
    $codes = isset($codes['data']) ? $codes['data'] : [];
    // $codes;
    return $codes;
  }
}

if (!function_exists('getCodesByEngine')) {
  function getCodesByEngine($engine = NULL,$category = NULL){
    if(!$engine || !$category) return [];
    $codes = callDataService('GET','services/cross/cross_references_search',
      [
        "engine" => $engine,
        "category" => $category,
      ]
    );
    $codes = isset($codes['data']) ? $codes['data'] : $codes;
    return $codes;
  }
}
