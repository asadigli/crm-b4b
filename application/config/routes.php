<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$module = $this->config->item("current_module_name");
if ($module === "api") {
  $prefixes = $this->config->item("service_versions");
} else {
  $prefixes = $this->config->item("languages");
}

$route["default_controller"] = $module."/home";
$route["404_override"] = $module."/home/errorPage";
$route["translate_uri_dashes"] = TRUE;

foreach ($prefixes as $key => $lang) {
  if ($lang) {
    $route[$lang] = $module."/home";
  }
}

$route = Route::map($route,$prefixes,$module);

// for ($i=1; $i <= 9; $i++) {
//   $route[rtrim(str_repeat("(:any)/",$i),"/")] = $module."/errorhandling/notFound";
// }
for ($i=1; $i <= 10; $i++) {
  $route[rtrim(str_repeat("(:any)/",$i),"/")] = $module."/errorhandling/notFound";
}
if (substr_count($_SERVER["REQUEST_URI"],"/") > 10) {
  $route[str_replace("//","/",ltrim($_SERVER["REQUEST_URI"],"/"))] = $module."/errorhandling/notFound";
}


if (isset($_GET["all-routes"])) {
  if(in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1','::1'])){
    header("Content-type:application/json");
    echo json_encode($route);
    die;
  }
}
