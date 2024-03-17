<?php
defined('BASEPATH') or exit('No direct script access allowed');


if(!function_exists("getDaysBetween")){
  function getDaysBetween($start_date, $end_date = NULL) {
    if (!$start_date) {
      return false;
    }
    $now = $end_date ? strtotime($end_date) : time();
    $start_date = strtotime($start_date);
    $datediff = $now - $start_date;

    return round($datediff / (60 * 60 * 24));
  }
}

if(!function_exists("oemstToproductCodes")){
  function oemstToproductCodes($product) {
    if((string)!$product["cleaned_OEM"] && (string)!$product["cleaned_brand_code"]){return [];}

    $cross_codes = [];
    $all_cleaned_oems = array_values(array_filter(array_unique(explode(OEM_SPRTR, $product["cleaned_OEM"]))));

    foreach ($all_cleaned_oems as $key => $item) {
      if(strlen($item)) {

        $token = md5($product["token"]. OEM_SPRTR . $item . OEM_SPRTR);

        $insert_code = [
          "token" => $token,
          "product_token" => $product["token"],

          "code" => $item,
          "cleaned_code" => $item,

          "deleted_at" => NULL
        ];

        $cross_codes[$token] = $insert_code;
      }
    }

    if($product["cleaned_brand_code"]) {
      $token = md5($product["token"]. OEM_SPRTR . $product["cleaned_brand_code"] . OEM_SPRTR);

      $insert_code = [
        "token" => $token,
        "product_token" => $product["token"],

        "code" => $product["cleaned_brand_code"],
        "cleaned_code" => $product["cleaned_brand_code"],

        "deleted_at" => NULL
      ];
      $cross_codes[$token] = $insert_code;
    }

    $cross_codes = array_values($cross_codes);

    return $cross_codes;
  }
}

