<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config["customer_accounts_page_load_limit"]  = 200;
$config["products_page_load_limit"]           = 100;
$config["customers_page_load_limit"]          = 200;
$config["orders_page_load_limit"]             = 200;
$config["b4b_online_time"]                    = 60;

$config["manager_orders_page_load_limit"] = 200;

$config["remember_me_time"] = 3600*24*30; // 30 days
$config["remember_key_name"] = "ma_rememberkey";
$config["token_reliability"] = 60*60*24*2; // 48 hours
$config["manager_search_logs_limit"] = 10;