<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model('Search_model','model');
  }

  function searchSuggestions(){
    $keyword = $this->input->get('keyword');
    $data = $this->model->searchSuggestions($keyword);
    $this->response($data);
  }

  function findByAjax(){
    $keyword = $this->input->get('keyword');
    $min = $this->input->get('min');
    $max = $this->input->get('max');
    $division = $this->input->get('division');
    $category = $this->input->get('category');
    $subcategory = $this->input->get('subcategory');
    $car_brand = $this->input->get('car_brand');
    $car_model = $this->input->get('car_model');
    $condition = $this->input->get('condition');
    $result = $this->model->findByAjax($keyword,$min,$max,$division,$category,$subcategory,$car_brand,$car_model,$condition,$this->date);
    $this->response($result);
  }

  function panelSearchCategories(){
    $model = $this->input->get('model');
    $marka = $this->input->get('marka');
    $year = $this->input->get('year');
    $result = $this->model->panelSearchCategories($model,$marka,$year);
    $this->response($result);
  }

  function listProducts(){
    $unique_key = $this->input->get('unique_key');
    $result = $this->model->listProducts($unique_key);
    $this->response($result);
  }

}
