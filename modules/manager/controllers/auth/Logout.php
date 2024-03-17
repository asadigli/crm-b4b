<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true);
  }

  function index()
  {
    Auth::unset();
    redirect(path_local("auth/login"));
  }

}
