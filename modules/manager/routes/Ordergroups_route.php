<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Route::get("order-groups","order_groups/all");

Route::prefix("order-groups",function(){
  Route::get("list","order_groups/all/list");
  Route::get("warehouses","order_groups/all/warehouses");
  Route::post("add","order_groups/all/add");
  Route::put("{id}/edit-detail","order_groups/all/detail/$1");
  Route::delete("{id}/delete","order_groups/all/delete/$1");
});
