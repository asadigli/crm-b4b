<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Carbrand extends MY_Controller{

  function __construct() {
    parent::__construct();
  }

  function details($id){
    $page = [
      'title' => 'Brand name',
      'main_id' => 'brand_list',
    ];
    $this->load->view('list/brands',['page' => $page]);
  }

  function getBrandList(){
    $this->load->model('product/Carbrand_model','gmodel');
    $datas = $this->gmodel->getCarBrands();
    $page = [
      'title' => lang('Auto_brands')
    ];
    $this->load->view('brand_list',['datas' => $datas,'page' => $page]);
  }

  // function getProductList(){
  //
  // }

  function models($brand){
    $this->load->view("product/carbrand/models",["brand" => $brand]);
  }

  function engines($brand,$model){
    $this->load->view("product/carbrand/engines",["brand" => $brand,"model" => $model]);
  }

  function cataloge($brand,$model,$engine){
    $this->load->view("product/carbrand/cataloge",["brand" => $brand,"model" => $model,"engine" => $engine]);
  }

  function products($engine,$category){
    $this->load->view("product/carbrand/products",["engine" => $engine,"category" => $category]);
  }

}
