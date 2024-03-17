<?php

$access_key = "jaf-JSbo1VTTSwqOCEnjpycOjnWueDh";

if (php_sapi_name() === "cli" || (isset($_GET["access-key"]) && $_GET["access-key"] === $access_key)) {
  if (isset($argv[1]) && $argv[1] === "--logs") {
    $path = dirname(__FILE__) . "/modules/*/logs/*/*";
    $files = glob($path);
    foreach ($files as $file) {
      if (!in_array(substr($file, strrpos($file, '/') + 1),['.', '..','.gitkeep','index.html','.DS_Store']) && file_exists($file)) {
        unlink($file);
      }
    }

    $path = dirname(__FILE__) . "/application/logs/*/*";
    $files = glob($path);
    foreach ($files as $file) {
      if (!in_array(substr($file, strrpos($file, '/') + 1),['.', '..','.gitkeep','index.html','.DS_Store']) && file_exists($file)) {
        unlink($file);
      }
    }
  } else {
    $path = dirname(__FILE__) . "/application/cache";
    $files = array_diff(scandir($path), array('.', '..','index.html','.DS_Store'));
    foreach ($files as $file) {
      if (file_exists($path . "/" . $file)) {
        unlink($path . "/" . $file);
      }
    }


    $path = dirname(__FILE__) . "/modules/*/cache/*";
    $files = glob($path);
    foreach ($files as $file) {
      if (!in_array(substr($file, strrpos($file, '/') + 1),['.', '..','.gitkeep','index.html','.DS_Store']) && file_exists($file)) {
        unlink($file);
      }
    }
  }

  echo "OK\n";
  die;
}


header("Location: /");
die("NOT ALLOWED!");
