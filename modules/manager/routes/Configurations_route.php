<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# Configurations
Route::prefix("configurations", function() {

  #Logs
  Route::get("error-logs", "configurations/errorlogs/all/index");
  Route::prefix("error-logs", function() {
    Route::get("list-live", "configurations/errorlogs/all/live");
    Route::get("paths", "configurations/errorlogs/all/logsPathsLive");
  });

  #System setups
  Route::get("system-setups", "configurations/systemsetups/all/index");
  Route::prefix("system-setups", function() {
    Route::get("refresh-local-cache", "configurations/systemsetups/all/refreshLocalCache");
    Route::get("clear-local-sessions", "configurations/systemsetups/all/clearLocalSessions");
    // Route::get("check-access", "systemsetups/all/checkAccess");
  });
});
