<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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

if (!function_exists("assets")) {
  function assets($path,$global = false) {
    if(!$path) return "";
    $CI = get_instance();
    if ($global) {
      $folder = "assets/global/";
    } else {
      $folder = $CI->config->item("assets_path")."/";
    }

    $is_production = $CI->config->item("system_mode") === "production";

    $version = !$is_production && isset(pathinfo($path)["extension"]) && in_array(pathinfo($path)["extension"],["js","css"]) ? '?v='.uniqid() : "?v=".$CI->config->item("asset_version");

    $ext = pathinfo($path)["extension"];
    $temp = explode('.', $path);
    unset($temp[count($temp) - 1]);
    $path = implode('.', $temp);
    return base_url($folder.$path.".".$ext).$version;

  }
}

if (!function_exists("lang")) {
  function lang($key = null,$params = []){
    if(!$key) return "";
    $CI = get_instance();
    if ($params) {
      $content = $CI->lang->line($key);
      foreach ($params as $key_name => $value) {
        $content = str_replace("%".$key_name,$value,$content);
      }
      return $content;
    }
    return $CI->lang->line($key) ?: "no_locale.$key";
  }
}

// if (!function_exists("lang")) {
//   function lang($key = null,$params = null){
//     if(!$key) return "";
//     $CI = get_instance();
//     if ($params) {
//       if ($CI->lang->line($key) && str_contains($CI->lang->line($key),$params["key"])) {
//         $string = str_replace($params["key"],$params["value"],$CI->lang->line($key));
//         return $string;
//       }else{
//         return $CI->lang->line($key) ?: "no_locale.$key";
//       }
//     }
//
//     return $CI->lang->line($key) ?: "no_locale.$key";
//   }
// }


if (!function_exists("readSVG")) {
  function readSVG($name = null){
    if(!$name) return "";
    $CI = get_instance();
    $folder = $CI->config->item("assets_svg_path")."/";
    $file = APPPATH."../".$folder."/{$name}.svg";
    if(!file_exists($file)) return "";
    $svg = file_get_contents($file);
    return $svg;
  }
}


if (!function_exists("startsWith")) {
  function startsWith($haystack,$needle) {
    if (phpversion() > 8) {
      return str_starts_with($haystack,$needle);
    }else{
      $length = strlen($needle);
      return substr($haystack,0,$length) === $needle;
    }
  }
}

if (!function_exists("endsWith")) {
  function endsWith($haystack,$needle) {
    if (phpversion() > 8) {
      return str_ends_with($haystack,$needle);
    }else{
      $length = strlen($needle);
      if(!$length) return true;
      return substr($haystack, -$length) === $needle;
    }
  }
}



if (!function_exists('device')) {
  function device($default = null){
    if($default){return $default;}
    $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : NULL;
    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent) ||
            preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
              substr($useragent,0,4))){
        return "mobile";
    }
    return "web";
  }
}


if (!function_exists("user_ip")) {
  function user_ip() {
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


if (!function_exists("str_limit")) {
  function str_limit($string = null,$limit = 30){
    $string = trim($string);
    $string = strip_tags($string);
    if(!$string) return "";
    $string = strlen($string) > $limit ? substr_replace($string, "...", $limit) : $string;
    return $string;
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


if (!function_exists("checkHeaders")) {
  function checkHeaders($auth_validate = true){
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    // var_dump($CI->db->host);die;
    if (ENVIRONMENT === "production" && isset($_SERVER['REMOTE_ADDR'])) {
      if ($_SERVER['REMOTE_ADDR'] !== "") {
        // code...
      }
    }

    if ($auth_validate) {
      $CI = get_instance();
      $config = $CI->config->item('api_credentials');

      if (!headers("usecret") || !headers("ukey") || !isset($config[headers("usecret")]["ukey"]) || $config[headers("usecret")]["ukey"] !== headers("ukey")) {
        echo json_encode(rest_response(Status_codes::HTTP_UNAUTHORIZED,lang("Not authorized")));
        die;
      }
    }
  }
}

if (!function_exists("checkIsLocalRequest")) {
  function checkIsLocalRequest(){
    $CI = get_instance();
    $config = $CI->config->item('api_credentials');

    return headers("usecret") &&
            headers("ukey") &&
            isset($config[headers("usecret")]["ukey"]) &&
            $config[headers("usecret")]["ukey"] === headers("ukey") &&
            isset($config[headers("usecret")]["is_local"]) &&
            $config[headers("usecret")]["is_local"];
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

if (!function_exists("user_ip_address")) {
  function user_ip_address(){
    $ip_address = isset($_SERVER['HTTP_USERADDRESS']) && filter_var($_SERVER['HTTP_USERADDRESS'], FILTER_VALIDATE_IP) ? $_SERVER['HTTP_USERADDRESS'] : NULL;
    return $ip_address;
  }
}

if (!function_exists("logRequest")) {
  function logRequest($params){
    $CI = get_instance();
    $usecret = isset($_SERVER['HTTP_USECRET']) ? $_SERVER['HTTP_USECRET'] : NULL;
    $ip_address = user_ip_address();
    $get_client_ip = get_client_ip();
    $inserted_list = [
      "domain" => $_SERVER["HTTP_HOST"],
      "path" => $_SERVER["REQUEST_URI"],
      "description" => isset($params["description"]) ? $params["description"] : NULL,
      "ip_address" => $ip_address ? encode_blob($ip_address) : NULL,
      "ip_address" => $get_client_ip ? encode_blob($get_client_ip) : NULL,
      "user_usecret" => $usecret ? encode_blob($usecret) : NULL,
      "response_status" => isset($params["status"]) ? $params["status"] : 0,
      "device" => isset($params["device"]) ? $params["device"] : null,
      "content_length" => isset($params["length"]) ? $params["length"] : 0,
      "response_type" => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : NULL
    ];
    $CI->local_db = $CI->load->database("local_db",true);
    $CI->local_db->insert(local_table_name("logs"),$inserted_list);
  }
}




if (!function_exists("json_response")) {
  function json_response($data,$no_auth = null,$log = true){

    if (!$no_auth) {
      checkHeaders();
    } else {
      checkHeaders(false);
    }
    if(!$data || !is_array($data)) {
      echo json_encode(rest_response(409,"No body"),[$data]);
      die;
    }

    if (isset($_SERVER['REQUEST_METHOD']) && !in_array($_SERVER['REQUEST_METHOD'],["GET","HEAD"]) && $log) {
      $path_list = explode("/",$_SERVER['REQUEST_URI']);
      if(isset($path_list[0])) unset($path_list[0]);
      logRequest([
        "status" => (isset($data["code"]) ? $data["code"] : 0),
        "description" => (isset($data["message"]) ? $data["message"] : 0),
        "length" => (isset($data["data"]) ? strlen(json_encode($data["data"])) : 0),
        "user" => headers("usertoken") ?: null,
        "module" => isset($path_list[2]) ? special_codes("logs.modules." . $path_list[2]) : NULL,
        "device" => headers("userdevice") ?: null
      ]);
    }

    echo json_encode($data);
    die;
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
