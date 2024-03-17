<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true,[]);
  }

  public function index()
  {
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/configurations/systemsetups/index.js",
    ];

    $this->page_title = lang("System setups");
    $this->view([
      "layouts/header",
      "configurations/systemsetups/index",
      "layouts/footer",
    ],[
    ]);
  }

  public function refreshLocalCache()
  {
    $path = APPPATH . "cache/";
    $files = array_diff(scandir($path), array('.', '..','index.html','.DS_Store'));

    foreach ($files as $key => $item) {
      if (file_exists( $path . $item)) {
        unlink( $path . $item);
      }
    }

    return json_response(rest_response(
      Status_codes::HTTP_OK,
      lang("Operation implemented")
    ));
  }

  public function clearLocalSessions()
  {
    $path = APPPATH . "..". DIRECTORY_SEPARATOR ."sessions" . DIRECTORY_SEPARATOR;
    $files = array_diff(scandir($path), array('.', '..','index.html','.DS_Store'));

    foreach ($files as $key => $item) {
      if (file_exists( $path . $item)) {
        unlink( $path . $item);
      }
    }

    return json_response(rest_response(
      Status_codes::HTTP_OK,
      lang("Operation implemented")
    ));
  }

  public function checkAccess()
  {
    $params = [
      "connection_timeout" => $this->input->get("connection_timeout"),
    ];

    $this->load->model("systemsetups/All_model", "model");
    $res = $this->model->checkAccess($params);
    return json_response($res);
  }
}
