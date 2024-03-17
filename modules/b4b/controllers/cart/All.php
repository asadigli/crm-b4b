<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true);
  }

  public function index()
  {
    $this->extraCSS = [
      "../globals/css/libs/fancybox.min.css"
    ];

    $this->extraJS = [
      "../globals/js/libs/fancybox.min.js",
      "js/pages/cart/index.js",
    ];

    $this->page_title = lang("Cart");
    $this->view([
      "layouts/head",
      "layouts/header",
      "cart/index",
      "layouts/footer"
    ],[

    ]);
  }

  public function live()
  {
    $params = [
      "wid" => $this->input->get("wid"),
      "resource" => $this->input->get("resource"),
      "filter_type" => $this->input->get("filter_type"),
      "is_remote" => $this->input->get("is_remote"),
    ];

    $this->load->model("cart/All_model", "model");
    $res = $this->model->index($params);

    return json_response($res);
  }

  public function cartCountLive()
  {
    $params = [];

    $this->load->model("cart/All_model", "model");
    $res = $this->model->cartCount($params);

    if (isset($res["code"]) && $res["code"] === Status_codes::HTTP_OK) {
      Auth::setOneSession("cart_info",[
        "count" => $res["data"]["count"],
        "sale_price" => $res["data"]["sale_price"]
      ]);
    }
    return json_response($res);
  }
}
