<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Route::get("configs", "configs/all/index");
Route::prefix("configs", function() {
  Route::get("list", "configs/all/live");
  Route::put("{id}/edit", "configs/edit/index/$1");
  Route::delete("{id}/delete", "configs/edit/delete/$1");
  Route::put("{id}/status", "configs/edit/status/$1");
  Route::post("add", "configs/add");
});
