<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends MY_Controller{

  function __construct(){
    parent::__construct();
  }

  private $path = "news/";

  function allList(){
    $this->load->view($this->path."all");
  }

  function addNew(){
    $this->load->view($this->path."add_new");
  }

  function details($slug){
    $this->load->model("News_model","model");
    $params = [
      "slug" => $slug
    ];
    $res = $this->model->details($params);
    // return json_response($res,"no_auth");die;
    if (!isset($res["code"]) || $res["code"] !== 200) {
      if (isset($res["code"]) && $res["code"] === Status_codes::HTTP_PARTIAL_CONTENT) {
        redirect(path_local("news/".$res["data"]["slug"]));
      } else {
        $this->redError(404,base_url(),lang("News not found"));
        die;
      }
    }
    // return json_response($res["data"],"no_auth");die;
    $this->load->view($this->path."details",["slug" => $slug,"data" => $res["data"]]);
  }

  function getAll(){
    $this->load->model("News_model","model");
    $params = [
      "user" => $this->auth_user,
      "version" => $this->input->get("version") ?: null,
      "limit" => $this->input->get("limit") ?: 5,
      "offset" => $this->input->get("page") && $this->input->get("page") > 1 ? ($this->input->get("page") - 1)*5 : 0,
      "language" => $this->language
    ];
    $res = $this->model->getAll($params);
    return json_response($res);
  }


}
