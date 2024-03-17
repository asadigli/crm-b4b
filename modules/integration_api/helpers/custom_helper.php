<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists("refreshLocalCachedListCache")) {
  function refreshLocalCachedListCache($type) {
    $CI = get_instance();
    $groups = $CI->config->item("cache_types");

    if(isset($groups[$type]["cache-list"])) {
      foreach ($groups[$type]["cache-list"] as $key => $item) {
        $file = $item . ".cache";
        $dir = APPPATH . "cache";
        if (file_exists($dir . DIRECTORY_SEPARATOR . $file)) {
          unlink($dir . DIRECTORY_SEPARATOR . $file);
        }
      }
    }
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


if (!function_exists("search_clean")) {
  function search_clean($data) {
    $data = urldecode($data);

    // GET PARAM OLARAQ BOSHLUQ GONDERILDIKDE + OLARAQ GELIR
    // BU SEBEBDEN ILKIN OLARAQ + LAR BOSHLUQ ILE EVEZ OLUR
    $data = str_replace("+", " ", $data);

    $arr_symbol = array("'", '"', "<", ">", "#", "%", "(", ")", "^", "\"", "&", "=", "+", ";", "{", "}", "alert", "script", "style", "_");
    $replace_info = str_replace($arr_symbol, "", $data);

    $replace_info = strip_tags($replace_info);
    $replace_info = htmlspecialchars($replace_info);
    return $replace_info;
  }
}

if (!function_exists("decode_blob")) {
  function decode_blob($param){
    if(!$param) return;
    return hex2bin(strtolower(substr($param, 2)));
  }
}

if (!function_exists("encode_blob")) {
  function encode_blob($param = null){
    if(!$param) return;
    return "0x".strtoupper(bin2hex($param));
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

if (!function_exists("insert_dublicate_key")) {
  function insert_dublicate_key($table_name = null, $insert_list = []) {
    if (!$table_name || !$insert_list) {
      return "";
    }
    $sql_keys = [];
    $sql_values = [];
    $key_name_list = [];
    foreach ($insert_list as $key => $item) {
      if (!$key) {
        $sql_keys = array_keys($item);
        foreach ($sql_keys as $key_name) {
          $key_name_list[] = "`$key_name` = VALUES(`$key_name`)";
        }
      }
      $sub_values = [];
      foreach ($sql_keys as $item_sub) {
        if ($item[$item_sub] !== "" && $item[$item_sub] !== null) {
          $sub_values[] = is_int($item[$item_sub]) ? $item[$item_sub] : "'" . trim($item[$item_sub]) . "'";
        } else {
          $sub_values[] = "NULL";
        }
      }
      $sql_values[] = "(".implode(",",$sub_values).")";
    }
    $insert_query = "(`".implode("`,`",$sql_keys)."`)";
    $values_query = implode(",",$sql_values);
    $key_names = implode(",",$key_name_list);
    return "INSERT INTO `$table_name` $insert_query
            VALUES $values_query
            ON DUPLICATE KEY
            UPDATE $key_names";
  }
}

if (!function_exists('rest_response')) {
  function rest_response($code = null,$message = null,$data = []){
    return [
      "code" => $code,
      "message" => $message,
      "data" => $data
    ];
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

if (!function_exists("local_table_name")) {
  function local_table_name($name = null,$type = null){
    $CI = get_instance();
    $tables = $CI->config->item("db_local_tables");
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

if (!function_exists("ava_tr_code_value")) {
  function ava_tr_code_value($tr_code) {
		$arr_tr_code = [
      1	   => "Nağd ödəniş",
			2	   => "Borclandırma",
			3	   => "Borclandırma",
			4	   => "Kredit notu",
			6	   => "Kurs fərqi",
			14	 => "Başlanğıc",
			33	 => "Qaytarma",
			38 	 => "Satış fakturası"
    ];

		if(isset($arr_tr_code[$tr_code])) {
			$retun_code = $arr_tr_code[$tr_code];
		} else {
			$retun_code = "Əməliyyat kodu: " . $tr_code;
		}
		return $retun_code;
	}
}


if (!function_exists("getAccountDescription")) {
  function getAccountDescription($code) {
		$description_list = [
      1	   => special_codes("cached_customer_accounts.types.payment"),
			2	   => special_codes("cached_customer_accounts.types.payment"),
			3	   => special_codes("cached_customer_accounts.types.debt_second"),
			4	   => special_codes("cached_customer_accounts.types.credit"),
			6	   => special_codes("cached_customer_accounts.types.currency_difference"),
			14	 => special_codes("cached_customer_accounts.types.initial"),
			33	 => special_codes("cached_customer_accounts.types.return"),
			38 	 => special_codes("cached_customer_accounts.types.sale_invoice"),
      31   => special_codes("cached_customer_accounts.types.purchase_invoice"),
    ];

    $retun_code = null;
		if(isset($description_list[$code])) {
			$retun_code = $description_list[$code];
		}
		return $retun_code;
	}
}
