<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errorhandling extends MY_Controller{
  public function __construct(){
    parent::__construct();
  }

  public function notFound(){
    $this->view([
      "home"
    ]);
  }
}
