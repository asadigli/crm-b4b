<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config["pages"] = [
  [
    "path" => "",
    "name" => "Home page",
    "icon" => "fa-solid fa-house",
    "setup" => true,
  ],
  [
    "path" => "products/search",
    "name" => "Search",
    "icon" => "fa-solid fa-magnifying-glass",
    "setup" => true,
  ],
  [
    "path" => "account",
    "name" => "Customer account",
    "icon" => "fa-solid fa-receipt",
    "setup" => true,
  ],
  [
    "path" => "cart",
    "name" => "Cart",
    "icon" => "fa-solid fa-cart-shopping",
    "setup" => true,
  ],
  [
    "path" => "orders",
    "name" => "Orders",
    "icon" => "fa-solid fa-indent",
    "setup" => true,
  ],
  [
    "path" => "orders/returns",
    "name" => "Return",
    "icon" => "fa-solid fa-rotate-left",
    "setup" => true,
  ],
  [
    "path" => "promotions",
    "name" => "Promotions",
    "icon" => "fa-solid fa-rectangle-ad",
    "setup" => true,
  ],
  [
    "path" => "no_path",
    "name" => "Excel export",
    "icon" => "fa-solid fa-file-export",
    "setup" => false,
  ],
  [
    "path" => "news",
    "name" => "Information",
    "icon" => "fa-solid fa-circle-info",
    "setup" => true,
  ],
];
