<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// if (!function_exists("lang")) {
//   function lang($key = null){
//     if(!$key) return "";
//     $CI = get_instance();
//     return $CI->lang->line($key) ?: "no_locale.$key";
//   }
// }

if (!function_exists('rest_response')) {
  function rest_response($code = null,$message = null,$data = []){
    return [
      "code" => $code,
      "message" => $message,
      "data" => $data
    ];
  }
}

if (!function_exists("lang")) {
  function lang($key = null,$params = []){
    if(!$key || !is_string($key)) return "";
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

// if (!function_exists('getConfig')) {
//   function getConfig($file = null,$direct_path = null){
//     if(!$file && !$direct_path) return [];
//     $path = $file ? APPPATH.'config/jsons/'.$file.'.json' : $direct_path;
//     if(!file_exists($path)) return [];
//     $myfile = fopen($path, "r") or die("Unable to open file!");
//     $data = json_decode(fread($myfile,filesize($path)),true);
//     fclose($myfile);
//     return $data;
//   }
// }

if (!function_exists("readSVG")) {
  function readSVG($name = null){
    if(!$name) return "";
    $CI = get_instance();
    $file = FCPATH . "assets/".$CI->config->item("current_module_name")."/svgs/{$name}.svg";
    if(!file_exists($file)) return "";
    $svg = file_get_contents($file);
    return $svg;
  }
}


if (!function_exists('version')) {
  function version(){
    return '?v='.md5(microtime());
  }
}

if (!function_exists('path_local')) {
  function path_local($path = null){
    $CI = get_instance();
    $lang = $CI->config->item("current_language");
    $lang_path = $lang !== "az" ? $lang . "/" : "";
    return base_url() . $lang_path . ($path ?: "");
  }
}


if (!function_exists('assets')) {
  function assets($path,$global = false) {
    if(!$path) return "";
    $CI = get_instance();
    if (!$global) {
      $folder = "assets/" . $CI->config->item("current_module_name")."/";
    } else {
      $folder = "assets/globals/";
    }
    $version =  $CI->config->item("asset_version") ?: uniqid();

    $version = ENVIRONMENT !== "production" && isset(pathinfo($path)["extension"]) && in_array(pathinfo($path)["extension"],["js","css"]) ? '?v=01'.uniqid() : "?v=01".md5(date("Y-m-d"));
    return base_url($folder.$path).$version;
  }
}


if (!function_exists('slugify')) {
  function slugify($text){
    $latin = array('á', 'Ü', 'ü', 'Ş', 'ş', 'é', 'í', 'ó', 'Ö', 'ö', 'ú', 'ñ', 'ç', 'ğ', 'ü', 'à', 'è', 'ì', 'İ', 'ò', 'ù', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', 'Ç', 'Ğ', 'Ü', 'À', 'È', 'Ì', 'ı', 'Ò', 'Ù', 'ə', 'Ə',
			'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
            'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
            'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
            'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
		);

    $plain = array('a', 'u', 'u', 's', 's', 'e', 'i', 'o', 'o', 'o', 'u', 'n', 'c', 'g', 'u', 'a', 'e', 'i', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'N', 'C', 'g', 'U', 'A', 'E', 'I', 'i', 'O', 'U', 'e', 'E',
			'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
            'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
            'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
            'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
		);
		$slug = str_replace($latin, $plain, $text);
		$slug = url_title($slug);
		$slug = strtolower($slug);
    return $slug;
  }
}


if (!function_exists('custom_url')) {
  function custom_url($url,$lang = null){
    // $_SESSION["custom_url_path"] = $url;
    // $_SESSION["custom_url_lang"] = $lang;
    $lang = $lang ? $lang : $_SESSION["language"];
    $lang_in_route = $lang !== 'az' ? $lang . ($url ? '/' : '') : '';
    $lang_path = $lang ? $lang : 'az';
    // ['','az','en','ru','tr']

    $path = APPPATH.'config/jsons/route_langs/'.$lang_path.'.json';
    $myfile = fopen($path, "r") or die("Unable to open file!");
    $data = json_decode(fread($myfile,filesize($path)),true);
    fclose($myfile);

    $route_path = isset($data[$url]) ? $data[$url] : $url;
    return base_url($lang_in_route.$route_path);
  }
}

if (!function_exists('cacheInJson') && !function_exists('readCacheInJson')) {
  function cacheInJson($file){
    if(!$file){return '';}
    $path = APPPATH.'config/jsons/cache/'.$file.'.json';
    if (!file_exists($path)) {return '';}
    $myfile = fopen($path, "r") or die("Unable to open file!");
    $data = json_decode(fread($myfile,filesize($path)),true);
    fclose($myfile);
    return $data;
  }


  function writeCacheInJson($file,$content){
    if(!$file || !$content){return '';}
    $content = is_array($content) ? json_encode($content) : $content;
    $path = APPPATH.'config/jsons/cache/'.$file.'.json';
    $fp = fopen($path, 'w');
    fwrite($fp, $content);
    fclose($fp);
    chmod($path, 0777);
    return true;
  }
}


if (!function_exists('str_limit')) {
  function str_limit($str,$limit){
    if (strlen($str) > $limit){
      $str = substr($str, 0, $limit) . '...';
    }
    return $str;
  }
}



if (!function_exists('langSwitcher')) {
  function getLangKey($word,$lang){
    $path = APPPATH.'config/jsons/route_langs/'.$lang.'.json';
    $myfile = fopen($path, "r") or die("Unable to open file!");
    $data = json_decode(fread($myfile,filesize($path)),true);
    fclose($myfile);
    $key = '';
    foreach (array_keys($data) as $index => $value) {
      if ($word === $data[$value]) {
        $key = $value;
      }
    }
    return $key;
  }

  function langSwitcher($uri = null,$lang = "az",$url = null,$current_lang = null){
    if (!$current_lang) {
      $CI = get_instance();
      $current_lang = $CI->config->item("current_language");
    }
    $uri = $current_lang === 'az' ? $uri : ltrim(strstr($uri, '/'), '/');
    $route = $lang === 'az' ? $uri : $lang.($uri && $uri !== '/' ? '/'.$uri : '');
    return base_url($route);
  }
}



if (!function_exists("json_response")) {
  function json_response($val,$type = null){
    $header = isset($_SERVER['HTTP_HEADERKEY']) ? $_SERVER['HTTP_HEADERKEY'] : NULL;
    $CI = get_instance();
    if ($type !== "no_auth") {
      if (!$header || $header !== $CI->config->item("headerKey")) {
        echo json_encode([
          "code" => Status_codes::HTTP_UNAUTHORIZED,
          "message" => 'Not authorized',
          "data" => []
        ]);
        die;
      }
    }
    if (!is_array($val)) {
      var_dump($val);die;
    }
    echo json_encode($val);
  }
}
