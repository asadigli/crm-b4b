<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*
* Database table names
*
*/

$prefix = "avh_";

/*
* Demo tables
*/
$local_table_names = [

  "cached_products" => "avh_cached_products",

  "cached_product_codes" => "avh_cached_product_codes",

  "cached_history" => "avh_cached_history",

  "cached_customer_accounts" => "avh_cached_customer_accounts",

  "cached_invoices" => "avh_cached_invoices",

  "cached_currencies" => "avh_cached_currencies",

  "cached_customers" => "avh_cached_customers",

  "cached_models" => "avh_cached_models",

  "cached_carbrands" => "avh_cached_carbrands",

  "cached_cashboxes" => "avh_cached_cashboxes",

  "cached_product_brands" => "avh_cached_product_brands",
];

/*
* Remote tables
*/
$remote_table_names = [
  "products"                  => "LG_001_ITEMS",

  "product_prices"            => "LG_001_PRCLIST",

  "product_quantities"        => "LG_001_01_STINVTOT",

  "product_quantities_based"  => "LG_001_02_GNTOTST",

  // "report_list" => "LG_001_02_CLFLINE",

  "invoices"                  => "LG_001_02_INVOICE",

  "invoices_details"          => "LG_001_02_STLINE",

  "currencies"                => "L_CURRENCYLIST",

  "currency_values"           => "L_DAILYEXCHANGES",

  "customer_accounts"         => "LG_001_02_CLFLINE",

  "warehouses"                => "L_CAPIWHOUSE",

  "customer_debts"            => "LG_001_02_CLFICHE",

  "customers"                 => "LG_001_CLCARD",

  "cashboxes"                 => "LG_001_KSCARD",

  "payments"                  => "LG_001_02_KSLINES",

];

$config["db_local_tables"] = $local_table_names;
$config["db_remote_tables"] = $remote_table_names;
