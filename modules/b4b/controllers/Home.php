<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

  public function __construct(){
    parent::__construct();
    Auth::check(true);
  }

  public function index(){
    $this->extraCSS = [

    ];
    $this->extraJS = [
      "js/pages/dashboard.js"
    ];

    $currencies = Services::currencies();

    $key = "b4b_dashboard_datas.cache";
    $dashboard_data = $this->cache->get($key);

    if (!$dashboard_data) {
      $this->load->model("sliders/All_model","slider_model");
      $dashboard_data["sliders"]  = $this->slider_model->index([]);

      $this->cache->save($key,$dashboard_data,360 * 24);
    }

    // $this->load->model("products/Search_model","prod_model");
    // $dashboard_data["new_products_from_warehouses"] = $this->prod_model->newFromWarehouses([]);

    $params = [
      "only_new_products" => $this->input->get("only_new_products") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE
    ];

    $this->view([
      "layouts/head",
      "layouts/header",
      "dashboard",
      "layouts/footer",
    ],
    [
      // "new_products_from_warehouses"    => isset($dashboard_data["new_products_from_warehouses"]["code"]) && $dashboard_data["new_products_from_warehouses"]["code"] === Status_codes::HTTP_OK ? $dashboard_data["new_products_from_warehouses"]["data"]["list"] : [],
      "sliders"  => isset($dashboard_data["sliders"]["code"]) && $dashboard_data["sliders"]["code"] === Status_codes::HTTP_OK ? $dashboard_data["sliders"]["data"] : [],
      "currencies"  => isset($currencies["code"]) && $currencies["code"] === Status_codes::HTTP_OK ? $currencies["data"] : [],

      "url_params" => $params,
    ]);
  }

  public function errorPage(){
    echo "THIS IS ERROR";
  }

  public function checkEntryUpdates(){
    if (file_exists(FCPATH . "assets/updates/".md5(Auth::user()).".status_check.json")) {
      unlink(FCPATH . "assets/updates/".md5(Auth::user()).".status_check.json");
    }
    $params = [
      "url_path"    => $this->input->post("url_path"),
      "entry_token" => Auth::accessToken()
    ];

    $this->load->model("Online_model","model");
    $res = $this->model->index($params);
    if (isset($res["code"]) && $res["code"] === Status_codes::HTTP_UNAUTHORIZED) {
      $access_token_list = isset($_COOKIE["access_token"]) && $_COOKIE["access_token"] ? @unserialize(@base64_decode($_COOKIE["access_token"])) : [];
      $secret_key = md5("SECRET" . Auth::email() . "SECRET");
      if (isset($access_token_list[$secret_key])) {
        unset($access_token_list[$secret_key]);
        setcookie("access_token", @base64_encode(@serialize($access_token_list)), time() + (86400 * 7), "/");
      }
      Auth::unset();
    }

    return json_response(rest_response(
      Status_codes::HTTP_UNAUTHORIZED,
      "NO AUTH"
    ));
  }

  public function newFromWarehouses()
  {
    $params = [];

    $this->load->model("products/Search_model","model");
    $new_products = $this->model->newFromWarehouses([]);

    return json_response($new_products);
  }
}
