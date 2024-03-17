<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

  function __construct() {
    parent::__construct();
    // Auth::check(true);
  }

  function checkStatus() {
    // DEBUG: NOT COMPLETED
    error_reporting(0);
    ini_set('display_errors', 0);
    $this->local_db = $this->load->database("local_db",true);

    return json_response(rest_response(
      Status_codes::HTTP_INTERNAL_SERVER_ERROR,
      lang("Connection failed")
    ));
  }

  function index() {
    return json_response(rest_response(
      Status_codes::HTTP_NOT_FOUND,
      lang("Page not found")
    ));
  }

  function errorPage(){
    return json_response(rest_response(
      Status_codes::HTTP_NOT_FOUND,
      lang("Page not found")
    ));
  }

}
