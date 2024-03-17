<?php
defined('BASEPATH') OR exit('No direct script access allowed');

# Products
Route::prefix("products", function() {
  Route::get("search", "products/search/index");
  Route::get("list-live","products/search/live");

  Route::prefix("search",function(){
    Route::put("update/type","products/search/updateType");
    Route::get("tecdoc-crosses", "products/search/tecdocCrosses");
    Route::get("comments", "products/search/comments");


    Route::post("add-comments", "products/search/addComments");
    Route::post("add-price-offer", "products/search/addPriceOffer");
  });
  #Properties
  Route::prefix("properties", function() {
    Route::get("brands","products/properties/brands");
    Route::get("car-brands","products/properties/carBrands");
  });
});
