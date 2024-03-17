<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# Cart
Route::get("cart", "cart/all/index");
Route::prefix("cart", function() {

  Route::prefix("properties", function() {
    Route::get("filter-list", "cart/properties/filters/list");
  });

  Route::get("list-live", "cart/all/live");
  Route::get("count-live", "cart/all/cartCountLive");
  Route::post("add", "cart/add/index");
  Route::put("{id}/edit-quantity", "cart/edit/editQuantity/$1");
  Route::put("{id}/edit-note", "cart/edit/editNote/$1");
  Route::delete("{id}/delete", "cart/edit/delete/$1");
  Route::delete("delete-items", "cart/edit/deleteItems");
});
