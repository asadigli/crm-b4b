<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Route::get("orders", "orders/all/index");
Route::prefix("orders",function(){
  Route::get("list-live", "orders/all/live");
  Route::get("{id}/details", "orders/details/index/$1");

  Route::post("is-approve", "orders/add/isApprove");
  Route::put("confirm-approve", "orders/edit/confirmApprove");

  Route::get("returns", "orders/returns/all/index");
  Route::prefix("returns", function() {
    Route::get("list-live", "orders/returns/all/live");
    Route::get("{id}/details", "orders/returns/details/index/$1");
    Route::post("add", "orders/returns/add/action");
  });
});
