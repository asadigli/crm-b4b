<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  function subcatLive($min = null,$max = null,$value = null,$condition = null){
    $list = [
      'min' => $min,
      'max' => $max,
      'condition' => $condition,
      'type' => 'subcat'
    ];
    $data = Base::callApi("GET","category/".$value,$list);
    return isset($data['data']) ? $data['data'] : [];
  }

  function categoryLive($min = null,$max = null,$value = null,$condition = null){
    $list = [
      'min' => $min,
      'max' => $max,
      'condition' => $condition,
      'type' => 'category'
    ];
    $products = Base::callApi("GET","category/".$value,$list);
    return isset($products['data']) ? $products['data'] : [];
  }

  function divisionLive($min = null,$max = null,$value = null,$condition = null){
    $list = [
      'min' => $min,
      'max' => $max,
      'condition' => $condition,
      'type' => 'division'
    ];
    $products = Base::callApi("GET","category/".$value,$list);
    return isset($products['data']) ? $products['data'] : [];
  }

}
