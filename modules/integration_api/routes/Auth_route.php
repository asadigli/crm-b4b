<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Route::prefix("auth", function() {
  Route::post("login", "authentication/login");
  Route::post("login/token", "authentication/loginWithToken");
});
