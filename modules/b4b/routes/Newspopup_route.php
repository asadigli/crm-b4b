<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Route::prefix("news-popup",function(){
  Route::get("list","news_popup/all/index");
});
