<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("promotions/All_model","model");
    Auth::check(true);
  }

  function index(){
    $this->extraCSS = [
    ];

    $this->extraJS = [
      "js/pages/promotions/index.js",
    ];

    $this->page_title = lang("Promotions");

    $this->view([
      "layouts/head",
      "layouts/header",
      "promotions/index",
      "layouts/footer"
    ],[]);
  }

  function list(){
    $params = [
      "limit" => $this->input->get("limit"),
      "show_main" => $this->input->get("show_main")
    ];

    $res = $this->model->list($params);

    return json_response($res);

  }

  function details($id){
    $params = [
      "id" => $id
    ];

    validateArray($params,["id"]);

    $res = $this->model->details($params);
    $promotion = [];
    if (isset($res["code"]) && $res["code"] === 200) {
      $view = "promotions/details.php";
      $promotion = $res["data"];
    }else{
      $view = "promotions/not_found.php";
    }

    $this->view([
      "layouts/head",
      "layouts/header",
      $view,
      "layouts/footer"
    ],
    [
      "promotion" => $promotion
    ]);
  }

}
