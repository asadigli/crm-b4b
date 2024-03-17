<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*
* Baza table adları
*
*/
$prefix = "avh_";

/*
* Local tables
*/
$local_table_names = [

  "b4b_orders" => "cs_product_order",

  "b4b_order_details" => "cs_product_order_list",

  "b4b_return_orders" => "cs_product_reverce",

  "b4b_return_order_details" => "cs_product_reverce_list",

  "b4b_order_statuses" => "cs_order_status",

  // "b4b_user_list" => "cs_user_list",

  "b4b_companies" => "cs_company_list",

  "b4b_entry_tokens" => "avh_b4b_entry_tokens",

  "b4b_login_tokens" => "avh_b4b_login_tokens",

  "b4b_promotions" => "cs_action_list",

  "b4b_searched_keywords" => "cs_search_list",

  "b4b_cart" => "cs_basket_list",

  "b4b_dashboard_products" => "cs_main_products",

  "b4b_news" => "cs_news_list",

  "b4b_onlines" => "avh_b4b_onlines",

  "b4b_curators" => "cs_curator_list",

  "b4b_entry_price_requests" => "avh_b4b_entry_price_requests",

  "b4b_returns" => "cs_reverce",

  "b4b_order_groups" => "avh_b4b_order_groups",

  "b4b_entry_customers" => "avh_b4b_entry_customers",

  "b4b_configs" => "avh_configs",

  "b4b_exports" => "avh_b4b_exports",

  "b4b_slides" => "avh_b4b_slides",

  "b4b_order_folders" => "avh_b4b_order_folders",

  "b4b_order_folder_joints" => "avh_b4b_order_folder_joints",

  "news_popup" => "avh_b4b_news",

  "news_popup_images" => "avh_b4b_news_images",

  "news_popup_subscribes" => "avh_b4b_news_subscribes",



  "warehouses" => "cs_depo_list",

  "cities" => "cs_city_list",

  "brands" => "cs_brend_list",

  "car_brands" => "cs_marka_list",

  "banners" => "cs_banner_list",

  "banner_types" => "cs_banner_type",

  "stock_list" => "cs_stock_list", # old name used

  "su_groups" => "cs_admin_groups",

  "su_order_groups" => "avh_su_order_groups",

  "system_users" => "cs_admin_list",


  "cached_customer_accounts" => "avh_cached_customer_accounts",

  "cached_invoices" => "avh_cached_invoices",

  "cached_products" => "avh_cached_products",

  "b4b_products_comments" => "avh_b4b_product_comments",

  "cached_product_codes" => "avh_cached_product_codes",

  "cached_product_brands" => "avh_cached_product_brands",

  "cached_carbrands" => "avh_cached_carbrands",

  "cached_currencies" => "avh_cached_currencies",

  "cached_cashboxes" => "avh_cached_cashboxes",

  "cached_history" => "avh_cached_history",

  "cached_customers" => "avh_cached_customers",


  "product_import_history" => "avh_product_import_history",

  "product_carbrand_joints" => "avh_product_carbrand_joints",

  "product_dicsount_packages" => "avh_product_dicsount_packages",

  "login_attempts" => "avh_login_attempts",

  "user_remember_tokens" => "avh_su_remember_tokens",

  "logs" => "avh_logs",

  "file_export_history" => "avh_file_export_history",
];

/*
* Remote tables
*/
$remote_table_names = [



];


// $local_table_names = array_map(function($i) use ($prefix) {
//     return $prefix.$i;
// },$local_table_names);
//
//
// $remote_table_names = array_map(function($i) use ($prefix) {
//     return $prefix.$i;
// },$remote_table_names);
//
// $local_table_names = array_map(function($i) use ($prefix) {
//     return $prefix.$i;
// },$local_table_names);



$config["db_local_tables"] = $local_table_names;
$config["db_remote_tables"] = $remote_table_names;
