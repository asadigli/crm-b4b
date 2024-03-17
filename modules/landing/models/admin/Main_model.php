<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  function updateInfo($params){
    $data = Base::callAPI("PUT","admin/user/".($params["user"] ?: 0)."/update",$params);
    return $data;
  }

  function updateStatus($params){
    $data = Base::callAPI("PUT","admin/user/".($params["user"] ?: 0)."/status/update",$params);
    return $data;
  }

  function deleteUser($params){
    $data = Base::callAPI("DELETE","admin/user/".($params["user"] ?: 0)."/delete",$params);
    return $data;
  }

  function checkUser($email = NULL){
    $data = Base::callAPI("GET","admin/check-user",['email' => $email]);
    return $data;
  }

  function usersLive($params){
    $data = Base::callApi("GET","admin/users",$params);
    return $data;
  }

  function homeProducts($user){
    $data = Base::callApi("GET","admin/home-products",['user' => $user]);
    return $data['data'];
  }

  function checkCode($code){
    $data = Base::callApi("GET","admin/check-product-code",['code' => $code]);
    return $data['data'];
  }

  function addHP_product($params){
    $data = Base::callApi("POST","admin/add-home-product",$params);
    return $data;
  }

  function updateProductOrders($params){
    $data = Base::callApi("PUT","admin/product/update-orders",$params);
    return $data;
  }

  function updateProductLimit($params){
    $data = Base::callApi("POST","admin/config/update",$params);
    return $data;
  }


  // static pages control
  function pageList($params){
    $data = Base::callAPI("GET","page/list",$params);
    return $data;
  }

  function updatePage($params){
    $data = Base::callAPI("POST","page/update",$params);
    return $data;
  }

  function addFaq($params){
    $data = Base::callAPI("POST","page/add",$params);
    return $data;
  }

  function editFaq($params){
    $data = Base::callAPI("POST","page/update",$params);
    return $data;
  }

  function deleteFaq($params){
    $data = Base::callAPI("POST","page/delete/".$params["id"],["user" => $params["user"]]);
    return $data;
  }


}
