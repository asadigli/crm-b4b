<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model{

  function __construct(){
    parent::__construct();
  }

  private $bs_url = "admin/product/";

  function categoryList($params){
    $data = Base::callAPI("GET",$this->bs_url."category/list",$params);
    return $data;
  }


  function view($params){
    $res = Base::callAPI('GET','product/details',$params);
    return $res;
  }

  function checkBrandCode($params){
    $data = Base::callAPI("GET",$this->bs_url."check-brand-code",$params);
    return $data;
  }


  function addProduct($params){
    $data = Base::callAPI("POST",$this->bs_url."add-new",$params);
    if (isset($data["code"]) && in_array((int)$data["code"],[200,201])) {
      Services::getHomeProducts([],true);
    }
    return $data;
  }

  function updateProduct($params){
    $data = Base::callAPI("PUT",$this->bs_url."update",$params);
    if (isset($data["code"]) && in_array((int)$data["code"],[200,201])) {
      Services::getHomeProducts([],true);
    }
    return $data;
  }

  function get1CBrands($params){
    $data = Base::callAPI("POST",$this->bs_url."get-1c-brands",$params);
    return $data;
  }

  function addCategory($params){
    $data = Base::callAPI("POST",$this->bs_url."add-category",$params);
    return $data;
  }

  function getMNproducts($params){
    $data = Base::callAPI("GET",$this->bs_url."get-list",$params);
    return $data;
  }

  function updateCategory($params){
    $data = Base::callAPI("POST",$this->bs_url."category/update",$params);
    return $data;
  }

  function deleteCategory($params){
    $data = Base::callAPI("DELETE",$this->bs_url."category/".($params["category"] ?: 0)."/delete",$params);
    return $data;
  }

  function updateHomeList($params){
    $res = Base::callApi("PUT",$this->bs_url."home-list/update",$params);
    if (isset($data["code"]) && in_array((int)$data["code"],[200,201])) {
      Services::getHomeProducts([],true);
    }
    return $res;
  }

  function updateStatus($params){
    $res = Base::callApi("PUT",$this->bs_url."status/update",$params);
    if (isset($data["code"]) && in_array((int)$data["code"],[200,201])) {
      Services::getHomeProducts([],true);
    }
    return $res;
  }


  function getProducts($params){
    $data = Base::callAPI('GET','products/latest',$params);
    return $data;
  }

  function addNewProduct($params){
    $res = Base::callAPI("POST",$this->bs_url."add-new",$params);
    if (isset($data["code"]) && in_array((int)$data["code"],[200,201])) {
      Services::getHomeProducts([],true);
    }
    return $res;
  }

  function delete($params){
    $res = Base::callApi("DELETE",$this->bs_url."/".($params["product"] ?: 0)."/delete",$params);
    if (isset($data["code"]) && in_array((int)$data["code"],[200,201])) {
      Services::getHomeProducts([],true);
    }
    return $res;
  }


  function updateBrandOrder($params){
    $res = Base::callApi("PUT","brand/ordering",$params);
    return $res;
  }
}
