<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true);
  }

  public function index()
  {
    $params = [
      "filter" => $this->input->get("filter") ?: FILTER_STOCK_BAKU_DESC,
      "brand" => $this->input->get("brand"),
      "keyword" => $this->input->get("keyword"),
      "car_brand" => $this->input->get("car_brand"),
      "model" => $this->input->get("model"),
      "search_type" => $this->input->get("search_type") ?: (isset($_COOKIE["search_type"]) ? $_COOKIE["search_type"] : null),
      "is_on_the_way" => $this->input->get("is_on_the_way"),
      "in_stock" => $this->input->get("in_stock"),
      "show_discount" => $this->input->get("show_discount"),
    ];

    $products_filters = [
      FILTER_STOCK_BAKU_ASC,
      FILTER_STOCK_BAKU_DESC,
      FILTER_STOCK_GANJA_ASC,
      FILTER_STOCK_GANJA_DESC,
      FILTER_CART_ASC,
      FILTER_CART_DESC,
      FILTER_PRICE_EUR_ASC,
      FILTER_PRICE_EUR_DESC,
      FILTER_PRICE_AZN_ASC,
      FILTER_PRICE_AZN_DESC,
      FILTER_BRAND_NAME_ASC,
      FILTER_BRAND_NAME_DESC,
      FILTER_PRODUCT_NAME_ASC,
      FILTER_PRODUCT_NAME_DESC,
    ];

    if(!in_array($params["filter"], $products_filters)){
      $params["filter"] = FILTER_STOCK_BAKU_DESC;
    }

    $this->extraCSS = [
      "../globals/css/libs/fancybox.min.css"
    ];

    $this->extraJS = [
      "../globals/js/libs/fancybox.min.js",
      "js/pages/products/search.js",
    ];

    $cache_key = "b4b_product_search_datas.cache";
    $cached_data = $this->cache->get($cache_key);
    if (!$cached_data) {
      $this->load->model("products/Properties_model", "model");
      // $banners = $this->model->banners([]);
      $brand_sliders = $this->model->brand_sliders([]);

      if (isset($brand_sliders["code"]) && $brand_sliders["code"] === Status_codes::HTTP_OK) {
        $this->cache->save($cache_key,[
          "brand_sliders" => $brand_sliders["data"],
        ],360 * 24);
        $brand_sliders = $brand_sliders["data"];
      } else {
        $brand_sliders = ["list" => [],"count" => 0];
      }
    } else {
      // $banners = $cached_data["banners"];
      $brand_sliders = $cached_data["brand_sliders"];
    }

    $customers = Auth::customers();
    $current_account_currency = CURRENCY_EUR;
    foreach ($customers as $key => $item) {
      if($item["is_current"]) {
        $current_account_currency = $item["currency_name"];
      }
    }

    $this->page_title = lang("Products");

    $this->view([
        "layouts/head",
        "layouts/header",
        "products/search",
        "layouts/footer",
    ],[
      // "banners" => $banners,
      "brand_sliders" => $brand_sliders,
      "url_params" => $params,
      "current_account_currency" => $current_account_currency,
    ]);
  }

  public function live()
  {
    $params = [
      "keyword" => $this->input->get("keyword"),
      "filter" => $this->input->get("filter"),
      "brand" => $this->input->get("brand"),
      "car_brand" => $this->input->get("car_brand"),
      "model" => $this->input->get("model"),
      "cr_search" => $this->input->get("cr_search"),
      "search_type" => $this->input->get("search_type"),
      "is_on_the_way" => $this->input->get("is_on_the_way"),
      "in_stock" => $this->input->get("in_stock"),
      "show_discount" => $this->input->get("show_discount"),
      "offset" => $this->input->get("offset"),
      "export" => $this->input->get("excel_export"),
    ];

    $this->load->model("products/Search_model", "model");
    $res = $this->model->index($params);
    return json_response($res);
  }

  public function updateType(){
    $params = [
      "search_type" => $this->custom_input->put("search_type")
    ];

    setcookie("search_type", $params["search_type"], time() + (86400 * 360), "/");

    if(!isset($_COOKIE["search_type"])) {
      return json_response(
        rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("Search type not updated")
          )
        );
    }

    return json_response(
      rest_response(
        Status_codes::HTTP_ACCEPTED,
        lang("Search type updated")
        )
      );
  }

  public function tecdocCrosses()
  {
    $params = [
      "keyword" => $this->input->get("keyword"),
    ];

    $this->load->model("products/Search_model", "model");
    $res = $this->model->tecdocCrosses($params);
    return json_response($res);
  }

  public function comments(){
    $params = [
      "product_id" => (int)$this->input->get("product_id") ?: NULL,
      // "remoteb4buserid" => (int)headers("remoteb4buserid"),
    ];

    $this->load->model("products/Search_model", "model");
    $res = $this->model->comments($params);
    return json_response($res);
  }

  public function addComments()
  {

    $params = [
      "product_id" => (int)$this->input->post("product_id") ?: NULL,
      "entry_product_comment" => $this->input->post("entry_product_comment") ?: NULL,
      // "remoteb4buserid" => (int)headers("remoteb4buserid"),
    ];

    $this->load->model("products/Search_model", "model");
    $res = $this->model->addComments($params);
    return json_response($res);
  }

  public function addPriceOffer()
  {

    $params = [
      "product_id" => (int)$this->input->post("product_id") ?: NULL,
      "price_offer" => $this->input->post("price_offer") ?: NULL,
      "company_name" => $this->input->post("company_name") ?: NULL,
      // "remoteb4buserid" => (int)headers("remoteb4buserid"),
    ];

    $this->load->model("products/Search_model", "model");
    $res = $this->model->addPriceOffer($params);
    return json_response($res);
  }
}
