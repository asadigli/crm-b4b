<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Route::get("news","news/all");
Route::prefix("news",function(){
  Route::get("list","news/all/list");
  Route::get("{id}/details","news/all/details/$1");
});
