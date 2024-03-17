<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Details extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('product/Product_model','model');
  }

  function index($slug){
    $params = [
      "slug" => $slug,
      "user" => $this->auth_user,
      "user_token" => $this->nonauth_user,
    ];
    $res = $this->model->productView($params);
    if (!isset($res["code"]) || $res["code"] !== 200) {
      $this->redError(404,base_url(),lang("Product not found"));
      die;
    }
    $this->load->view("product/details",['slug' => $slug, 'product' => $res['data'],'similars' => []]);
  }


  function getLatestProducts(){
    $params = [
      "limit" => 8,
      "user" => $this->auth_user,
      "user_token" => $this->nonauth_user,
    ];
    $res = $this->model->getProductsAjax($params);
    return json_response($res);
  }

  function getProducts(){
    $this->load->view("user/products");
  }

  function getCrossReference(){
    $params = [
      "code" => $this->input->get("code")
    ];
    $res = $this->model->getCrossReference($params);
    return json_response($res);
  }

  function similarOEMs(){
    $params = [
      "code" => $this->input->get("code")
    ];
    $res = $this->model->similarOEMs($params);
    return json_response($res);
  }

  function compatibleCars(){
    $params = [
      "code" => $this->input->get("code")
    ];
    $res = $this->model->compatibleCars($params);
    return json_response($res);
  }

  function getByEngine(){
    $params = [
      "code" => $this->input->get("code")
    ];
    $res = $this->model->getByEngine($params);
    return json_response($res);
  }

  function getPartDetails(){
    $params = [
      "code" => $this->input->get("code")
    ];
    $res = $this->model->getPartDetails($params);
    return json_response($res);
  }

  function getProductsAjax(){
    $params = [
      "limit" => 8
    ];
    $res = $this->model->getProductsAjax($params);
    return json_response($res);
  }

  function getSimilars(){
    $params = [
      "user" => $this->auth_user,
      "user_token" => $this->nonauth_user,
      "id" => is_int($this->input->get("product")) ? $this->input->get("product") : null,
      "slug" => !is_int($this->input->get("product")) ? $this->input->get("product") : null,
    ];
    $res = $this->model->getSimilars($params);
    return json_response($res);
  }

  function updateHomeProduct(){
    $params = [
      "product" => $this->input->post("product"),
      "status" => $this->input->post("status"),
      "user" => $this->auth_user,
    ];
    $res = $this->model->updateHomeProduct($params);
    return json_response($res);
  }

  function homeProducts(){
    $params = [
      "user" => $this->auth_user,
      "user_token" => $this->nonauth_user,
    ];
    $res = $this->model->homeProducts($params);
    return json_response($res,"no_auth");
  }



  function categories(){
    $parts = $this->parts;
    return json_response($parts);
  }


  function regions(){
    $data = $this->model->regions();
    return json_response($data);
  }

  function categoryByGroup(){
    $params = [
      'user' => $this->auth_user,
      'user_token' => $this->nonauth_user,
    ];
    $data = $this->model->categoryByGroup($params);
    return json_response($data);
  }



  function productEdit($id){
    if (!$this->admin || !is_numeric($id)) {
      $this->redError(404,base_url(),lang("Page not found"));
      die;
    }
    $params = [
      "id" => $id/123123 - 2342342342
    ];
    $res = $this->model->productView($params);
    if (!isset($res['code']) || $res['code'] !== 200) {
      $this->redError(404,base_url(),lang("Product not found"));die;
    }

    if ($res["data"]["type"] !== "system") {
      redirect(base_url("product/edit-manual/".$id));
      die;
    }
    $this->load->view("product/edit",["data" => $res["data"]]);

  }


  function addNew(){
    if (!$this->admin) {
      $this->redError(404,base_url(),lang("Page not found"));
      die;
    }
    $this->load->view("product/add_new",["title" => lang("Add product")]);

  }

  function groupProducts(){
    $params = [
      "group_id" => $this->input->get("group_id") ?: null,
      "keyword" => $this->input->get("keyword") ?: null,
      "min" => $this->input->get("min") ?: null,
      "max" => $this->input->get("max") ?: null,
      "brand_id" => $this->input->get("brand") ?: null,
      "category_id" => $this->input->get("category") ?: null,
      "subcat_id" => $this->input->get("subcategory") ?: null,
      "sort_by" => $this->input->get("sort_by") ?: null
    ];
    $data = $this->model->groupProducts($params);
    if (isset($data['code']) && $data['code'] === 200) {
      $this->session->set_userdata("group_prods_count",count($data["data"]));
    }
    return json_response($data);
  }

  function changeName(){
    $params = [
      "user" => $this->auth_user,
      "product" => $this->input->post("product"),
      "name" => $this->input->post("name"),
    ];
    $res = $this->model->changeName($params);
    return json_response($res);
  }

  function allCategories(){
    $params = [
      "group_id" => $this->input->get("group"),
    ];
    $res = $this->model->allCategories($params);
    return json_response($res);
  }

  function editManualProduct($id){
    if (!$this->admin) {
      $this->redError(404,base_url(),lang("Page not found"));
      die;
    }

    $params = [
      "id" => $id/123123 - 2342342342
    ];
    $res = $this->model->productView($params);
    if (!isset($res['code']) || $res['code'] !== 200) {
      $this->redError(404,base_url(),lang("Product not found"));die;
    }
    if ($res["data"]["type"] !== "local") {
      redirect(base_url("product/edit/".$id));
      die;
    }
    $this->load->view("product/add_new",[
      "title" => lang("Product_edit"),
      "data" => $res["data"],
      "product" => $params["id"]
    ]);
  }



  function changeDescription(){
    $params = [
      "user" => $this->auth_user,
      "product" => $this->input->post("product"),
      "type" => $this->input->post("type"),
      "value" => $this->input->post("description") ?: null,
    ];
    $res = $this->model->changeDescription($params);
    return json_response($res);
  }


  function updateDetails(){
    $params = [
      "user" => $this->auth_user,
      "list" => $this->input->post("list"),
    ];
    $res = $this->model->updateDetails($params);
    return json_response($res);
  }



}
