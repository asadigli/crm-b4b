<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Route::get("promotions","promotions/all");
Route::prefix("promotions",function(){
  Route::get("list","promotions/all/list");
  Route::get("{id}/details","promotions/all/details/$1");
});
