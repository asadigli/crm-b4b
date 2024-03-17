<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# # # # BPM MODULE
$config["endpoints_list"] = [
  # - System users
  "system_users" => "manager/users/list",
  "system_users_groups" => "manager/users/groups",
  "system_users_roles" => "manager/users/roles",
  "system_users_edit" => "manager/users/$1/edit",
  "system_users_edit_password" => "manager/users/$1/edit-password",
  "system_users_add" => "manager/users/add",
  "system_users_delete" => "manager/users/$1/delete",
  "system_users_order_groups" => "manager/users/order-groups",
  "system_users_edit_group" => "manager/users/$1/edit-group",

  "search_logs" => "manager/search-logs",
  "search_logs_only_customers" => "manager/search-logs/only-customers",

  # - Auth
  "auth_login" => "manager/auth/login",
  "auth_login_with_token" => "manager/auth/login/token",

  "entry_list" => "manager/b4b/entry/list",
  "entry_properties" => "manager/b4b/entry/properties",
  "entry_add" => "manager/b4b/entry/add",
  "entry_delete" => "manager/b4b/entry/$1/delete",
  "entry_stock_update" => "manager/b4b/entry/$1/stock-show",
  "entry_store_active" => "manager/b4b/entry/$1/store-active",
  "entry_detail_update" => "manager/b4b/entry/$1/edit-detail",
  "entry_limit_update" => "manager/b4b/entry/$1/entry-limit",
  "entry_edit_password" => "manager/b4b/entry/$1/edit-password",
  "entry_edit_properties" => "manager/b4b/entry/$1/edit-properties",
  "entry_add_customer" => "manager/b4b/entry/$1/add-customer",
  "entry_edit_is_blocked" => "manager/b4b/entry/$1/edit-is-blocked",
  # Configurations

  # - System users
  "configs" => "manager/configs/list",
  "configs_edit" => "manager/configs/$1/edit",
  "configs_add" => "manager/configs/add",
  "configs_delete" => "manager/configs/$1/delete",
  "configs_status" => "manager/configs/$1/status",
  "configs_properties" => "manager/configs/properties",
    # - caches
  "configurations_integration_caches" => "configurations/integration-caches",
  "configurations_integration_caches_warehouses" => "configurations/integration-caches/warehouses",

  "integration_cache_refresh" => "caches/$1/manual/refresh",
  "caches_history" => "manager/caches/history",

  "supervisors_all" => "manager/supervisors/all",
  "supervisors_add" => "manager/supervisors/add",
  "supervisors_edit" => "manager/supervisors/edit",

  "customers" => "manager/customers",
  "customers_list" => "manager/customers/list",
  "city_list" => "manager/customers/city-list",
  "customers_account" => "manager/customers/$1/account",
  "customers_account_details" => "manager/customers/$1/account/$2/details",
  "customers_edit_max_allowed_order_limit" => "manager/customers/$1/edit-max-allowed-order-limit",
  "customers_edit_max_order_limit" => "manager/customers/$1/edit-max-order-limit",
  "customers_edit_has_order_limit" => "manager/customers/$1/edit-has-order-limit",
  "customers_edit_is_blocked" => "manager/customers/$1/edit-is-blocked",

  "invoices" => "manager/customers/invoices",
  "invoices_in_details" => "manager/customers/invoices-in-details",

  // "dashboard_b4b_onlines" => "manager/dashboard/onlines",
  // "dashboard_monthly_onlines" => "manager/reports/daily-onlines",
  "dashboard_reports" => "manager/reports/dashboard",

  "products_search" => "manager/products/search",
  "product_brands" => "manager/products/properties/brands",
  "product_car_brands" => "manager/products/properties/car-brands",
  "products_edit_price" => "manager/products/$1/edit-price",
  "products_discount_price" => "manager/products/$1/discount-price",
  "products_b4b_hide_price" => "manager/products/$1/hide-price",
  "products_apply_discount" => "manager/products/apply-discount",
  "products_hide_price" => "manager/products/hide-prices",
  "product_resources" => "manager/products/properties/product-resources",
  "products_comments" => "manager/products/comments-list",
  "products_search_comments_manager" => "manager/products/product-comments",
  "products_offered_price" => "manager/products/price-offers-list",
  "entries_list" => "manager/products/entries-list",
  "products_search_offered_price" => "manager/products/product-price-offers",
  "products_edit_is_new_from_warehouse" => "manager/products/$1/edit-is-new-from-warehouse",

    # - imports
  "products_imports_add" => "manager/products/imports/add",
  "products_imports" => "manager/products/imports",
  "products_imports_delete" => "manager/products/imports/$1/delete",
  "products_discount_packages" => "manager/products/discount-packages",
  "products_discount_packages_delete" => "manager/products/discount-packages/$1/delete",

  "static_error_logs" => "manager/static/error-logs",
  "static_error_logs_paths" => "manager/static/error-logs/paths",

  "order_group_list" => "manager/order-groups/list",
  "order_group_add" => "manager/order-groups/add",
  "order_group_delete" => "manager/order-groups/$1/delete",
  "order_group_detail" => "manager/order-groups/$1/detail",
  "order_group_warehouses" => "manager/order-groups/warehouses",

  #Orders
  "orders" => "manager/b4b/orders",
  "orders_details" => "manager/b4b/orders/$1/details",
  "orders_returns" => "manager/b4b/orders/returns",
  "orders_returns_details" => "manager/b4b/orders/returns/$1/details",
  "orders_statuses" => "manager/b4b/orders/statuses",
  "orders_status_edit" => "manager/b4b/orders/$1/edit-status",
  "orders_transfer_order" => "manager/b4b/orders/$1/transfer-order",
  // "orders_status_confirm" => "manager/b4b/orders/$1/status-confirm",
  // "orders_status_finish" => "manager/b4b/orders/$1/status-finish",
  // "orders_status_cancel" => "manager/b4b/orders/$1/status-cancel",

  # Folders
  "orders_folders" => "manager/b4b/orders/folders",
  "orders_folders_list" => "manager/b4b/orders/folders/list",
  "orders_folders_add" => "manager/b4b/orders/folders/add",
  "orders_folders_edit_name" => "manager/b4b/orders/folders/$1/edit-name",
  "orders_folders_edit_description" => "manager/b4b/orders/folders/$1/edit-description",
  "orders_folders_delete" => "manager/b4b/orders/folders/$1/delete",
  "orders_folders_edit_is_active" => "manager/b4b/orders/folders/$1/edit-is-active",
    # - Folder order operations
  "orders_folders_add_order" => "manager/b4b/orders/folders/$1/add-order",
  "orders_folders_remove_order" => "manager/b4b/orders/folders/$1/remove-order",
  "orders_folders_orders_list" => "manager/b4b/orders/folders/$1/orders-list",


  "banners_list" => "manager/banners/all",
  "banners_add" => "manager/banners/add",
  "banners_edit" => "manager/banners/edit",
  "banners_delete" => "manager/banners/$1/delete",
  "cached_currencies" => "manager/currencies",
  "currencies_list" => "manager/currencies/list",

  "sliders_list" => "manager/b4b/sliders",
  "sliders_add" => "manager/b4b/sliders/add",
  "sliders_edit" => "manager/b4b/sliders/edit",
  "sliders_delete" => "manager/b4b/sliders/$1/delete",

  "brand_sliders_list" => "manager/b4b/brand-sliders",
  "brand_sliders_add" => "manager/b4b/brand-sliders/add",
  "brand_sliders_edit" => "manager/b4b/brand-sliders/edit",
  "brand_sliders_delete" => "manager/b4b/brand-sliders/$1/delete",

  "b4b_news" => "manager/b4b/news-popup",
  "b4b_news_types" => "manager/b4b/news-popup/types",
  "b4b_news_add" => "manager/b4b/news-popup/add",
  "b4b_news_delete" => "manager/b4b/news-popup/$1/delete",
  "b4b_news_edit" => "manager/b4b/news-popup/$1/edit",
  "warehouses" => "manager/warehouses",

  "sale_invoices" => "manager/invoices/sales",
  "purchase_invoices" => "manager/invoices/purchases",
  "invoices_purchases_details" => "manager/invoices/purchases/$1/details",
  "invoices_sales_details" => "manager/invoices/sales/$1/details",

  "daily_sales" => "manager/invoices/daily-sales",
];
