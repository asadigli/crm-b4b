<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errorhandling extends MY_Controller{

  public function __construct(){
    parent::__construct();
    Auth::check(true);
  }

  public function notFound(){
    $this->view([
      "layouts/errorhandling/404.php"
    ]);
  }
}
