<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$autoload["libraries"] = [
  "Auth",
  "Flash",
  "Api",
  "Services"
];


$autoload["helpers"] = [
  "custom",
  "default",
  "validate",
];

$autoload["configs"] = [
  "navbar",
  "secrets",
  "endpoints",
  "company_info",
  "constants",
  "footer",
  "js_langs",
  "limits",
  "custom"
];
