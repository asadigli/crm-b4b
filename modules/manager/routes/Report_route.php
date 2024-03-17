<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#

Route::prefix("brand-reports",function(){
  Route::get("all", "invoices/all/brandReports");
  Route::get("invoices/live", "invoices/all/live");

  Route::get("in-details", "invoices/all/brandReportsInDetails");
  Route::get("invoices/live-in-details", "invoices/all/liveInDetails");
});
