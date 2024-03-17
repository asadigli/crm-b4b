<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Route::prefix("auth",function(){
  Route::get("login","auth/login");
  Route::post("login","auth/login/action");
  Route::get("logout","auth/logout");
});
