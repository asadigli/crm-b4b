<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cdn {

  static function imgResize($source_image, $destination, $tn_w, $tn_h, $quality = 100, $wmsource = false,$bg_color = null){
      $info = getimagesize($source_image);
      $imgtype = image_type_to_mime_type($info[2]);

      switch ($imgtype) {
          case 'image/jpeg':
              $source = imagecreatefromjpeg($source_image);
              break;
          case 'image/jpg':
              $source = imagecreatefromjpeg($source_image);
              break;
          case 'image/gif':
              $source = imagecreatefromgif($source_image);
              break;
          case 'image/png':
              $source = imagecreatefrompng($source_image);
              break;
          case 'image/webp':
              $source = imagecreatefromwebp($source_image);
              break;
          default:
      }

      #Figure out the dimensions of the image and the dimensions of the desired thumbnail
      $src_w = imagesx($source);
      $src_h = imagesy($source);


      #Do some math to figure out which way we'll need to crop the image
      #to get it proportional to the new size, then crop or adjust as needed

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
      $backgroundColor = is_null($bg_color) ? imagecolorallocate($final, 255, 255, 255) : $bg_color;
      imagefill($final, 0, 0, $backgroundColor);
      //imagecopyresampled($final, $newpic, 0, 0, ($x_mid - ($tn_w / 2)), ($y_mid - ($tn_h / 2)), $tn_w, $tn_h, $tn_w, $tn_h);
      imagecopy($final, $newpic, (($tn_w - $new_w)/ 2), (($tn_h - $new_h) / 2), 0, 0, $new_w, $new_h);

      #if we need to add a watermark
      if ($wmsource) {
          #find out what type of image the watermark is
          $info    = getimagesize($wmsource);
          $imgtype = image_type_to_mime_type($info[2]);

          #assuming the mime type is correct
          switch ($imgtype) {
            case 'image/jpeg':
                $watermark = imagecreatefromjpeg($wmsource);
                break;
            case 'image/jpg':
                $watermark = imagecreatefromjpeg($wmsource);
                break;
            case 'image/gif':
                $watermark = imagecreatefromgif($wmsource);
                break;
            case 'image/png':
                $watermark = imagecreatefrompng($wmsource);
                break;
            case 'image/webp':
                $watermark = imagecreatefromwebp($wmsource);
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
      if (imagejpeg($final, $destination, $quality)) {
          return true;
      }
      return false;
  }


  static function base64ToJpeg($base64_string, $output_file) {
    $ifp = fopen( $output_file, 'wb' );
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode( ',', $base64_string );
    fwrite( $ifp, base64_decode( $data[ 1 ] ) );
    fclose( $ifp );

    return $output_file;
  }


  static function getBase64FromImage($path) {
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    return $base64;
  }


}
