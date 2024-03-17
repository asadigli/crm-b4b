<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config["navbar"] = [
  [
    "setup" => true,
    "is_developer" => false,
    "roles" => ["manager", "admin", "main_admin"],
    "icon" => "fa-solid fa-chart-line",
    "name" => "Home page",
    "path" => "home",
  ],
  [
   "setup" => true,
   "is_developer" => false,
   "roles" => ["manager", "warehouseman","admin", "main_admin"],
   "icon" => "fa-solid fa-box-open",
   "name" => "Orders",
   "path" => "orders"
  ],
  [
    "setup" => true,
    "is_developer" => false,
    "roles" => ["manager", "admin", "main_admin"],
    "icon" => "fas fa-users",
    "name" => "Entries",
    "path" => "entries",
  ],
  [
   "setup" => true,
   "is_developer" => false,
   "roles" => ["manager", "main_admin"],
   "icon" => "fa-solid fa-user-group",
   "name" => "Customers",
   "path" => "customers"
  ],
  [
   "setup" => true,
   "is_developer" => false,
   "roles" => ["main_admin"],
   "icon" => "fa-solid fa-cube",
   "name" => "Products",
   "path" => "javascript:void(0)",
   "childs" => [
     [
       "setup" => true,
       "is_developer" => false,
       "roles" => ["main_admin"],
       // "icon" => "mdi mdi-account menu-icon",
       "name" => "Search",
       "path" => "products/search",
     ],
     [
       "setup" => true,
       "is_developer" => false,
       "roles" => ["main_admin"],
       // "icon" => "mdi mdi-account menu-icon",
       "name" => "Import",
       "path" => "products/imports",
     ],
     [
       "setup" => true,
       "is_developer" => false,
       "roles" => ["main_admin"],
       // "icon" => "mdi mdi-account menu-icon",
       "name" => "Discount packages",
       "path" => "products/discount-packages",
     ],
     [
       "setup" => true,
       "is_developer" => false,
       "roles" => ["main_admin"],
       // "icon" => "mdi mdi-account menu-icon",
       "name" => "Products price offers",
       "path" => "products/price-offers",
     ],
     [
       "setup" => true,
       "is_developer" => false,
       "roles" => ["main_admin"],
       // "icon" => "mdi mdi-account menu-icon",
       "name" => "Products comments",
       "path" => "products/comments",
     ],
   ],
  ],
  [
   "setup" => true,
   "is_developer" => false,
   "roles" => ["main_admin"],
   "icon" => "fa-solid fa-file-invoice",
   "name" => "Invoices",
   "path" => "javascript:void(0)",
   "childs" => [
     [
       "setup" => true,
       "is_developer" => false,
       "roles" => ["main_admin"],
       // "icon" => "fa-solid fa-file-invoice",
       "name" => "Sales",
       "path" => "invoices/sales",
     ],
     [
       "setup" => true,
       "is_developer" => false,
       "roles" => ["main_admin"],
       // "icon" => "fa-solid fa-file-invoice",
       "name" => "Daily sales",
       "path" => "invoices/daily-sales",
     ],
     [
       "setup" => true,
       "is_developer" => false,
       "roles" => ["main_admin"],
       // "icon" => "fa-solid fa-file-invoice",
       "name" => "Purchases",
       "path" => "invoices/purchases",
     ],
   ],
  ],
  [
   "setup" => true,
   "is_developer" => false,
   "roles" => ["admin", "main_admin"],
   "icon" => "fa-solid fa-file-invoice",
   "name" => "Brand report",
   "path" => "javascript:void(0)",
   "childs" => [
     [
       "setup" => true,
       "is_developer" => false,
       "roles" => ["admin", "main_admin"],
       // "icon" => "mdi mdi-account menu-icon",
       "name" => "Brand report",
       "path" => "brand-reports/all",
     ],
     [
       "setup" => true,
       "is_developer" => false,
       "roles" => ["admin", "main_admin"],
       // "icon" => "fa-solid fa-users-gear",
       "name" => "Brand report in details",
       "path" => "brand-reports/in-details"
     ]
    ]
 ],
 [
   "setup" => true,
   "is_developer" => false,
   "roles" => ["developer", "main_admin"],
   "icon" => "fa-solid fa-gear",
   "name" => "B4B slides",
   "path" => "javascript:void(0)",
   "childs" => [
     [
       "setup" => true,
       "is_developer" => false,
       "roles" => ["developer", "main_admin"],
       // "icon" => "mdi mdi-account menu-icon",
       "name" => "Main page",
       "path" => "main-page",
     ],
     [
       "setup" => true,
       "is_developer" => false,
       "roles" => ["developer", "main_admin"],
       // "icon" => "fa-solid fa-users-gear",
       "name" => "Product brands",
       "path" => "product-brands"
     ]
    ]
  ],
  [
    "setup" => true,
    "is_developer" => false,
    "roles" => ["developer", "main_admin"],
    "icon" => "fa-solid fa-newspaper",
    "name" => "Popup news",
    "path" => "news",
  ],
[
  "setup" => true,
  "is_developer" => false,
  "roles" => ["admin", "main_admin"],
  "icon" => "fa-solid fa-gear",
  "name" => "Others",
  "path" => "javascript:void(0)",
  "childs" => [
    [
      "setup" => true,
      "is_developer" => false,
      "roles" => ["main_admin"],
      // "icon" => "mdi mdi-account menu-icon",
      "name" => "System users",
      "path" => "system-users",
    ],
    [
      "setup" => true,
      "is_developer" => false,
      "roles" => ["admin", "main_admin"],
      // "icon" => "fa-solid fa-users-gear",
      "name" => "Supervisors",
      "path" => "supervisors"
    ],
    // [
    //  "setup" => true,
    //  "is_developer" => false,
    //  "roles" => ["admin", "main_admin"],
    //  // "icon" => "fa-solid fa-file-invoice",
    //  "name" => "Slide banners",
    //  "path" => "banners"
    // ],
    [
      "setup" => true,
      "is_developer" => false,
      "roles" => ["main_admin"],
      // "icon" => "mdi mdi-account menu-icon",
      "name" => "Configs",
      "path" => "configs",
    ],
    [
      "setup" => true,
      "is_developer" => false,
      "roles" => ["admin", "main_admin"],
      "name" => "Caches",
      "path" => "caches"
    ],
    [
      "setup" => true,
      "is_developer" => true,
      "roles" => ["main_admin"],
      "name" => "Order groups",
      "path" => "order-groups"
    ],
    [
      "setup" => true,
      "is_developer" => true,
      "roles" => [],
      "name" => "Application error logs",
      "path" => "configurations/error-logs"
    ],
    [
      "setup" => true,
      "is_developer" => true,
      "roles" => [],
      "name" => "System setups",
      "path" => "configurations/system-setups"
    ],
    [
      "setup" => true,
      "is_developer" => false,
      "roles" => [],
      "name" => "Search logs",
      "path" => "search-logs/all"
    ]


  ]
],

];
