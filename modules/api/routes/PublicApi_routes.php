<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Route::prefix("pb",function(){
  // Route::prefix("b4b", function() {
    // Route::get("products/search", "b4b/products/search/index");
  // });
  Route::get("products", "pb/products/all/list");
  Route::prefix("products", function() {
    Route::prefix("properties", function() {
      Route::get("car-brands", "pb/products/properties/carBrands");
    });
  });
});
