<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Route::get("main-page","b4b/sliders/all");
Route::prefix("sliders", function(){
  Route::get("list-live", "b4b/sliders/all/listLive");
  Route::post("add", "b4b/sliders/add");
  Route::put("edit", "b4b/sliders/edit");
  Route::delete("{id}/delete", "b4b/sliders/edit/delete/$1");
});

Route::get("product-brands","b4b/brand_sliders/all");
Route::prefix("brand-sliders", function(){
  Route::get("list-live", "b4b/brand_sliders/all/listLive");
  Route::post("add", "b4b/brand_sliders/add");
  Route::put("edit", "b4b/brand_sliders/edit");
  Route::delete("{id}/delete", "b4b/brand_sliders/edit/delete/$1");
});

Route::get("news", "b4b/news/all");
Route::prefix("news", function(){
  Route::get("list-live", "b4b/news/all/listLive");
  Route::get("types", "b4b/news/all/types");
  Route::post("add", "b4b/news/add");
  Route::put("{id}/edit", "b4b/news/edit/index/$1");
  Route::delete("{id}/delete", "b4b/news/edit/delete/$1");
});
