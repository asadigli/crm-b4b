<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct() {
    parent::__construct();
    Auth::check(true,["admin","main_admin"]);
    $this->load->model("caches/All_model", "model");
  }

  public function index() {
    $this->extraJS = [
      "js/helpers.js",
      "js/pages/caches/index.js",
    ];

    $caches = $this->config->item("cache_list");

    $history = $this->model->history([]);

    $this->page_title = lang("Caches");
    $this->view([
      "layouts/header",
      "caches/index",
      "layouts/footer",
    ],[
      "page_title" => lang("Caches"),
      "caches" => isset($caches) && $caches ? $caches : [],
      "history" => isset($history["data"]) ? $history["data"] : [],
    ]);
  }


  function refresh() {
    $params = [
      "type" => $this->custom_input->put("type"),
      "limit_hour" => $this->custom_input->put("limit_hour"),
      "start_date" => $this->custom_input->put("start_date"),
      "end_date" => $this->custom_input->put("end_date"),
    ];

    if($params["type"] === "product-brands"){
      $b4b_brand_cache  = APPPATH . "cache/b4b_product_brands_datas.cache";
      if(file_exists($b4b_brand_cache)) {
        unlink($b4b_brand_cache);
      }
    }

    if($params["type"] === "carbrands"){
      $b4b_brand_cache  = APPPATH . "cache/b4b_product_carbrands_datas.cache";
      if(file_exists($b4b_brand_cache)) {
        unlink($b4b_brand_cache);
      }
    }

    $res = $this->model->refresh($params);

    if(isset($res["code"]) && $res["code"] === Status_codes::HTTP_ACCEPTED && $params["type"] === "currencies") {

      $b4b_currencies_cache  = APPPATH . "cache/b4b_cached_currencies.cache";
      if(file_exists($b4b_currencies_cache)) {
        unlink($b4b_currencies_cache);
      }
    }
    return json_response($res);
  }

}
