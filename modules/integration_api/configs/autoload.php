<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$autoload["configs"] = [
  "constants",
  "tables",
  "secrets",
  "cache_types",
  "special_codes",
  "groups",
];


$autoload["libraries"] = [
  "Status_codes",
];


$autoload["helpers"] = [
  "custom",
  "default",
  "validate",
];
