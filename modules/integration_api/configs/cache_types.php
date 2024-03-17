<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config["cache_types"] = [
  "products" => [
    "show" => [
      "manager" => false,
    ],
    "roles" => [],
    "execute" => [
      REQUEST_TYPE_AUTO => true,
      REQUEST_TYPE_MANUAL => true,
    ],
    "body" => [
      "limit_hour" => 0.1 //0.1
    ],
    "table" => "cached_products",
    "auto-refresh-time" => [
      "month_days"  => "*",
      "week_days"   => "*",
      "hours"       => "*",
      "minutes"     => [0,4,8,12,16,20,24,28,32,36,40,44,48,52,58],
    ],
    "cache_for_all_time" => [
      "start_time" => "07:59",
      "end_time" => "08:01"
    ],
    "no_cache_time" => [
      "start_time" => "00:00",
      "end_time" => "07:59"
    ],
  ],
  "customers" => [
    "show" => [
      "manager" => false,
    ],
    "roles" => [],
    "execute" => [
      REQUEST_TYPE_AUTO => true,
      REQUEST_TYPE_MANUAL => true,
    ],
    "body" => [
      "limit_hour" => 24 + 0.5 //0.1
    ],
    "table" => "cached_customers",
    "auto-refresh-time" => [
      "month_days"  => "*",
      "week_days"   => "*",
      "hours"       => [0],
      "minutes"     => [6],
    ],
  ],
  "customer-accounts" => [
    "show" => [
      "manager" => false,
    ],
    "roles" => [],
    "execute" => [
      REQUEST_TYPE_AUTO => true,
      REQUEST_TYPE_MANUAL => true,
    ],
    "body" => [
      "limit_hour" => 0.1 //0.1
    ],
    "table" => "cached_customer_accounts",
    "auto-refresh-time" => [
      "month_days"  => "*",
      "week_days"   => "*",
      "hours"       => "*",
      "minutes"     => [1,5,9,13,17,21,25,29,33,37,41,45,49,53,57],
    ],
    "cache_special_dates" => [
      "cache_at_from" => "06:55",
      "cache_at_to" => "07:00",
      "start_date" => date("Y-m-d", strtotime(" -1 month")),
      "end_date" => date("Y-m-d")
    ],
  ],
  "invoices" => [
    "show" => [
      "manager" => false,
    ],
    "roles" => [],
    "execute" => [
      REQUEST_TYPE_AUTO => true,
      REQUEST_TYPE_MANUAL => true,
    ],
    "body" => [
      "limit_hour" => 0.1 //0.1
    ],
    "table" => "cached_invoices",
    "auto-refresh-time" => [
      "month_days"  => "*",
      "week_days"   => "*",
      "hours"       => "*",
      "minutes"     => [2,6,11,15,19,23,27,31,35,39,43,47,51,55,59],
    ],
    "cache_special_dates" => [
      "cache_at_from" => "07:00",
      "cache_at_to" => "07:05",
      "start_date" => date("Y-m-d", strtotime(" -1 month")),
      "end_date" => date("Y-m-d")
    ],
  ],
  "currencies" => [
    "show" => [
      "manager" => false,
    ],
    "roles" => [],
    "execute" => [
      REQUEST_TYPE_AUTO => true,
      REQUEST_TYPE_MANUAL => true,
    ],
    "body" => [
      "limit_hour" => 0.6 //0.1
    ],
    "table" => "cached_currencies",
    "auto-refresh-time" => [
      "month_days"  => "*",
      "week_days"   => "*",
      "hours"       => "*",
      "minutes"     => [3,32],
    ],
    "cache-list"  => [
      CURRENCY_CACHED_FILE,
    ],
  ],
  "models" => [
    "show" => [
      "manager" => false,
    ],
    "roles" => [],
    "execute" => [
      REQUEST_TYPE_AUTO => true,
      REQUEST_TYPE_MANUAL => true,
    ],
    "body" => [
      "limit_hour" => 24 * 7 + 0.5 //0.1
    ],
    "table" => "cached_models",
    "auto-refresh-time" => [
      "month_days"  => "*",
      "week_days"   => [1],
      "hours"       => [0],
      "minutes"     => [7],
    ],
    "cache-list"  => [
    ],
  ],
  "carbrands" => [
    "show" => [
      "manager" => false,
    ],
    "roles" => [],
    "execute" => [
      REQUEST_TYPE_AUTO => true,
      REQUEST_TYPE_MANUAL => true,
    ],
    "body" => [
      "limit_hour" => 24 * 7 + 0.5 //0.1
    ],
    "table" => "cached_carbrands",
    "auto-refresh-time" => [
      "month_days"  => "*",
      "week_days"   => [1],
      "hours"       => [0],
      "minutes"     => [8],
    ],
    "cache-list"  => [
    ],
  ],
  "product-brands" => [
    "show" => [
      "manager" => true,
    ],
    "roles" => [],
    "execute" => [
      REQUEST_TYPE_AUTO => true,
      REQUEST_TYPE_MANUAL => true,
    ],
    "body" => [
      "limit_hour" => 24 * 7 + 0.5 //0.1
    ],
    "table" => "cached_product_brands",
    "auto-refresh-time" => [
      "month_days"  => "*",
      "week_days"   => [1],
      "hours"       => [0],
      "minutes"     => [14],
    ],
    "cache-list"  => [
    ],
  ],
  "cashboxes" => [
    "show" => [
      "manager" => false,
    ],
    "roles" => [],
    "execute" => [
      REQUEST_TYPE_AUTO => true,
      REQUEST_TYPE_MANUAL => true,
    ],
    "body" => [
      "limit_hour" => 24.5 //0.1
    ],
    "table" => "cached_cashboxes",
    "auto-refresh-time" => [
      "month_days"  => "*",
      "week_days"   => "*",
      "hours"       => [0],
      "minutes"     => [10],
    ],
    "cache-list"  => [
    ],
  ],
];
