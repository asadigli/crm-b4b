<?php
defined('BASEPATH') OR exit('No direct script access allowed');

# Customers

Route::get("customers", "customers/all/index");
Route::prefix("customers",function(){

    Route::get("list-live", "customers/all/live");
    Route::get("simple-list-live","customers/all/list");
    Route::get("city-list","customers/all/cityList");
    Route::prefix("{id}", function() {

      Route::put("edit-max-order-limit", "customers/edit/editMaxOrderLimit/$1");
      Route::put("edit-is-blocked", "customers/edit/editIsBlocked/$1");
      Route::put("edit-max-allowed-order-limit", "customers/edit/editMaxAllowedOrderLimit/$1");
      Route::put("edit-has-order-limit", "customers/edit/editHasOrderLimit/$1");

      # Account
      Route::get("account","customers/account/all/index/$1");
      Route::prefix("account",function(){
        Route::get("list-live","customers/account/all/live/$1");
        Route::get("{code}/details","customers/account/details/index/$1/$2"); // DEBUG: bu yığışdırılmalıdır
      });

  });
});
