<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('product/Product_model','model');
  }

  function productDetails(){
    $params = [
      "user" => $this->input->get("user"),
      "version" => $this->input->get("version"),

      "user_token" => $this->input->get("user_token"),
      "slug" => $this->input->get("slug"),
      "id" => $this->input->get("id"),
    ];
    $data = $this->model->productDetails($params);
    $this->response($data);
  }


  function getCarBrands(){
    $params = [];
    $data = $this->model->getCarBrands($params);
    $this->response($data);
  }

  function getBrands(){
    $params = [];
    $data = $this->model->getBrands($params);
    $this->response($data);
  }

  function latestProducts(){
    $params = [
      "user" => $this->input->get("user"),
      "version" => $this->input->get("version"),

      "user_token" => $this->input->get("user_token"),
      "keyword" => $this->input->get("keyword"),
      "min" => $this->input->get("min"),
      "max" => $this->input->get("max"),
      "carbrands" => $this->input->get("carbrands"),
      "system_brands" => $this->input->get("system_brands"),
      "brands" => $this->input->get("brands"),
      "main_brands" => $this->input->get("main_brands"),
      "sort_by" => $this->input->get("sort_by"),
      "offset" => $this->input->get("offset"),
      "limit" => $this->input->get("limit"),

      "custom_groups" => $this->input->get("custom_groups"),
      "custom_brands" => $this->input->get("custom_brands"),
      "custom_categories" => $this->input->get("custom_categories"),
      "custom_second_categories" => $this->input->get("custom_second_categories"),
    ];
    $data = $this->model->latestProducts($params);
    $this->response($data);
  }

  function homePageList(){
    $params = [
      'user' => $this->input->get('user'),
      'user_token' => $this->input->get('user_token')
    ];
    $data = $this->model->homePageList($params);
    $this->response($data);
  }

  function getByEngine(){
    $params = [
      "user" => $this->input->get("user"),
      "user_token" => $this->input->get("user_token"),
      "engine" => $this->input->get("engine"),
      "category" => $this->input->get("category"),
      "limit" => $this->input->get("limit"),
      "offset" => $this->input->get("offset"),
      "sort_by" => $this->input->get("sort_by"),
    ];
    $data = $this->model->getByEngine($params);
    $this->response($data);
  }

  function updateProducts(){
    $key = $this->input->get('key');
    if ($key !== $this->config->item("ARM_update_key")) {
      $this->response(rest_response(Status_codes::HTTP_UNAUTHORIZED,"not authorized",[]));die;
    }
    $data = $this->model->updateProducts($this->date);
    $this->response($data);
  }

  function getProductList(){
    $params = [
      "group_id" => $this->input->get("group_id") ?: null,

      // for filtering
      "sort_by" => $this->input->get("sort_by") ?: null,
      "brands" => $this->input->get("brands") ?: null,
      "carbrands" => $this->input->get("carbrands") ?: null,
      "category_id" => $this->input->get("category_id") ?: null,
      "second_category_id" => $this->input->get("second_category_id") ?: null,
      "min" => $this->input->get("min") ?: null,
      "max" => $this->input->get("max") ?: null,


      // for pagination
      "offset" => $this->input->get("offset") ?: null,
      "limit" => $this->input->get("limit") ?: null,

      // for cart and wishlist
      "user" => $this->input->get("user") ?: null,
      "user_token" => $this->input->get("user_token") ?: null,
    ];

    $res = $this->model->getProductList($params);
    $this->response($res);
  }

  function getAllGroups(){
    $params = [
      "group_id" => $this->input->get("group_id") ?: null,
    ];
    $res = $this->model->getAllGroups($params);
    $this->response($res);
  }

  function getByCodes(){
    $params = [
      "codes" => $this->input->get("codes") ?: null,
    ];
    $res = $this->model->getByCodes($params);
    $this->response($res);
  }


  function similarProducts(){
    $params = [
      "id" => $this->input->get("id") ?: null,
      "slug" => $this->input->get("slug") ?: null,
      "limit" => $this->input->get("limit") ?: null,
      "user" => $this->input->get("user") ?: null,
      "offset" => $this->input->get("offset") ?: null,
      "user_token" => $this->input->get("user_token") ?: null,
    ];
    $res = $this->model->similarProducts($params);
    $this->response($res);
  }

  // function addNewProduct(){
  //   $params = [
  //     "product" => (int)$this->input->post("product") ?: null,
  //     "name" => $this->input->post("name") ?: null,
  //     "user" => $this->input->post("user") ?: null,
  //     "brand_code" => $this->input->post("brand_code") ?: null,
  //     "oem" => $this->input->post("oem") ?: null,
  //     "order" => $this->input->post("order") ?: 0,
  //     "group_id" => $this->input->post("group_id") ?: null,
  //     "category_id" => $this->input->post("category_id") ?: null,
  //     "second_category_id" => $this->input->post("second_category_id") ?: null,
  //     "description" => $this->input->post("description") ?: null,
  //     "short_description" => $this->input->post("short_description") ?: null,
  //     "quantity" => $this->input->post("quantity") ?: null,
  //     "price" => $this->input->post("price") ?: null,
  //     "brand" => $this->input->post("brand") ?: null,
  //     "carbrand" => $this->input->post("carbrand") ?: null,
  //
  //
  //     "home_product" => (string)$this->input->post("home_product") === "1" ? "1" : "0",
  //     "status" => (string)$this->input->post("status") === "0" ? "0" : "1",
  //   ];
  //
  //   $res = $this->model->addNewProduct($params);
  //   $this->response($res);
  // }

  function updateProductDetails(){
    $params = [
      "user" => $this->custom_input->put("user") ?: null,
      "list" => $this->custom_input->put("list")
    ];
    $res = $this->model->updateProductDetails($params);
    $this->response($res);
  }


  function addOrDeleteHomeProduct(){
    $params = [
      "user" => $this->input->post("user") ?: null,
      "product" => $this->input->post("product") ?: null,
      "status" => (string)$this->input->post("status") === "1" ? "1" : "0",
    ];
    $res = $this->model->addOrDeleteHomeProduct($params);
    $this->response($res);
  }

  function updateProductName(){
    $params = [
      "user" => $this->custom_input->put("user"),
      "product" => $this->custom_input->put("product"),
      "name" => $this->custom_input->put("name")
    ];
    $res = $this->model->updateProductName($params);
    $this->response($res);
  }

  function updateDescription(){
    $params = [
      "user" => $this->custom_input->put("user"),
      "product" => $this->custom_input->put("product"),
      "type" => $this->custom_input->put("type"),
      "value" => $this->custom_input->put("value")
    ];
    $res = $this->model->updateDescription($params);
    $this->response($res);
  }

}
