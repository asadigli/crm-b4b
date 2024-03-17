<?php
defined('BASEPATH') OR exit('No direct script access allowed');

defined("MAX_EXEC_TIME") OR define("MAX_EXEC_TIME", 900);

defined("MEMORY_LIMIT") OR define("MEMORY_LIMIT", "-1");

defined("MANAGER_DB") OR define("MANAGER_DB", "manager_db");
defined("LOCAL_DB") OR define("LOCAL_DB", "local_db");

define("STATUS_ACTIVE", "1");
define("STATUS_DEACTIVE", "0");

define("CLICKABLE", "1");
define("UNCLICKABLE", "0");

define("EXCEL_KEYWORD", "excel");

define("STATUS_ACCEPTED","accepted");
define("STATUS_FINISHED","finished");
define("STATUS_PENDING","pending");
define("STATUS_CANCELLED","cancelled");
define("STATUS_DRAFTED","drafted");
define("STATUS_PARTIALLY_PAID","partially_paid");
define("STATUS_ORDERS_LIST_ALL", "orders_list_all");
define("STATUS_NEW_ORDERS", "status_new_orders");

define("REPLACE_ORDER_AMOUNT", "[]");

define("ROLE_DEVELOPER", "developer");
define("ROLE_MAIN_ADMIN", "main_admin");
define("ROLE_ADMIN", "admin");
define("ROLE_SELLER", "seller");

define("STATUS_NO", "no");
define("STATUS_YES", "yes");


define("OEM_SPRTR", "AVHSPRTAVH");
define("ID_SPRTR", "|ID|");
define("DASH_SPRT", "-");
define("LINE_SPRT", "||");

// ETS => Entry Token Status
define("ETS_IDLE", "0x01");
define("ETS_RESERVED", "0x02");
define("ETS_EXPIRED", "0x03");

define("SEARCH_TYPE_QUICK", "quick");
define("SEARCH_TYPE_FULL", "full");

define("AVA_PRODUCT_DELIVERY_TIME", "1-2");
define("KERBA_AUTO_PRODUCT_DELIVERY_TIME", "2-3");
define("RONAX_PRODUCT_DELIVERY_TIME", "20");
define("RONAX_PRODUCT_DELIVERY_TIME_2", "15-30");
define("STORE_PRODUCT_DELIVERY_TIME", "60-90");

define("PRODUCT_RESOURCE_KERBA_AUTO", "kerba_auto");
define("PRODUCT_RESOURCE_AVA", "ava");
define("PRODUCT_RESOURCE_AVA_CACHE", "AVA_CACHE");
define("PRODUCT_RESOURCE_RONAX", "ronax");
define("PRODUCT_RESOURCE_STORE", "store");
define("PRODUCT_RESOURCE_STORE_DATA", "store_data");


define("CURRENCY_EUR_ID", "2");
define("CURRENCY_AZN_ID", "1");

define("CURRENCY_EUR", "EUR");
define("CURRENCY_AZN", "AZN");
define("CURRENCY_USD", "USD");
define("DEFAULT_AVA_CURRENCY_ID", 20);

define("STOCK_SHOW_MIN_QUANTITY", 0);
define("STOCK_SHOW_MAX_QUANTITY", 5);

define("STOCK_AVA_HAS_QUANTITY", 10);
define("STOCK_AVA_HAS_NOT_QUANTITY", 0);

define("CURRENCY_CACHED_FILE", "b4b_cached_currencies");

define("WAREHOUSE_GANJA_ID", 2);
define("WAREHOUSE_BAKU_ID", 1);
define("WAREHOUSE_BAKU_2_ID", 3);

define("B4B_CART_FILTER_TYPE_GROUP", "group");
define("B4B_CART_FILTER_TYPE_ALL", "all");
define("B4B_CART_FILTER_TYPE_NO_STOCK", "no_stock");

define("RESTRICTED_ORDER_CODE", "T20232019");

define("RONAX_PRICE_RATE", 0.6);

define("MAX_AMOUNT_ORDER_LIMIT_MESSAGE", "max_order_amount_warning_message");
define("MINIMUM_OEM_PRODUCT_AMOUNT", "b4b_minimum_oem_product_amount");

define("FILTER_STOCK_BAKU_ASC", "stock_baku_asc");
define("FILTER_STOCK_BAKU_DESC", "stock_baku_desc");
define("FILTER_STOCK_BAKU_2_ASC", "stock_baku_2_asc");
define("FILTER_STOCK_BAKU_2_DESC", "stock_baku_2_desc");
define("FILTER_STOCK_GANJA_ASC", "stock_ganja_asc");
define("FILTER_STOCK_GANJA_DESC", "stock_ganja_desc");
define("FILTER_CART_ASC", "cart_asc");
define("FILTER_CART_DESC", "cart_desc");
define("FILTER_PRICE_EUR_ASC", "price_eur_asc");
define("FILTER_PRICE_EUR_DESC", "price_eur_desc");
define("FILTER_PRICE_AZN_ASC", "price_azn_asc");
define("FILTER_PRICE_AZN_DESC", "price_azn_desc");
define("FILTER_BRAND_NAME_ASC", "brand_name_asc");
define("FILTER_BRAND_NAME_DESC", "brand_name_desc");
define("FILTER_PRODUCT_NAME_ASC", "product_name_asc");
define("FILTER_PRODUCT_NAME_DESC", "product_name_desc");

define("B2B_SHOW_OEM_COUNT", 3);
define("MANAGER_SHOW_OEM_COUNT", 100);

define("WAREHOUSE_BAKU", "1");
define("WAREHOUSE_GANJA", "2");


define("PRODUCT_API_PB_SEARCH_TYPE", "pb");

define("GROUP_CONFIG_PRODUCT_RESOURCE", "product_resource");
