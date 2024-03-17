<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true);
  }

  function index()
  {
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/systemusers/profile.js",
    ];


    $this->view(["layouts/header","systemusers/profile","layouts/footer"]);
  }

}
