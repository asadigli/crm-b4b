<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function index()
  {
    $is_local = checkIsLocalRequest();
    $auth_user = $is_local ? checkCompany(NULL, true) : NULL;

    $params = [
      "b4buserid" => (int)headers("b4buserid") ?: NULL,
      "remoteb4buserid" => (int)headers("remoteb4buserid") ?: NULL,
      "keyword" => $this->custom_input->get("keyword",[
        "symbols" => ["'","\"","\\","/","`"]
      ]),
      "brand" => (int)$this->input->get("brand") ?: NULL,
      "filter" => $this->input->get("filter") ?: NULL,
      "search_type" => $this->input->get("search_type") ?: NULL,
      "car_brand" => (int)$this->input->get("car_brand") ?: NULL,
      "model" => $this->input->get("model") ?: NULL,
      "in_stock" => $this->input->get("in_stock") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "show_discount" => $this->input->get("show_discount") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "cr_search" => $this->input->get("cr_search") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "operation_date" => now(),
      "entry_ip" => headers("useraddress") ?: "127.0.0.1",
      "entry_device" => headers("userdevice") ?: NULL,
      "entry_agent" => headers("useragent") ?: NULL,
      "offset" => (int)$this->input->get("offset") ?: NULL,
      "auth_user" => $auth_user,
      "is_store_active" => $auth_user["is_store_active"],
      "export" => $this->input->get("export") ? 1 : 0,
      "is_local" => $is_local,
    ];

    if (!$params["keyword"] && !$params["brand"] && !$params["car_brand"]) {
      validateArray(["keyword" => NULL,"brand" => NULL, "car_brand" => NULL],["keyword","brand","car_brand"]);
    }

    if($is_local) {
      validateArray($params, ["b4buserid"]);
    }

    if ($params["keyword"] && (strlen($params["keyword"]) < 2)) {
      return json_response(rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Enter minimum 2 symbols")
      ));
    }

    if(!in_array($params["search_type"], [SEARCH_TYPE_QUICK,SEARCH_TYPE_FULL])) {
      $params["search_type"] = SEARCH_TYPE_FULL;
    }

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

    $this->load->model("b4b/products/Search_model", "model");

    $res = $this->model->index($params);
    return json_response($res);
  }

  public function tecdocCrosses()
  {
    checkCompany();
    $params = [
      "b4buserid" => (int)headers("b4buserid") ?: NULL,
      "keyword" => $this->custom_input->get("keyword",[
        "symbols" => ["'","\"","\\","/","`"]
      ]),
    ];

    validateArray($params, ["b4buserid", "keyword"]);

    $this->load->model("b4b/products/Search_model", "model");
    $res = $this->model->tecdocCrosses($params);
    return json_response($res);
  }

  public function comments(){
    checkCompany();

    $params = [
      "b4buserid" => (int)headers("b4buserid") ?: NULL,
      "product_id" => (int)$this->input->get("product_id") ?: NULL,
      // "remoteb4buserid" => (int)headers("remoteb4buserid"),
    ];

    validateArray($params,["product_id", "b4buserid"]);

    $this->load->model("b4b/products/Search_model", "model");
    $res = $this->model->comments($params);
    return json_response($res);
  }

  public function addComments(){
    checkCompany();

    $params = [
      "b4buserid" => (int)headers("b4buserid") ?: NULL,
      "product_id" => (int)$this->input->post("product_id") ?: NULL,
      "entry_product_comment" => $this->input->post("entry_product_comment") ?: NULL,
      "operation_date" => now(),
      // "remoteb4buserid" => (int)headers("remoteb4buserid"),
    ];
    $params["entry_product_comment"] = trim($params["entry_product_comment"]);
    validateArray($params,["entry_product_comment", "product_id", "b4buserid"]);


    $this->load->model("b4b/products/Search_model", "model");
    $res = $this->model->addComments($params);
    return json_response($res);
  }

  public function addPriceOffer(){
    checkCompany();

    $params = [
      "b4buserid" => (int)headers("b4buserid") ?: NULL,
      "remoteb4buserid" => (int)headers("remoteb4buserid") ?: NULL,
      "product_id" => (int)$this->input->post("product_id") ?: NULL,
      "company_name" => $this->input->post("company_name") ?: NULL,
      "price_offer" => $this->input->post("price_offer") ?: NULL,
      "operation_date" => now(),
    ];

    $params["company_name"] = trim($params["company_name"]);
    $params["price_offer"] = trim($params["price_offer"]);
    validateArray($params,["price_offer", "product_id", "b4buserid", "remoteb4buserid", "company_name"]);


    $this->load->model("b4b/products/Search_model", "model");
    $res = $this->model->addPriceOffer($params);
    return json_response($res);
  }

  public function newFromWarehouses()
  {
    $auth_user = checkCompany(null, true);
    $params = [
      "current_date" => date('Y-m-d'),
      "b4buserid" => (int)headers("b4buserid") ?: NULL,
      "remoteb4buserid" => (int)headers("remoteb4buserid") ?: NULL,
      "auth_user" => $auth_user,
    ];

    $this->load->model("b4b/products/Search_model", "model");
    $res = $this->model->newFromWarehouses($params);
    return json_response($res);
  }
}
