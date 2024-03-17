<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#systemusers
Route::get("system-users", "systemusers/all/index");
Route::get("profile", "systemusers/profile");

Route::prefix("system-users", function() {
  Route::get("list-live", "systemusers/all/live");
  Route::get("groups", "systemusers/all/groups");
  Route::get("roles", "systemusers/all/roles");
  Route::put("{id}/edit", "systemusers/edit/index/$1");
  Route::put("{id}/add-order-group", "systemusers/edit/editGroup/$1");
  Route::put("{id}/edit-password", "systemusers/edit/editPassword/$1");
  Route::delete("{id}/delete", "systemusers/edit/delete/$1");
  Route::post("add", "systemusers/add/index");
  Route::get("order-groups","systemusers/all/orderGroups");
});

