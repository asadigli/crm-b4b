<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Route::get("account","account/all");
Route::prefix("account",function(){

  Route::delete("delete-excel-file", "account/edit/deleteExcelFile");

  Route::get("live","account/all/live");
  Route::get("{code}/details","account/details/index/$1");
  Route::post("return-product","account/details/return");
});
