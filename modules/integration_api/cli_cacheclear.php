<?php


if (php_sapi_name() === "cli") {
  define('BASEPATH', __DIR__);
  if (file_exists("modules/integration_api/controllers/caches/Edit.php")) {
    $CI = get_instance();
    echo "OK\n";die;
    require BASEPATH . "/controllers/caches/Edit.php";
    $edit = new Edit1(1);
    var_dump($edit);
  } else {
    echo "not exists";
  }

} else {
  echo "NOT AUTHED";
}
