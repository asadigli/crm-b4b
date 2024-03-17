<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# Caches
Route::get("supervisors", "supervisors/all/index");
Route::prefix("supervisors", function() {
  Route::get("live", "supervisors/all/live");
  Route::post("add-action", "supervisors/all/add");
  Route::put("edit-action", "supervisors/all/edit");
});
