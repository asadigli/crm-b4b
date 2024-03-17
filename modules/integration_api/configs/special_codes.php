<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config["special_codes"] = [
  "products" => [
    "resources" => [
      "ava" => "0x001",
      "ronax" => "0x002",
      "store" => "0x003",
    ],
    "search_types" => [
      "quick" => "0x01",
      "full" => "0x02",
    ],
  ],

  "b4b_orders" => [
    "statuses" => [
      "1" => "shipped",
    ]
  ],
  "b4b_return_orders" => [
    "statuses" => [
      "0" => "returned",
    ]
  ],

  "cached_customer_accounts" => [
    "types" => [
      "payment"               => "0x000P1",
      "debt_first"            => "0x00DF2",
      "debt_second"           => "0x00DS3",
      "credit"                => "0x000C4",
      "currency_difference"   => "0x00CD5",
      "initial"               => "0x000I6",
      "return"                => "0x000R7",
      "sale_invoice"          => "0x00SI8",
      "purchase_invoice"      => "0x00PI9"
    ],

    "warehouses" => [
      "baku"  => "0x0001",
      "ganja" => "0x0002"
    ]
  ]
];
