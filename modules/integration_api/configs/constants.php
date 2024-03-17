<?php
defined('BASEPATH') OR exit('No direct script access allowed');

defined("MAX_EXEC_TIME") OR define("MAX_EXEC_TIME", 900);

defined("MEMORY_LIMIT") OR define("MEMORY_LIMIT", "-1");

defined("MANAGER_DB") OR define("MANAGER_DB", "manager_db");
defined("LOCAL_DB") OR define("LOCAL_DB", "local_db");
defined("REMOTE_DB") OR define("REMOTE_DB", "remote_db");

define("STATUS_ACTIVE", "1");
define("STATUS_DEACTIVE", "0");

define("STATUS_YES", "yes");
define("STATUS_NO", "no");

define("REQUEST_TYPE_AUTO", "auto");
define("REQUEST_TYPE_MANUAL", "manual");

### CACHE TYPES

define("INTEG_TYPE_PRODUCTS", "products");
define("INTEG_TYPE_CUSTOMERS", "customers");
define("INTEG_TYPE_CUSTOMER_ACCOUNT", "customer-accounts");
define("INTEG_TYPE_INVOICES", "invoices");
define("INTEG_TYPE_CURRENCIES", "currencies");
define("INTEG_TYPE_MODELS", "models");
define("INTEG_TYPE_CARBRANDS", "carbrands");
define("INTEG_TYPE_CASHBOXES", "cashboxes");
define("INTEG_TYPE_PRODUCT_BRANDS", "product-brands");

### CACHE TYPES

define("OEM_SPRTR", "AVHSPRTAVH");
define("ID_SPRTR", "|ID|");
define("DASH_SPRT", "-");
define("LINE_SPRT", "||");

define("CURRENCY_EUR", "EUR");
define("CURRENCY_AZN", "AZN");
define("CURRENCY_USD", "USD");
define("DEFAULT_AVA_CURRENCY_ID", 20);

define("CURRENCY_CACHED_FILE", "b4b_cached_currencies");
