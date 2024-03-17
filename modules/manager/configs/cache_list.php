<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config["cache_list"] = [
  [
    "key" => "products",
    "name" => "Products",
    "setup" => true,
    "roles" => ["developer","main_admin","admin","manager"],
  ],
  [
    "key" => "customer-accounts",
    "name" => "Customer accounts",
    "setup" => true,
    "roles" => ["developer","main_admin","admin","manager"],
  ],
  [
    "key" => "invoices",
    "name" => "Invoices",
    "setup" => true,
    "roles" => ["developer","main_admin","admin","manager"],
  ],
  [
    "key" => "customers",
    "name" => "Customers",
    "setup" => true,
    "roles" => ["developer","main_admin","admin","manager"],
  ],
  [
    "key" => "currencies",
    "name" => "Currencies",
    "setup" => true,
    "roles" => ["developer","main_admin","admin","manager"],
  ],
  [
    "key" => "models",
    "name" => "Car models",
    "setup" => true,
    "roles" => ["developer","main_admin","admin","manager"],
  ],
  [
    "key" => "carbrands",
    "name" => "Car brands",
    "setup" => true,
    "roles" => ["developer","main_admin","admin","manager"],
  ],
  [
    "key" => "product-brands",
    "name" => "Brands",
    "setup" => true,
    "roles" => ["developer","main_admin"],
  ],
  [
    "key" => "cashboxes",
    "name" => "Cashboxes",
    "setup" => true,
    "roles" => ["developer","main_admin","admin","manager"],
  ],

];
