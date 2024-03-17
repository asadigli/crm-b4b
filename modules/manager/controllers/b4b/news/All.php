<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct(){
    parent::__construct();
    Auth::check(true,["developer", "main_admin"]);
  }

  public function index(){
    $this->extraCSS = [
      "../globals/css/libs/fancybox.css",
    ];

    $this->extraJS = [
      "../globals/js/libs/fancybox.min.js",
      "js/libs/flup.js",
      "js/helpers.js",
      "js/pages/b4b/news/index.js"
    ];

    $this->page_title = lang("News");

    $this->view([
      "layouts/header",
      "b4b/news/index",
      "layouts/footer"
    ]);
  }

  public function listLive(){
    $this->load->model("b4b/news/All_model","model");
    $params = [];
    $res = $this->model->index($params);
    return json_response($res);
  }

  public function types(){
    $this->load->model("b4b/news/All_model","model");
    $params = [];
    $res = $this->model->types($params);
    return json_response($res);
  }

}
