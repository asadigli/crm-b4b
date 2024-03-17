<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct(){
    parent::__construct();
    Auth::check(true,["developer", "main_admin"]);
  }

  public function index(){
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/b4b/brand_sliders/index.js"
    ];

    $this->page_title = lang("B4B brand sliders");
    $this->view([
      "layouts/header",
      "b4b/brand_sliders/index",
      "layouts/footer",
    ],[
      "page_title" => lang("B4B brand sliders"),
    ]);
  }

  public function listLive(){
    $this->load->model("b4b/brand_sliders/All_model", "model");
    $params = [];
    $res = $this->model->index($params);
    return json_response($res);
  }

}
