<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('product/Search_model','model');
  }

  function getSuggestions(){
    $keyword = $this->input->get('keyword');
    $data = $this->model->getSuggestions($keyword);
    return json_response($data['data']);
  }

  function find(){
    $this->load->model('product/Carbrand_model','brands_model');
    $datas = $this->brands_model->getCarBrands();
    // $this->model->find()
    $menus = $this->config->item("menu_items");
    $this->load->view('product/search',[
      "datas" => $datas,
      "menus" => $menus
    ]);
  }

  function getSitemap($products) {
    $path = APPPATH."../";
    $langs = ["","en/","tr/","ru/"];

    for ($num=1; $num <= 8; $num++) {
      $html_string = "";
      foreach ($products as $key_prod => $product) {
        // if (($key_size === 0 && $key_prod > $size && $key_prod < $sizes[$key_size + 1]) && ($key_size === (count($sizes) - 1) && $key_prod < $size && $key_prod > $sizes[$key_size - 1])) {
        if($key_prod >= (int)($num . "0000") && $key_prod < (int)(($num + 1) . "0000")){
          foreach ($langs as $key_lang => $lang) {
            $html_string .= '<url><loc>http://customer.loc/'.$lang.'product/'.$product["slug"].'</loc><lastmod>2021-09-08T19:16:52+00:00</lastmod><priority>1.00</priority></url>';
          }
        }
      }

      $data = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"><!--  created with Free Online Sitemap Generator www.xml-sitemaps.com  -->'.$html_string.'</urlset>';
      file_put_contents($path . "sitemap-".$num.".xml", $data);
    }

    return $data;
  }

  function searchAjax(){
    $params = [
      "sort_by" => $this->input->get('sort_by'),
      "keyword" => $this->input->get('keyword'),
      "brands" => $this->input->get('brands'),
      "main_brands" => $this->input->get('main_brands'),
      "carbrands" => $this->input->get('carbrands'),
      "page" => $this->input->get('page'),
      "limit" => $this->input->get('limit'),
      // "limit" => 100000,

      "custom_groups" => $this->input->get('filt_group'),
      "custom_brands" => $this->input->get('filt_brands'),
      "custom_categories" => $this->input->get('filt_categories'),
      "custom_second_categories" => $this->input->get('filt_2nd_categories'),
    ];
    if ($params["keyword"]) {
      $new_keywords = $this->session->userdata("last_search_keyword") ?: [];
      $new_keywords = array_reverse($new_keywords);
      $key = array_search($params["keyword"], $new_keywords);
      if ($key) {
        unset($new_keywords[$key]);
      }
      array_push($new_keywords,$params["keyword"]);
      $new_keywords = array_reverse($new_keywords);
      count($new_keywords) > 20 ? array_pop($new_keywords) : "";
      $this->session->set_userdata("last_search_keyword",$new_keywords);
    }
    $products = $this->model->searchAjax($params);

    // var_dump($this->getSitemap($products["products"]));die;
    return json_response($products);
  }

  function panelSearch(){
    $this->load->model('product/Carbrand_model','brands_model');
    $models = $this->brands_model->getCarBrands();
    $this->load->view('list/panel_search',['models' => $models]);
  }

  function panelSearchAjax(){
    $model = $this->input->get('model');
    $marka = $this->input->get('marka');
    $year = $this->input->get('year');
    $data = $this->model->panelSearchAjax($model,$marka,$year);
    return json_response($data);
  }

  function listProducts(){
    $key = $this->input->get('key');
    $data = $this->model->listProducts($key);
    return json_response(isset($data['data']) ? $data['data'] : []);
  }

  // function getBrands(){
  //   $params = [];
  //   $brands = $this->model->getBrands($params);
  //   $carbrands = $this->model->getCarBrands($params);
  //   return json_response([
  //     "brands" => (isset($brands["data"]) ? $brands["data"] : []),
  //     "carbrands" => (isset($carbrands["data"]) ? $carbrands["data"] : [])
  //   ]);
  // }

  function getBrandsForAdmin(){
    $params = [
      "group_id" => $this->input->get("group_id")
    ];
    $this->load->model('product/Product_model','prod_model');
    $data = $this->prod_model->categoryByGroup($params);
    return json_response($data,"no_auth");
  }

  function getBrands(){
    $params = [
      "group_id" => $this->input->get("group_id")
    ];
    $this->load->model('product/Product_model','prod_model');
    $data = $this->prod_model->categoryByGroup2($params);
    return json_response($data,"no_auth");
  }

}