if(!function_exists("b2bOemShower")){
  function b2bOemShower($cleaned_OEM, $with_matched_keyword = false, $keyword = "", $is_array = false,$show_count = 3){
    $oems = explode(OEM_SPRTR, $cleaned_OEM);
    $matched_oem_list = [];
    $additional_oem_list = [];

    if($oems) {
      $oems = array_values(array_filter($oems));
      if($with_matched_keyword && $keyword){
        foreach ($oems as $key => $item) {
          if($item && strpos($item, $keyword)) {
            $matched_oem_list[] = $item;
            unset($oems[$key]);
          }
        }
      }

      if($oems) {
        for($i = 0; $i < ($show_count - count($matched_oem_list)); $i++) {
          $additional_oem_list[] = $oems[array_rand($oems)];
        }
      }

      $additional_oem_list = array_unique($additional_oem_list);

      $product_oems = array_merge($matched_oem_list, $additional_oem_list);
      $product_oems = array_slice($product_oems, 0, $show_count);
      return $is_array ? $product_oems : implode(" ", $product_oems);
    }

    // return "";
    return $is_array ? [] : "";
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


if(!function_exists("toNumber")) {
  function toNumber($dest){
    if ($dest && is_string($dest)) {
      $letters = str_split($dest);
      $string = "";
      foreach ($letters as $item) {
        if (is_numeric($item)) {
          $string .= $item;
        } else {
          $string .= ord(strtolower($item)) - 96;
        }
      }
      return $string;
    }
    return 0;
  }
}

if(!function_exists("send_attachment_mail")) {
  function send_attachment_mail($user_email, $subject, $text_message, $file_name)
	{
		$to = $user_email;

		$from = 'no-reply@test.loc';
		$fromName = 'B4B | TEST Customer';

    return Mail::send([
      // "mail_to" => $to,
      "mail_to" => "test@test.loc",
      "mail_from" => $from,
      "name_from" => "B4B - Customer",
      "subject" => $subject,
      "message" => $text_message,
      "attachments" => $file_name,
    ]);
	}
}

if(!function_exists("get_currency")) {
  function get_currency(){
    $CI = get_instance();
    $key = "api." . CURRENCY_CACHED_FILE . ".cache";
    $data = $CI->cache->get($key);
    if(!$data) {
      $CI->local_db = $CI->load->database("local_db",true);
      $cached_currencies_query = $CI->local_db->query("SELECT
                                                          `id`,
                                                          `remote_id`,
                                                          `name`,
                                                          `short_name`,
                                                          `main_name`,
                                                          `value`
                                                         FROM `".local_table_name("cached_currencies")."`
                                                         WHERE `deleted_at` IS NULL");
      $cached_currencies = [];
      if ($cached_currencies_query->num_rows()) {
        $cached_currencies = $cached_currencies_query->result_array();
        $CI->cache->save($key, $cached_currencies, 3600 * 3);
      }
      return $cached_currencies;
    }
    return $data;
  }
}

if(!function_exists("stock_visibility")) {
  function stock_visibility($resource, $stock_show, $stock) {
    $stock_visibility = [
      "quantity" => null,
      "title" => null,
      "icon" => true,
      "class" => null,
      "to_order" => true,
      "is_check" => false,
    ];

    if($stock > 0) {
      $stock_visibility["is_check"] = true;
    }

    if($stock > STOCK_SHOW_MIN_QUANTITY && $stock <= STOCK_SHOW_MAX_QUANTITY) {
      $stock_visibility["quantity"] = $stock;
      $stock_visibility["icon"] = false;
    }

    if($stock_show === STATUS_ACTIVE) {
      $stock_visibility["quantity"] = $stock;
      $stock_visibility["icon"] = false;
    }

    if($stock >= STOCK_AVA_HAS_QUANTITY) {
      $stock_visibility["title"] = lang("stock_has");
      $stock_visibility["class"] = "success";
    }
    if($stock <= STOCK_AVA_HAS_NOT_QUANTITY) {
      $stock_visibility["title"] = lang("stock_has_not");
      $stock_visibility["class"] = "danger";
      $stock_visibility["to_order"] = false;
    }
    if($stock > STOCK_AVA_HAS_NOT_QUANTITY && $stock < STOCK_AVA_HAS_QUANTITY) {
      $stock_visibility["title"] = lang("stock_almost_has");
      $stock_visibility["class"] = "warning";
    }

    if($resource !== special_codes("products.resources.ava")) {
      $stock_visibility["icon"] = true;
      $stock_visibility["quantity"] = null;
      $stock_visibility["title"] = lang("with_order");
      $stock_visibility["class"] = "info";
      $stock_visibility["is_check"] = true;
    }

    return $stock_visibility;
  }
}

if (!function_exists("cleaned_text")) {
  function cleaned_text($data){
    return trim(preg_replace("/[^a-zA-Z0-9]+/", "", $data));
  }
}

if (!function_exists("special_codes")) {
  function special_codes($params = null, $reverse = null){
    $CI = get_instance();
    $special_codes = $CI->config->item("special_codes");

    if ($params) {
      $params_array = explode(".",$params);
      $res = "";
      foreach ($params_array as $key => $item) {
        if (!$res) {
          $res = isset($special_codes[$item]) ? $special_codes[$item] : NULL;
        } else if(isset($res[$item])) {
          $res = $res[$item];
        }
      }
      if (is_array($res) && $reverse) {
        foreach ($res as $key => $item) {
          if ($item === $reverse) {
            return $item === $reverse ? $key : NULL;
          }
        }
      }
      return $res;
    }
    return $special_codes;
  }
}

if (!function_exists("configs")) {
  function configs($params = []){
    $CI = get_instance();
    $key = md5($params["group"])."_configs.cache";
    $CI->local_db = $CI->load->database("local_db",true);

    $res = $CI->cache->get($key);
    if (!$res){
      $configs_sql = "SELECT
                          `id`,
                          `key`,
                          `value`,
                          `group`,
                          `resource`
                     FROM `".local_table_name("b4b_configs")."`
                     WHERE `group` IN  ('".implode("','",array_values(special_codes("configs.groups")))."')
                     AND `resource` IN ('".implode("','",array_values(special_codes("configs.resources")))."')
                     AND `type` IN ('".implode("','",array_values(special_codes("configs.types")))."')
                     AND `is_active` = '".STATUS_ACTIVE."'
                     AND `deleted_at` IS NULL ";
      $configs_query = $CI->local_db->query($configs_sql);

      if (!$configs_query->num_rows()) {
          return rest_response(
            Status_codes::HTTP_NO_CONTENT,
            lang("Config not found")
          );
      }

      $configs = $configs_query->result_array();

      $res = [];
      foreach (special_codes("configs.groups") as $key => $item) {
        foreach ($configs as $conf_key => $config) {
          if ($item === $config["group"]) {
            $res[$key][$config["key"]][] = $config["value"];
          }
        }
      }


      if (isset($res["data"][$params["group"]]) && isset($res["code"]) && $res["code"] === 200) {
        $res = $res["data"][$params["group"]];

        $CI->cache->save($key, $res, 3600 * 7 * 24);
      }
    }
    return isset($res["data"]) ? (isset($params["group"]) ? [] : $res["data"]) : $res;
  }
}



if (!function_exists("local_table_name")) {
  function local_table_name($name = null,$type = null){
    $CI = get_instance();
    $tables = $CI->config->item("db_local_tables");
    return isset($tables[$name]) ? $tables[$name] : ($type === "full" ? $tables : null);
  }
}

if (!function_exists("remote_table_name")) {
  function remote_table_name($name = null,$type = null){
    $CI = get_instance();
    $tables = $CI->config->item("db_remote_tables");
    return isset($tables[$name]) ? $tables[$name] : ($type === "full" ? $tables : null);
  }
}

if (!function_exists("now")) {
  function now(){
    return date('Y-m-d H:i:s');
  }
}

if (!function_exists("headers")) {
  function headers($name = null){
    return isset($_SERVER['HTTP_'.strtoupper($name)]) ? $_SERVER['HTTP_'.strtoupper($name)] : null;
  }
}

if (!function_exists("serverKey")) {
  function serverKey($name = null){
    return isset($_SERVER[strtoupper($name)]) ? $_SERVER[strtoupper($name)] : null;
  }
}

if(!function_exists("removeDashboardCache")) {
  function removeDashboardCache($place){
    $CI = get_instance();
    $file = "b4b_" . $place . "_datas.cache";

    $dir = APPPATH . "cache";

    if (file_exists($dir . '/' . $file)) {
      unlink($dir . '/' . $file);
    }
  }
}
