<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$autoload["configs"] = [
  "constants",
  "tables",
  "secrets",
  "special_codes",
  "limits",
  "order_emails",
  "cache_list",
  "b4b_order_groups",
  "default",
  "endpoints",
  "groups",
];


$autoload["libraries"] = [
  "Dtcisct",
  "Api",
  "Cdn",
  "PHPExcel",
  "Mail",
  "Ronax",
  "Services",
];


$autoload["helpers"] = [
  "custom",
  "default",
  "data_existence",
  "validate",
  "invoice_generator",
];
