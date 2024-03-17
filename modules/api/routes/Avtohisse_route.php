<?php
defined('BASEPATH') OR exit('No direct script access allowed');


Route::prefix("ah", function(){

  # Products
  Route::prefix("products", function() {
    Route::get("list", "avtohisse/products/all/list");
  });

});
