<?php
defined('BASEPATH') OR exit('No direct script access allowed');


Route::get("search-logs/all","searchlogs/index");
Route::get("search-logs","searchlogs/action");
Route::get("search-logs/only-customers","searchlogs/onlyCustomersAction");
