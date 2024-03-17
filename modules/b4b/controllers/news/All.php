<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("news/All_model","model");
    Auth::check(true);
  }

  function index(){
    $this->extraCSS = [
    ];

    $this->extraJS = [
      "js/pages/news/index.js",
    ];

    $this->page_title = lang("News");

    $this->view([
      "layouts/head",
      "layouts/header",
      "news/index",
      "layouts/footer"
    ],[]);
  }

  function list(){
    $params = [
      "start_date" => $this->input->get("start_date"),
      "end_date" => $this->input->get("end_date")
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
    $news = [];
    if (isset($res["code"]) && $res["code"] === 200) {
      $view = "news/details.php";
      $news = $res["data"];
    }else{
      $view = "news/not_found.php";
    }

    $this->view([
      "layouts/head",
      "layouts/header",
      $view,
      "layouts/footer"
    ],
    [
      "news" => $news
    ]);
  }

}
