<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*
* Database table names
*
*/
$prefix = "sa_";
/*
* Demo tables
*/
$local_table_names = [
    "product_brands" => "sa_product_brands",
];
/*
* Remote tables
*/
$remote_table_names = [
  //
];

$config["db_local_tables"] = $local_table_names;
$config["db_remote_tables"] = $remote_table_names;
