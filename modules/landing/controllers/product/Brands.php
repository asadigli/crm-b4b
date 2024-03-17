<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brands extends MY_Controller
{
  public function __construct(){
    parent::__construct();
  }
  public function allList(){
    $this->load->view("product/brand/list");
  }

  public function liveList(){
    $params = [
      "limit"   => 1000000 ?: 15,
      "offset"  => (int)$this->input->get("offset") ?: 0,
      "user"    => $this->auth_user,
      "version" => $this->input->get("version") === "full" ? true : false,
    ];

    $this->load->model("product/Brand_model","model");
    $res = $this->model->liveList($params);

    return json_response($res);
  }

  public function brandDetails($slug){
    $list = explode("-",$slug);
    $slug = $list[count($list) - 1];
    $params = [
      "brand" => $slug
    ];
    $this->load->model("product/Brand_model","model");
    $res = $this->model->brandDetails($params);
    if (!isset($res['code']) || $res['code'] !== 200) {
      $this->redError(404,base_url(),lang("Brand not found"));
      die;
    }
    $this->load->view("product/brand/details",[
      "data" => $res["data"]
    ]);
  }
}
