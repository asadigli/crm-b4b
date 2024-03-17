<?php
defined('BASEPATH') OR exit('No direct script access allowed');

# Integration caches
// Route::get("caches", "caches/all/index");
Route::prefix("caches",  function(){
  Route::put("{type}/{request_type}/refresh", "caches/edit/refresh/$1/$2");
  //
  Route::put("refresh-all", "caches/edit/refreshAll");
});
