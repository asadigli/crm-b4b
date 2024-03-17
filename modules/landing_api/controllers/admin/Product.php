<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller{

  function __construct(){
    parent::__construct();
    $this->load->model('admin/Product_model','model');
  }

  function addCategory(){
    $params = [
      'group_id' => $this->input->post('group_id') ?: null,
      'type' => $this->input->post('type') ?: null,
      'name' => $this->input->post('name') ?: null,
      'status' => (string)$this->input->post("status") === "0" ? "0" : "1",
    ];
    $res = $this->model->addCategory($params);
    $this->response($res);
  }

  function getList(){
    $params = [];
    $res = $this->model->getList($params);
    $this->response($res);
  }

  function addProduct(){
    $inputs = $this->input->post();
    $params = [
      // "product" => isset($inputs["product"]) ? $inputs["product"] : null,
      "user" => isset($inputs["user"]) ? $inputs["user"] : null,
      "group_id" => isset($inputs["group_id"]) ? $inputs["group_id"] : null,
      "brand_id" => isset($inputs["brand_id"]) ? $inputs["brand_id"] : null,
      "first_category_id" => isset($inputs["first_category_id"]) ? $inputs["first_category_id"] : null,
      "second_category_id" => isset($inputs["second_category_id"]) ? $inputs["second_category_id"] : null,
      "status" => isset($inputs["status"]) ? $inputs["status"] : null,
      "name" => isset($inputs["name"]) ? $inputs["name"] : null,
      "oem_code" => isset($inputs["oem"]) ? $inputs["oem"] : null,
      "brand_code" => isset($inputs["brand_code"]) ? $inputs["brand_code"] : null,
      "home_product" => isset($inputs["home_product"]) ? $inputs["home_product"] : "0",
      "quantity" => isset($inputs["quantity"]) ? $inputs["quantity"] : null,
      "price" => isset($inputs["price"]) ? $inputs["price"] : null,
      "short_description" => isset($inputs["short_description"]) ? $inputs["short_description"] : null,
      "description" => isset($inputs["description"]) ? $inputs["description"] : null,
      "images" => isset($inputs["images"]) ? $inputs["images"] : [],
      // "exist_images" => isset($inputs["exist_images"]) ? $inputs["exist_images"] : [],
    ];

    // if ($params["product"]) {
    //   $res = $this->model->updateItem($params);
    // }else{
    //   $res = $this->model->addNew($params);
    // }
    $res = $this->model->addNew($params);

    $this->response($res);
  }

  function updateProduct(){
    $params = [
      "user" =>  $this->custom_input->put("user"),
      "product" =>  $this->custom_input->put("product"),
      "group_id" =>  $this->custom_input->put("group_id"),
      "brand_id" =>  $this->custom_input->put("brand_id"),
      "first_category_id" =>  $this->custom_input->put("first_category_id"),
      "second_category_id" =>  $this->custom_input->put("second_category_id"),
      "status" =>  $this->custom_input->put("status"),
      "name" =>  $this->custom_input->put("name"),
      "oem_code" =>  $this->custom_input->put("oem"),
      "brand_code" =>  $this->custom_input->put("brand_code"),
      "home_product" =>  $this->custom_input->put("home_product"),
      "quantity" =>  $this->custom_input->put("quantity"),
      "price" =>  $this->custom_input->put("price"),
      "short_description" =>  $this->custom_input->put("short_description"),
      "description" =>  $this->custom_input->put("description"),
      "images" =>  $this->custom_input->put("images"),
      "exist_images" =>  $this->custom_input->put("exist_images"),
      "date" =>  $this->date,
    ];
    // $this->response($params);die;
    // $res = $this->model->updateProduct($params);
    $res = $this->model->updateItem($params);
    $this->response($res);
  }

  function deleteImage(){
    $params = [
      "image" => $this->custom_input->delete("image") ?: null,
      "date" => $this->date,
    ];
    $res = $this->model->deleteImage($params);
    $this->response($res);
  }

  function delete($product){
    $params = [
      "user" => $this->custom_input->delete("user") ?: null,
      "product" => $product,
      "date" => $this->date,
    ];
    $res = $this->model->delete($params);
    $this->response($res);
  }

  function getProductList(){
    $params = [
      "group_id" => $this->input->get("group_id") ?: null,
      "session" => $this->input->get("session") ?: null,
      "keyword" => $this->input->get("keyword") ?: null,
      "brand_id" => $this->input->get("brand_id") ?: null,
      "category_id" => $this->input->get("category_id") ?: null,
      "subcat_id" => $this->input->get("subcat_id") ?: null,
      "sort_by" => $this->input->get("sort_by") ?: null,
      "limit" => $this->input->get("limit") ?: null,
    ];
    $res = $this->model->getProductList($params);
    $this->response($res);
  }

  function checkBrandCode(){
    $params = [
      "brand_code" => $this->input->get("brand_code") ?: null,
      "oem" => $this->input->get("oem") ?: null,
      "brand" => $this->input->get("brand") ?: null,
      "parent" => $this->input->get("parent") ?: null,
    ];
    $res = $this->model->checkBrandCode($params);
    $this->response($res);
  }

  function get1CBrands(){
    $params = [];
    $res = $this->model->get1CBrands($params);
    $this->response($res);
  }

  function updateCategory(){
    $params = [
      'id' => $this->input->post('id'),
      'name' => $this->input->post('name')
    ];
    // $this->response($params);die;
    $res = $this->model->updateCategory($params);
    $this->response($res);
  }

  function deleteCategory($category){
    $params = [
      "user" => $this->custom_input->delete("user"),
      "category" => $category,
      "date" => $this->date
    ];
    $res = $this->model->deleteCategory($params);
    $this->response($res);
  }

  function updateHomeList(){
    $params = [
      "product" => $this->custom_input->put("product"),
      "user" => $this->custom_input->put("user"),
      "status" => $this->custom_input->put("status"),
    ];
    $res = $this->model->updateHomeList($params);
    $this->response($res);
  }

  function changeStatus(){
    $params = [
      "product" => $this->custom_input->put("product"),
      "user" => $this->custom_input->put("user"),
      "status" => $this->custom_input->put("status"),
    ];
    $res = $this->model->changeStatus($params);
    $this->response($res);
  }

}
