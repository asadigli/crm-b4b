<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('product/Category_model','model');
  }

  function subcat($slug){
    $type = "subcat";
    $this->load->view('list/categories',['type' => $type,'slug' => $slug]);
  }

  function category($slug){
    $type = "category";
    $this->load->view('list/categories',['type' => $type,'slug' => $slug]);
  }

  function division($slug){
    $type = "division";
    $this->load->view('list/categories',['type' => $type,'slug' => $slug]);
  }

  function subcatLive(){
    $min = $this->input->get('min');
    $max = $this->input->get('max');
    $condition = $this->input->get('condition');
    $value = $this->input->get('value');
    $products = $this->model->subcatLive($min,$max,$value,$condition);
    return json_response($products);
  }

  function categoryLive(){
    $min = $this->input->get('min');
    $max = $this->input->get('max');
    $condition = $this->input->get('condition');
    $value = $this->input->get('value');
    $products = $this->model->categoryLive($min,$max,$value,$condition);
    return json_response($products);
  }

  function divisionLive(){
    $min = $this->input->get('min');
    $max = $this->input->get('max');
    $condition = $this->input->get('condition');
    $value = $this->input->get('value');
    $products = $this->model->divisionLive($min,$max,$value,$condition);
    return json_response($products);
  }

}
