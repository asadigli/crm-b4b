<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller{

  function __construct(){
    parent::__construct();
  }

  function home_page(){
    $data = rest_response(
      Status_codes::HTTP_METHOD_NOT_ALLOWED,
      'method not allowed',
      []
    );
    $this->response($data);
  }

}
