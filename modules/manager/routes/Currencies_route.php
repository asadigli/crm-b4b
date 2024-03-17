<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Route::prefix("currencies", function(){
  Route::get("list", "currencies/all/list");
});
