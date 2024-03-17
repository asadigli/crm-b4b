<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# Caches
Route::get("caches", "caches/all/index");
Route::prefix("caches", function() {
  Route::get("warehouses", "caches/all/warehouses");
  Route::put("refresh", "caches/All/refresh");
});
