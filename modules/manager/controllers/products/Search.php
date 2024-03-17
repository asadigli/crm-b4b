<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true);
  }

  public function applyDiscount(){
    return json_response($this->live("put"));

  }

  public function hidePrice(){
    return json_response($this->live("put"));
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
      "is_dead_stock" => $this->input->get("is_dead_stock"),
      "dead_stock" => $this->input->get("dead_stock"),
      "min_search_quantity" => $this->input->get("min_search_quantity"),
      "max_search_quantity" => $this->input->get("max_search_quantity"),
      "discount_package_id" => $this->input->get("discount_package_id"),
      "warehouse_id" => $this->input->get("warehouse_id"),
      "only_warehouse" => $this->input->get("only_warehouse"),
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

    $this->extraJS = [
      "js/helpers.js",
      "js/pages/products/search.js",
    ];


    $this->page_title = lang("Products");

    $this->view([
        "layouts/header",
        "products/search",
        "layouts/footer",
    ],[
      "banners" => [],
      "url_params" => $params,
    ]);
  }

  public function live($method = "get")
  {
    $params = [
      "keyword" => $this->custom_input->{$method}("keyword"),
      "filter" => $this->custom_input->{$method}("filter"),
      "brand" => $this->custom_input->{$method}("brand"),
      "car_brand" => $this->custom_input->{$method}("car_brand"),
      "product_resource" => $this->custom_input->{$method}("product_resource"),
      "model" => $this->custom_input->{$method}("model"),
      "cr_search" => $this->custom_input->{$method}("cr_search"),
      "search_type" => $this->custom_input->{$method}("search_type"),
      "is_on_the_way" => $this->custom_input->{$method}("is_on_the_way"),
      "in_stock" => $this->custom_input->{$method}("in_stock"),
      "offset" => $this->custom_input->{$method}("offset"),
      "export" => $this->custom_input->{$method}("excel_export"),
      "is_dead_stock" => $this->custom_input->{$method}("is_dead_stock"),
      "dead_stock" => $this->custom_input->{$method}("dead_stock"),
      "min_search_quantity" => $this->custom_input->{$method}("min_search_quantity"),
      "max_search_quantity" => $this->custom_input->{$method}("max_search_quantity"),
      "warehouse_id" => $this->custom_input->{$method}("warehouse_id"),
      "only_warehouse" => $this->custom_input->{$method}("only_warehouse"),
      "discount_package_id" => $this->custom_input->{$method}("discount_package_id"),
      "name" => $this->custom_input->{$method}("name"),
      "apply_discount" => $this->custom_input->{$method}("apply_discount"),
      "hide_price" => $this->custom_input->{$method}("hide_price"),
    ];

    if($params["apply_discount"] === STATUS_ACTIVE){
      $this->load->model("products/Search_model","model");
      $res = $this->model->applyDiscount($params);
      return json_response($res);
    }

    if($params["hide_price"] === STATUS_ACTIVE){
      $this->load->model("products/Search_model","model");
      $res = $this->model->hidePrice($params);
      return json_response($res);
    }

    $this->load->model("products/Search_model", "model");
    $res = $this->model->index($params);
    return json_response($res);
  }

  function updateType(){
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

  public function priceOffers(){
    $params = [
      "product_id" => (int)$this->input->get("product_id") ?: NULL,
      // "remoteb4buserid" => (int)headers("remoteb4buserid"),
    ];

    $this->load->model("products/Search_model", "model");
    $res = $this->model->priceOffers($params);
    return json_response($res);
  }
}
