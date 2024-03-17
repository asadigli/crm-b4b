<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# B2B
Route::prefix("b4b", function() {
  # Auth
  // Route::prefix("auth",function(){
  //   Route::post("login","b2b/auth/login/action");
  //   Route::post("login-with-token","b2b/auth/login/actionWithToken");
  //   Route::post("get-code","b2b/auth/login/getCode");
  // });


  Route::get("orders", "b4b/orders/all/index");
  Route::prefix("orders", function() {

  });
});
