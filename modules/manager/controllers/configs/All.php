<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true,["main_admin"]);
    $this->load->model("configs/All_model", "model");
  }

  public function index()
  {
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/configs/index.js",
    ];
    $properties = $this->model->properties([]);

    $this->page_title = lang("Configs");
    $this->view([
      "layouts/header",
      "configs/index",
      "layouts/footer",
    ],[
      "page_title" => lang("Configs"),
      "properties" => isset($properties["code"]) && $properties["code"] === Status_codes::HTTP_OK ? $properties["data"] : []
    ]);
  }

  public function live()
  {
    $params = [
      "keyword" => $this->input->get("keyword")
    ];
    $res = $this->model->index($params);

    if (isset($res["code"]) && $res["code"] === Status_codes::HTTP_OK) {
      $res["is_developer"] = Auth::isDeveloper();
    }


    return json_response($res);
  }

}
