<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function page404()
  {
    $list = rest_response(
      Status_codes::HTTP_NOT_FOUND,
      $this->lang->line("Page not found"),
      []
    );
    $this->response($list);
  }

}
