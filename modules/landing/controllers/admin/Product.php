<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller{

  function __construct(){
    parent::__construct();
    $this->load->model("admin/Product_model","model");
    if (!$this->admin) {redirect(base_url('/'));}
  }

  /**
  *
  *
  */
  function productAddView(){
    $menus = $this->config->item("menu_items");
    $title = lang("Add new product");
    $this->view('admin/product/add',[
      "title" => $title,
      "menus" => $menus
    ]);
  }

  /**
  *
  *
  */
  function editView($id){
    $params = [
      "user" => $this->auth_user,
      "version" => "full",
      "id" => $id
    ];
    $res = $this->model->view($params);
    if (!isset($res["code"]) || $res["code"] !== 200) {
      $this->redError(404,base_url(),lang("Page not found"));
      die;
    }
    // return json_response($res,"no_auth");die;

    $title = lang("Product_edit");
    $this->view("admin/product/edit",[
      "title" => $title,
      "id" => $id,
      "data" => $res["data"]
    ]);
  }

  /**
  *
  *
  */
  function categoryList(){
    $params = [];
    $res = $this->model->categoryList($params);
    return json_response($res);
  }

  /**
  *
  *
  */
  function productListView(){
    $datas = $this->config->item("menu_items");
    $title = lang('Product list');
    $this->view('admin/product/list',['groups' => $datas]);
  }

  /**
  *
  *
  */
  function categoryListView(){
    $datas = $this->config->item("menu_items");
    $title = lang("Category control");
    $this->view("admin/product/category/list",["groups" => $datas,"title" => $title]);
  }

  /**
  *
  *
  */
  function updateCategory(){
    $params = [
      'id' => $this->input->post('id'),
      'name' => $this->input->post('name')
    ];
    $res = $this->model->updateCategory($params);
    return json_response($res);
  }

  /**
  *
  *
  */
  function deleteCategory($category){
    $params = [
      "user" => $this->auth_user,
      "category" => $category,
    ];
    $res = $this->model->deleteCategory($params);
    return json_response($res);
  }

  /**
  *
  *
  */
  function get1CBrands(){
    $params = [];
    $res = $this->model->get1CBrands($params);
    return json_response($res);
  }

  /**
  *
  *
  */
  function checkBrandCode(){
    $params = [
      "brand_code" => $this->input->get("brand_code") ?: null,
      "oem" => $this->input->get("oem") ?: null,
      "brand" => $this->input->get("brand") ?: null,
      "parent" => $this->input->get("parent") ?: null,
    ];
    $res = $this->model->checkBrandCode($params);
    return json_response($res);
  }

  /**
  *
  *
  */
  function addProduct(){
    $params = [
      "brand_id" => $this->input->post("brand") ?: null,
      "category_id" => $this->input->post("category") ?: null,
      "group_id" => $this->input->post("group") ?: null,
      "subcat_id" => $this->input->post("subcat") ?: null,
      "productIDs" => $this->input->post("productIDs"),
      "prod_name" => $this->input->post("prod_name") ?: null,
      "prod_description" => $this->input->post("prod_description") ?: null,
    ];

    // return json_response($params);die;
    $res = $this->model->addProduct($params);
    return json_response($res);
  }

  /**
  *
  *
  */
  function getMNproducts(){
    $params = [
      "group_id" => $this->input->get("group") ?: null,
      "category_id" => $this->input->get("category") ?: null,
      "session" => $this->input->get("session") ?: null,
      "subcat_id" => $this->input->get("subcat") ?: null,
    ];
    $res = $this->model->getMNproducts($params);
    return json_response($res);
  }

  /**
  *
  *
  */
  function addCategory(){
    $type = $this->input->post("type");
    $params = [
      "group_id" => $this->input->post("group"),
      "name" => $this->input->post("name"),
      "type" => $this->input->post("type"),
      // "parent_id" => $type !== 'brand' ? $this->input->post("parent") : null
    ];
    $res = $this->model->addCategory($params);
    return json_response($res);
  }

  /**
  *
  *
  */
  function removeFromHomeList(){
    $params = [
      "user" => $this->auth_user,
      "product" => $this->input->post("product"),
      "status" => 0,
    ];
    $res = $this->model->updateHomeList($params);
    return json_response($res);
  }

  /**
  *
  *
  */
  function updateHomeList(){
    $params = [
      "user" => $this->auth_user,
      "product" => $this->input->post("product"),
      "status" => $this->input->post("status"),
    ];
    $res = $this->model->updateHomeList($params);
    return json_response($res);
  }

  /**
  *
  *
  */
  function updateStatus(){
    $params = [
      "user" => $this->auth_user,
      "product" => $this->input->post("product"),
      "status" => $this->input->post("status"),
    ];
    $res = $this->model->updateStatus($params);
    return json_response($res);
  }

  /**
  *
  *
  */
  function delete($product){
    $params = [
      "user" => $this->auth_user,
      "product" => $product,
    ];
    $res = $this->model->delete($params);
    return json_response($res);
  }


  /**
  *
  *
  */
  function updateProduct(){
    $params = [
      "user" => $this->auth_user,
      "product" => $this->input->post("product"),
      "name" => $this->input->post("name"),
      "brand_code" => $this->input->post("brand_code"),
      "oem" => $this->input->post("oem"),
      "status" => $this->input->post("status"),
      "group_id" => $this->input->post("group_id"),
      "first_category_id" => $this->input->post("category_id"),
      "second_category_id" => $this->input->post("second_category_id"),
      "description" => $this->input->post("description"),
      "short_description" => $this->input->post("short_description"),
      "quantity" => $this->input->post("quantity"),
      "price" => $this->input->post("price"),
      "brand_id" => $this->input->post("brand"),
      "carbrand" => $this->input->post("carbrand"),
      "home_product" => $this->input->post("home_product"),
      "images" => $this->input->post("images"),
      "exist_images" => $this->input->post("exist_images"),
    ];

    $data = $this->model->updateProduct($params);
    return json_response($data);
  }

  /**
  *
  *
  */
  function getProducts(){
    $params = [
      "user" => $this->auth_user,
      "version" => "full",

      "sort_by" => $this->input->get("sort_by"),
      "keyword" => $this->input->get("keyword"),
      // "system_brands" => $this->input->get("brands"),
      // "brands" => $this->input->get("brands"),
      "carbrands" => $this->input->get("carbrands"),
      // "main_brands" => $this->input->get("main_brands"),
      "system_brands" => $this->input->get("main_brands"),

      "custom_groups" => $this->input->get("filt_group"),
      "custom_brands" => $this->input->get("filt_brands"),
      "custom_categories" => $this->input->get("filt_categories"),
      "custom_second_categories" => $this->input->get("filt_2nd_categories"),



      "limit" => $this->input->get("limit") ?: 12,
      "offset" => $this->input->get("page") && $this->input->get("page") > 1 ? ($this->input->get("page") - 1)*12 : 0,
    ];
    $data = $this->model->getProducts($params);
    return json_response($data);
  }

  function addNewProduct(){
    $params = [
      "user" => $this->auth_user,
      "name" => $this->input->post("name"),
      "brand_code" => $this->input->post("brand_code"),
      "oem" => $this->input->post("oem"),
      "status" => $this->input->post("status"),
      "group_id" => $this->input->post("group_id"),
      "first_category_id" => $this->input->post("category_id"),
      "second_category_id" => $this->input->post("second_category_id"),
      "description" => $this->input->post("description"),
      "short_description" => $this->input->post("short_description"),
      "quantity" => $this->input->post("quantity"),
      "price" => $this->input->post("price"),
      "brand_id" => $this->input->post("brand"),
      "carbrand" => $this->input->post("carbrand"),
      "home_product" => $this->input->post("home_product"),
      "images" => $this->input->post("images"),
    ];
    $data = $this->model->addNewProduct($params);
    return json_response($data);
  }

}
