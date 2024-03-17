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
  "b4b_cart" => [
    "resources" => [
      "ava" => "0x001",
      "ronax" => "0x002",
      "store" => "0x003",
    ],

  ],
  "b4b_order_groups" => [
    "suppliers" => [
      # this should be same with product and cart resources
      "ronax" => "0x002",
      "store" => "0x003",
    ],
  ],
  "b4b_orders" => [
    "statuses" => [
      "pending" => "0x001",
      "confirmed" => "0x002",
      "on_the_way" => "0x003",
      "shipped" => "0x004",
      "partially_shipped" => "0x005",
      "finished" => "0x006",
      "canceled" => "0x007",
      "transfered" => "0x008",
    ],
  ],
  "b4b_return_orders" => [
    "statuses" => [
      "0" => "returned",
    ]
  ],
  "b4b_entries" => [
    "statuses" => [
      "idle" => "0x01",
      "reserved" => "0x02",
      "expired" => "0x03",
    ],

    "login_attempts" => [
      "statuses" => [
        "success" => "0x001",
        "failed"  => "0x002",
      ],

      "types" => [
        "manual"  => "0x001",
        "auto"    => "0x002",
      ]
    ]
  ],
  "entry_tokens" => [
    "statuses" => [
      "idle" => "0x01",
      "reserved" => "0x02",
      "expired" => "0x03",
    ]
  ],
  "b4b_customers" => [
    "statuses" => [
      "allowed" => "0x001"
    ]
  ],

  "customer_accounts" => [
    "tr_codes" => [
      "payment" => 1,
    ],
  ],
  "configs" => [
    "types" => [
      "string"   => "0x001T",
      "integer"  => "0x002T",
      "boolean"  => "0x003T",
      "array"    => "0x004T",
    ],
    "groups" => [
      "delivery_date"   => "0x001G",
      "contact_email"  => "0x002G",
      "order_email"  => "0x003G",
      "contact_whatsapp" => "0x004G",
      "other" => "0x005G",
      "product_resource" => "0x006G",
    ],
    "resources" => [
      "b4b"     => "0x001R",
      "manager" => "0x002R",
    ]
  ],
  "error_logs" => [
    "paths" => [
      "application" => "application",
      "api" => "api",
      "b4b" => "b4b",
      "manager" => "manager",
      "integration_api" => "integration_api",
      "landing" => "landing",
      "landing_api" => "landing_api",
    ],
  ],
  "logs" => [
    "modules" => [
      "b4b"    => "0x01",
      "manager" => "0x02",
    ]
  ],


  "system_users" => [
    "roles" => [
      "developer"     => "0x0005",
      "main_admin"    => "0x0002",
      "admin"         => "0x0001",
      "manager"       => "0x0004",
      "warehouseman"  => "0x0003",
    ]
  ],

  "b4b_slides" => [
    "types" => [
      "main" => "0x01",
      "brands" => "0x02",
      ]
  ],

  "app_status_codes" => [
    "errors" => [
      "group_not_found" => [
        "code" => "SE0001",
        "description" => "Order group not found"
      ],
    ],
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
  ],

  "pop_up" => [
    "types" => [
      "news" => "0x01",
      "promotion" => "0x02",
    ]
  ]
];
