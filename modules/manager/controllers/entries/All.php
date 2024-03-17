<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true,["manager", "main_admin","admin"]);
    $this->load->model("entries/All_model","model");
  }

  function index()
  {
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/entries/index.js",
    ];

    $this->page_title = lang("Entries");
    $this->view([
      "layouts/header",
      "entries/index",
      "layouts/footer"
    ]);
  }

  function list(){
    $params = [
      "keyword" => $this->input->get("keyword"),
      "sort_by" => $this->input->get("sort_by"),
      "search_by_blocks" => $this->input->get("search_by_blocks"),
      "limit" => limits("b4b_entries"),
      "offset" => $this->input->get("offset") ?: "0",
      "is_developer" => Auth::isDeveloper() || Auth::isMainDeveloper()
    ];
    $res = $this->model->index($params);
    return json_response($res);
  }

  function supervisors(){
    $params = [
      "mode" => "short"
    ];
    $res = $this->model->supervisors($params);
    return json_response($res);
  }

  function customers(){
    $params = [

    ];
    $res = $this->model->customers($params);
    return json_response($res);
  }

  function properties(){
    $params = [

    ];
    $res = $this->model->properties($params);
    return json_response($res);
  }

}
