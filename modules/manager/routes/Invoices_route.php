<?php
defined('BASEPATH') OR exit('No direct script access allowed');

# Invoices

Route::prefix("invoices",function(){

  Route::get("sales","invoices/sales/index");
  Route::get("daily-sales","invoices/sales/daily_list");

  Route::prefix("sales",function(){
    Route::get("list-live","invoices/sales/live");
    Route::get("{code}/details","invoices/sales/details/$1");

    Route::get("daily-list-live","invoices/sales/daily_list_live");
  });


  Route::get("purchases","invoices/purchases/index");

  Route::prefix("purchases",function(){
    Route::get("list-live","invoices/purchases/live");
    Route::get("{code}/details","invoices/purchases/details/$1");
  });

});
