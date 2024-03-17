<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Route::get("orders", "orders/all/index");
Route::prefix("orders",function(){
  Route::get("list-live", "orders/all/live");

  Route::put("update-copy-check", "orders/edit/orderDetailsCopyChecks");
  Route::put("update-order-group-order", "orders/edit/orderGroupOrder");

  Route::prefix("{id}", function() {
    Route::get("details", "orders/details/index/$1");
    Route::get("details/list-live", "orders/details/live/$1");

    Route::put("edit-status", "orders/edit/editStatus/$1");
    Route::put("transfer-order", "orders/edit/transferOrder/$1");

    // Route::put("status-confirm", "orders/edit/statusConfirm/$1");
    // Route::put("status-finish", "orders/edit/statusFinish/$1");
    // Route::put("status-cancel", "orders/edit/statusCancel/$1");
  });

  Route::get("returns", "orders/returns/all/index");
  Route::prefix("returns", function() {
    Route::get("list-live", "orders/returns/all/live");
    Route::get("{id}/details", "orders/returns/details/index/$1");
  });


  #Folders
  Route::get("folders", "orders/folders/all/index");
  Route::prefix("folders", function() {
    Route::get("list", "orders/folders/all/list");


    Route::post("add", "orders/folders/add/index");

    Route::prefix("{id}", function(){
      # Order operations
      Route::get("orders-list", "orders/folders/all/ordersList/$1");
      Route::post("add-order", "orders/folders/add/addOrder/$1");
      Route::delete("remove-order", "orders/folders/edit/removeOrder/$1");

      #Folder operations
      Route::put("edit-name", "orders/folders/edit/editName/$1");
      Route::put("edit-description", "orders/folders/edit/editDescription/$1");
      Route::put("edit-is-active", "orders/folders/edit/editIsActive/$1");
      Route::delete("delete", "orders/folders/edit/delete/$1");
    });
  });
});
