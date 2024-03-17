<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// ini_set('memory_limit','1200006M');

class Base {


  public static function callApi($method,$path = null,$data = null,$url = null){
    $CI = get_instance();
    $lang = isset($data["language"]) ? $data["language"] : $CI->config->item("current_language");
    $base_api_url = $CI->config->item("service_url");

    $path = $url ? $url : $base_api_url.$path;
    $path = !in_array($method,['PUT','POST']) ? sprintf("%s?%s", $path, http_build_query($data)) : $path;
    $data = $method === "POST" ? http_build_query($data) : json_encode($data,JSON_UNESCAPED_UNICODE);
    // Content-Type: application/x-www-form-urlencoded; charset=utf-8
    $header_list = $CI->config->item("service_credentials") + [
      "version:".$CI->config->item("system_version_int"),
      "Content-Type: application/json",
      "lang:$lang"
    ];
    $curl_body = [
      CURLOPT_URL => $path,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_POST => 1,
      CURLOPT_POSTFIELDS => $data,
      CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
      CURLOPT_HTTPHEADER => $header_list,
    ];
    // CURLOPT_MAXREDIRS => 10,

    // return $curl_body;
    $curl = curl_init();
    curl_setopt_array($curl, $curl_body);
    $res = curl_exec($curl);

    if(!$res){
      die("Connection Failure");
    }
    curl_close($curl);
    return json_decode($res, true);
  }

  public static function callApi2($method,$url,$data = null,$lang = NULL){
    $CI = get_instance();
    $lang = $CI->config->item("current_language");
    $data_string = json_encode($data,JSON_UNESCAPED_UNICODE);
    $ch = curl_init($url);
    $body = array_merge($CI->config->item("data_cisct_credentials"),[
                                      'Content-Type:application/json',
                                      'Lang:'. $lang,
                                      'Content-Length:'. strlen($data_string)
                                    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $body);
    $res = curl_exec($ch);
    return json_decode($res,true);
  }

  public static function sendEmail($name_from,$mail_from,$mail_to,$subject,$message){
    $CI = get_instance();

    $headers = array_merge($CI->config->item("data_cisct_credentials"),[
      "name_from" => $name_from,
      "mail_from" => $mail_from,
      "mail_to" => $mail_to,
      "subject" => $subject,
      "message" => $message,
      "source" => "AVTOHISSE"
    ]);
    $data_string = json_encode($headers);
    $ch = curl_init($CI->config->item("data_cisct_url")."services/send_email");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string))
    );
    $res = curl_exec($ch);
    return json_decode($res,true);
  }


  public static function resize($source_image, $destination, $tn_w, $tn_h, $color = [], $quality = 100, $wmsource = false){
      $info = getimagesize($source_image);
      $imgtype = image_type_to_mime_type($info[2]);
      switch ($imgtype) {
          case 'image/jpeg':
              $source = imagecreatefromjpeg($source_image);
              break;
          case 'image/gif':
              $source = imagecreatefromgif($source_image);
              break;
          case 'image/png':
              $source = imagecreatefrompng($source_image);
              break;
          default:
              die('Invalid image type.');
      }
      $src_w = imagesx($source);
      $src_h = imagesy($source);
      $x_ratio = $tn_w / $src_w;
      $y_ratio = $tn_h / $src_h;
      if (($src_w <= $tn_w) && ($src_h <= $tn_h)) {
          $new_w = $src_w;
          $new_h = $src_h;
      } elseif (($x_ratio * $src_h) < $tn_h) {
          $new_h = ceil($x_ratio * $src_h);
          $new_w = $tn_w;
      } else {
          $new_w = ceil($y_ratio * $src_w);
          $new_h = $tn_h;
      }
      $newpic = imagecreatetruecolor(round($new_w), round($new_h));
      imagecopyresampled($newpic, $source, 0, 0, 0, 0, $new_w, $new_h, $src_w, $src_h);
      $final = imagecreatetruecolor($tn_w, $tn_h);
      $c1 = 255;
      $c2 = 255;
      $c3 = 255;
      if ($color) {
        $c1 = isset($color[0]) ? $color[0] : 255;
        $c2 = isset($color[1]) ? $color[1] : 255;
        $c3 = isset($color[2]) ? $color[2] : 255;
      }
      $backgroundColor = imagecolorallocate($final, $c1, $c2, $c3);
      imagefill($final, 0, 0, $backgroundColor);
      //imagecopyresampled($final, $newpic, 0, 0, ($x_mid - ($tn_w / 2)), ($y_mid - ($tn_h / 2)), $tn_w, $tn_h, $tn_w, $tn_h);
      imagecopy($final, $newpic, (($tn_w - $new_w)/ 2), (($tn_h - $new_h) / 2), 0, 0, $new_w, $new_h);
      if ($wmsource) {
          $info    = getimagesize($wmsource);
          $imgtype = image_type_to_mime_type($info[2]);
          switch ($imgtype) {
              case 'image/jpeg':
                  $watermark = imagecreatefromjpeg($wmsource);
                  break;
              case 'image/gif':
                  $watermark = imagecreatefromgif($wmsource);
                  break;
              case 'image/png':
                  $watermark = imagecreatefrompng($wmsource);
                  break;
              default:
                  die('Invalid watermark type.');
          }

          #if we're adding a watermark, figure out the size of the watermark
          #and then place the watermark image on the bottom right of the image
          $wm_w = imagesx($watermark);
          $wm_h = imagesy($watermark);
          imagecopy($final, $watermark, $tn_w - $wm_w, $tn_h - $wm_h, 0, 0, $tn_w, $tn_h);

      }
      return imagejpeg($final, $destination, $quality) ? true : false;
  }


  public static function resize_image($file, $w, $h, $crop=FALSE, $extension = NULL) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    if (!$extension) {
      $src = imagecreatefromjpeg($file);
    }else{
      $src = imagecreatefrompng($file);
    }
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
  }

  public static function logo_on_image($target, $wtrmrk_file, $newcopy, $size = NULL) {
    $watermark = imagecreatefrompng($wtrmrk_file);
    if ($size && is_numeric($size)) {
      $wtrmrk_file = imagepng($wtrmrk_file, null, $size);
    }
    imagealphablending($watermark, false);
    imagesavealpha($watermark, true);
    $img = imagecreatefromjpeg($target);
    $img_w = imagesx($img);
    $img_h = imagesy($img);
    $wtrmrk_w = imagesx($watermark);
    $wtrmrk_h = imagesy($watermark);
    $dst_x = ($img_w / 2) - ($wtrmrk_w / 2); // For centering the watermark on any image
    $dst_y = ($img_h / 2) - ($wtrmrk_h / 2); // For centering the watermark on any image
    imagecopy($img, $watermark, $dst_x, $dst_y, 0, 0, $wtrmrk_w, $wtrmrk_h);
    imagejpeg($img, $newcopy, 100);
    imagedestroy($img);
    imagedestroy($watermark);
  }

  public static function str_limit($str,$limit){
    if (strlen($str) > $limit){
      $str = substr($str, 0, $limit) . '...';
    }
    return $str;
  }



}
