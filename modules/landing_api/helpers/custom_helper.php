<?php

if (!function_exists('callAPI')) {
  function callAPI($method,$path = null,$data = null,$url = null){
    $curl = curl_init();

    $CI = get_instance();
    $base_api_url = $CI->config->item("service_url");
    $path = $url ? $url : $base_api_url.$path;
    $path = !in_array($method,['PUT','POST']) ? sprintf("%s?%s", $path, http_build_query($data)) : $path;
    $data = $method === "POST" ? http_build_query($data) : json_encode($data);
    curl_setopt_array($curl, [
      CURLOPT_URL => $path,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_TIMEOUT => 0,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_POST => 1,
      CURLOPT_POSTFIELDS => $data,
      CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
      CURLOPT_HTTPHEADER => $CI->config->item("ARM_service_credentials") +  ['Content-Type: application/json'],
    ]);
    $res = curl_exec($curl);
    if(!$res){
      die("Connection Failure");
    }
    curl_close($curl);
    return json_decode($res, true);
  }
}


if (!function_exists('callARM_API')) {
  function callARM_API($method,$path = null,$data = null,$url = null){
    $CI = get_instance();
    $curl = curl_init();
		// $data = http_build_query($data);
		switch ($method)
		{
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);
				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			default:
				if ($data)
					$path = $url ? sprintf("%s?%s", $url, http_build_query($data)) : sprintf("%s?%s", $path, http_build_query($data));
		}
		// OPTIONS:
		curl_setopt($curl, CURLOPT_URL, ($url ? $url : $CI->config->item("ARM_service_url").$path));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $CI->config->item("ARM_service_credentials"));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		// EXECUTE:
		$result = curl_exec($curl);
		if(!$result){die("Connection Failure");}
			curl_close($curl);
		return json_decode($result, true);
  }
}



if (!function_exists('updateCDN')) {
  function updateCDN($data = [],$path = "default-path",$sizes = ["small,240","large,960"]){
    $CI = get_instance();
    $body = [
      "source" => $CI->config->item("cdn_source_name"),
      "folder" => $path,
      "sizes" => $sizes,
      "files" => $data
    ];

    $curl = curl_init();
    $curl_list = array(
      CURLOPT_URL => $CI->config->item("cdn_cisct_url")."services/cdn/upload_media",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => json_encode($body),
      CURLOPT_HTTPHEADER => $CI->config->item("ARM_service_credentials") +  ['Content-Type: application/json'],
    );
    curl_setopt_array($curl, $curl_list);

    $data = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($data,true);
    // return $curl_list;
    if (isset($data["code"]) && $data["code"] === 200) {
      return $data["data"];
    }else{
      return [
        "id" => null,
        "host" => null,
        "path" => null,
        "file" => null,
        "folder" => []
      ];
    }
  }
}

if (!function_exists('isNullSQL')) {
  function isNullSQL($data = null){
    if(!$data){ return "NULL"; }
    return $data ? "'".trim(stripslashes(str_replace("'","",$data)))."'" : "NULL";
  }
}


if (!function_exists('rest_response')) {
  function rest_response($code = null,$mess = null,$data = []){
    return [
      'code' => $code ? $code : '',
      'message' => $mess ? $mess : '',
      'data' => $data
      // (is_array($data) || is_object($data)) && count($data) ? $data : []
    ];
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

if (!function_exists('str_limit')) {
  function str_limit($str,$limit){
    if (strlen($str) > $limit){
      $str = substr($str, 0, $limit) . '...';
    }
    return $str;
  }
}

if (!function_exists('getConfig')) {
  function getConfig($file = null){
    if(!$file) return [];
    $path = APPPATH.'config/jsons/'.$file.'.json';
    if(!file_exists($path)) return [];
    $myfile = fopen($path, "r") or die("Unable to open file!");
    $data = json_decode(fread($myfile,filesize($path)),true);
    fclose($myfile);
    return $data;
  }
}
