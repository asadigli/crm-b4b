<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config["endpoints_list"] = [
  #Auth
  "b4b_login" => "b4b/auth/login",

  #Cart
  "cart_add" => "b4b/cart/add",
  "cart_count" => "b4b/cart/count",
  "cart" => "b4b/cart",
  "cart_edit_quantity" => "b4b/cart/$1/edit-quantity",
  "cart_edit_note" => "b4b/cart/$1/edit-note",
  "cart_delete" => "b4b/cart/$1/delete",
  "cart_delete_items" => "b4b/cart/delete-items",
  "cart_properties_filter_list" => "b4b/cart/properties/filter-list",

  #Orders
  "orders" => "b4b/orders",
  "orders_details" => "b4b/orders/$1/details",
  "orders_returns" => "b4b/orders/returns",
  "orders_returns_details" => "b4b/orders/returns/$1/details",
  "orders_return_add" => "b4b/orders/returns/add",
  "orders_is_approve" => "b4b/orders/is-approve",
  "orders_confirm_approve" => "b4b/orders/confirm-approve",

  "warehouses" => "b4b/warehouses",

  # Products
  "product_brands" => "b4b/products/properties/brands",
  "product_car_brands" => "b4b/products/properties/car-brands",
  "product_banners" => "b4b/products/properties/banners",
  "products_search" => "b4b/products/search",
  "products_main" => "b4b/products/main",
  "products_search_tecdoc_crosses" => "b4b/products/search/tecdoc-crosses",
  "products_search_add_comments" => "b4b/products/search/add-comment",
  "products_search_comments" => "b4b/products/search/comments",
  "products_search_add_price_offer" => "b4b/products/search/add-price-offer",
  "products_new_from_warehouses" => "b4b/products/new-from-warehouses",


  "file_export_add_to_history" => "b4b/file-export/add-to-history",
  # Promotions
  // "action_list" => "b4b/actions/list",
  // "action_details" => "b4b/actions/$1/details",
  "promotion_list" => "b4b/promotions/list",
  "promotion_details" => "b4b/promotions/$1/details",

  "news_popup_list" => "b4b/news-popup",

  # News
  "news_list" => "b4b/news/list",
  "news_details" => "b4b/news/$1/details",

  # Accounts
  "customer_account" => "b4b/customers/$1/account",
  "customer_account_details" => "b4b/customers/$1/account/details",
  "cached_currencies" => "manager/currencies",
  "customer_account_return" => "b4b/customers/return-product",

  "onlines" => "b4b/onlines",
  "configs" => "b4b/configs",

  "banners" => "b4b/banners",
  "sliders" => "b4b/sliders",
  "brand_sliders" => "b4b/brand-sliders",
];
