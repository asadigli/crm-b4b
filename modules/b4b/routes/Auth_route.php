<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Route::prefix("auth",function(){
  Route::get("reset-sessions","auth/login/resetSessions");

  Route::get("login","auth/login");
  Route::post("action","auth/login/action");
  Route::get("logout","auth/logout");

  Route::post("switch-customer","auth/logout/switchCustomer");
});


Route::post("check-entry-updates","home/checkEntryUpdates");
