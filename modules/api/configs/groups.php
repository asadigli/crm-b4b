<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config["temprorary_manager_orders_statuses"] = [
  "pending",
  "confirmed",
  "finished",
  "canceled",
];

$config["temprorary_manager_orders_edit_statuses"] = [
  "pending",
  "confirmed",
  "finished",
  "canceled",
];

$config["permitted_image_exts"] = ["jpeg","png","jpg", "webp"];

$config["excel_extensions"] = [
  "application/vnd.ms-excel",
  "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
  "application/vnd.openxmlformats-officedocument.spreadsheetml.template",
  "application/vnd.ms-excel.sheet.macroEnabled.12",
  "application/vnd.ms-excel.template.macroEnabled.12",
  "application/vnd.ms-excel.addin.macroEnabled.12",
  "application/vnd.ms-excel.sheet.binary.macroEnabled.12",
];

$config["default_discount"] = "15";

$config["product_resources"] = ["ava", "store"];

$config["pop_up_types"] = ["news", "promotion"];
