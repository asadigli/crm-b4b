<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errorhandling extends MY_Controller{
  public function __construct(){
    parent::__construct();
  }

  public function notFound(){
    return json_response(rest_response(
        Status_codes::HTTP_NOT_FOUND,
        lang("Page not found")
      ));
  }
}
