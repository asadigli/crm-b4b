<?php
defined('BASEPATH') OR exit('No direct script access allowed');

# Products
Route::prefix("products", function() {
  Route::get("search", "products/search/index");
  Route::get("list-live","products/search/live");

  #Comments
  Route::get("comments", "products/comments/all/index");
  Route::prefix("comments", function() {
    Route::get("list-live", "products/comments/all/live");
    Route::get("entries-list", "products/comments/all/entriesList");
  });

  // price offers

  Route::get("price-offers", "products/price_offers/all/index");
  Route::prefix("price-offers", function() {
    Route::get("list-live", "products/price_offers/all/live");
  });
  # Imports
  Route::get("imports", "products/imports/all/index");
  Route::prefix("imports", function() {
    Route::get("list-live", "products/imports/all/live");
    Route::post("add", "products/imports/add/index");

    Route::delete("{id}/delete", "products/imports/all/delete/$1");
  });

  Route::prefix("search",function(){
    Route::put("update/type","products/search/updateType");
    Route::get("tecdoc-crosses", "products/search/tecdocCrosses");
    Route::get("comment-list", "products/search/comments");
    Route::get("offered-price-list", "products/search/priceOffers");
  });
  #Properties
  Route::prefix("properties", function() {
    Route::get("brands","products/properties/brands");
    Route::get("car-brands","products/properties/carBrands");
    Route::get("product-resources","products/properties/productResources");
  });

  Route::prefix("{id}", function() {
    Route::put("edit-price", "products/edit/editPrice/$1");

    Route::put("discount-price", "products/edit/discountPrice/$1");

    Route::put("hide-price", "products/edit/hidePrice/$1");

    Route::put("edit-is-new-from-warehouse", "products/edit/isNewFromWarehouse/$1");
  });

  Route::put("apply-discount", "products/search/applyDiscount");
  Route::put("hide-prices", "products/search/hidePrice");

  # Discount packages
  Route::get("discount-packages", "products/discount_packages/all");
  Route::prefix("discount-packages", function() {
    Route::get("list-live", "products/discount_packages/all/live");

    Route::delete("{id}/delete", "products/discount_packages/all/delete/$1");
  });
});
