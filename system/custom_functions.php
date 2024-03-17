<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function fast_dump($data) {
  if (ENVIRONMENT === "production") {
    if (!isset($_GET["mode"]) || $_GET["mode"] !== "development") {
      return false;
    }
  }
  http_response_code(423);

  $backtrace  = debug_backtrace();
  $caller     = array_shift($backtrace);

  if (isset($caller["line"])) {
    header("Line: " . $caller["line"]);
    header("File: " . substr($caller["file"], strrpos($caller["file"], '/') + 1));
  }

  if (is_array($data)) {
    header("Content-type:application/json");
    echo json_encode([
      "mode" => "fast_dump",
      "data" => $data
    ]);
    exit();
  } else if(
    (str_contains($data,"SELECT") && str_contains($data,"FROM"))
    ||
    (str_contains($data,"INSERT") && str_contains($data,"INTO"))
    ||
    (str_contains($data,"DELETE") && str_contains($data,"FROM"))
    ||
    (str_contains($data,"UPDATE") && str_contains($data,"SET"))
    ||
    (str_contains($data,"ALTER") && str_contains($data,"TABLE"))
  ) {
    echo "<pre>";
    echo $data;
    exit();
  }
  var_dump($data);
  exit();
}
