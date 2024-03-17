<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# B4B
Route::prefix("b4b", function() {
  # Auth
  Route::prefix("auth",function(){
    Route::post("login","b4b/auth/login/action");
    // Route::post("login-with-token","b4b/auth/login/actionWithToken");
    // Route::post("get-code","b4b/auth/login/getCode");
  });

  Route::prefix("file-export", function() {
    Route::post("add-to-history", "b4b/fileexport/add/addToHistory");
  });


  # Orders
  Route::get("orders", "b4b/orders/all/index");
  Route::prefix("orders", function() {
    Route::get("{id}/details", "b4b/orders/all/details/$1");
    Route::post("is-approve", "b4b/orders/add/isApprove");
    Route::put("confirm-approve", "b4b/orders/edit/confirmApprove");

    Route::get("returns", "b4b/orders/returns/all/index");
    Route::prefix("returns", function() {
      Route::get("{id}/details", "b4b/orders/returns/all/details/$1");
      Route::post("add", "b4b/orders/returns/add/action");
    });
  });

  # Warehouse
  Route::get("warehouses", "b4b/warehouses/all/index");


  ## Promotions ##
  Route::prefix("promotions",function(){
    Route::get("list","b4b/promotions/all");
    Route::get("{id}/details","b4b/promotions/all/details/$1");
  });

  Route::get("news-popup", "b4b/news_popup/all");

  ## News ##
  Route::prefix("news",function(){
    Route::get("list","b4b/news/all");
    Route::get("{id}/details","b4b/news/all/details/$1");
  });


  # Products search
  Route::prefix("products", function() {

    Route::get("new-from-warehouses", "b4b/products/search/newFromWarehouses");

    Route::get("search", "b4b/products/search/index");
      Route::prefix("search", function() {
      Route::get("tecdoc-crosses", "b4b/products/search/tecdocCrosses");
      Route::get("comments", "b4b/products/search/comments");

      Route::post("add-comment", "b4b/products/search/addComments");
      Route::post("add-price-offer", "b4b/products/search/addPriceOffer");
    });

    Route::get("main","b4b/products/mainproducts");

    Route::prefix("properties", function() {
      Route::get("brands", "b4b/products/properties/brands");
      Route::get("car-brands", "b4b/products/properties/carBrands");
      Route::get("banners", "b4b/products/properties/banners");
    });
  });

  # Cart
  Route::get("cart", "b4b/cart/all/index");

  Route::prefix("cart", function() {
    Route::prefix("properties", function() {
      Route::get("filter-list", "b4b/cart/properties/filters/list");
    });

    Route::get("count", "b4b/cart/all/cartCount");
    Route::post("add", "b4b/cart/add/index");
    Route::put("{id}/edit-quantity", "b4b/cart/edit/editQuantity/$1");
    Route::put("{id}/edit-note", "b4b/cart/edit/editNote/$1");
    Route::delete("{id}/delete", "b4b/cart/edit/delete/$1");
    Route::delete("delete-items", "b4b/cart/edit/deleteItems");
  });

  Route::prefix("customers", function() {
    Route::get("{code}/account", "b4b/customers/account/index/$1");
    Route::get("{code}/account/details", "b4b/customers/account/details/$1");
  });

  Route::post("onlines","b4b/onlines/add");
  Route::get("configs","b4b/configs/all");

  Route::get("banners","b4b/banners/all");

  Route::get("sliders", "b4b/sliders/all");
  Route::get("brand-sliders", "b4b/brand_sliders/all");

});
