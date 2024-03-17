<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$autoload["configs"] = [
  "endpoints",
  "secrets",
  "custom",
  "navbar",
  "footer",
  "constants",
  "js_langs",
  "company_info",
  "cache_list",
  "limits",
  "su_roles"
];


$autoload["libraries"] = [
  "Auth",
  "Api",
  "Services"
];


$autoload["helpers"] = [
  "default",
  "validate",
  "custom",
];
