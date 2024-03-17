<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Route::get("entries","entries/all");
Route::prefix("entries",function(){
  Route::get("list","entries/all/list");
  Route::get("properties","entries/all/properties");

  Route::get("supervisors","entries/all/supervisors");
  Route::post("add","entries/add");
  Route::get("ava-customers","entries/all/customers");

  /*
  * DETAILS ROUTES
  */
  Route::prefix("{id}",function() {
    Route::put("add-customer","entries/edit/addCustomer/$1");
    Route::delete("delete","entries/edit/delete/$1");
    Route::put("edit-password","entries/edit/password/$1");
    Route::put("edit-detail","entries/edit/detail/$1");
    Route::put("edit-properties","entries/edit/editProperties/$1");
    Route::put("stock-show","entries/edit/stock/$1");
    Route::put("store-active","entries/edit/storeActive/$1");
    Route::put("entry-limit","entries/edit/entryLimit/$1");

    Route::put("edit-is-blocked", "entries/edit/editIsBlocked/$1");
  });
});
