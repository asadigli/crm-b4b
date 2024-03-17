<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("manager/b4b/entries/All_model","model");
    checkHeaders();

  }

  function index()
  {
    $params = [
      "keyword" => $this->custom_input->get("keyword",[
        "symbols" => ["'","\"","\\","`"]
      ]),
      "sort_by" => $this->input->get("sort_by"),
      "search_by_blocks" => $this->input->get("search_by_blocks"),
      "limit" => $this->input->get("limit"),
      "offset" => $this->input->get("offset"),
      "is_developer" => $this->input->get("is_developer"),
    ];

    $params["sort_by"] = in_array($params["sort_by"],["by_latest","by_is_online"]) ? $params["sort_by"] : "by_latest";
    $params["search_by_blocks"] = in_array($params["search_by_blocks"],["by_is_blocked","by_is_not_blocked"]) ? $params["search_by_blocks"] : "";
    $res = $this->model->index($params);

    return json_response($res);
  }

  function properties(){
    $res = $this->model->properties([]);

    return json_response($res);
  }

}
