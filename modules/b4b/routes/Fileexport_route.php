<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Route::prefix("file-export", function() {
  Route::post("add-to-history", "fileexport/add/addToHistory");
});
