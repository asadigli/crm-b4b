<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends MY_Controller{

  function __construct(){
    parent::__construct();
  }

  /**
  *
  *
  */
  // function index($slug){
  // $this->load->model('Menu_model','model');
  //   $data = $this->model->getPageDetails($slug,$this->local);
  //   if ($data['code'] !== 200) {redirect(base_url('/'));}
  //   $res = isset($data['data'][$this->local]) ? $data['data'][$this->local] : [];
  //   $page = [
  //     'title' => $res['title'],
  //   ];
  //   $this->load->view('pages/index',['page' => $page,'data' => $res]);
  // }


  /**
  *
  *
  */

  function about(){
    $this->load->model("Pages_model","model");
    $params = [
      "type" => "about"
    ];
    $data = $this->model->faqOrAbout($params);
    $page = [
      'title' => lang("Brand"),
    ];
    $this->load->view('pages/about',['page' => $page,"data" => $data]);
  }

  function contact(){
    $contacts = [
      [
        "person" => "",
        "position" => "Headquarters",
        "tel" => "+000000000",
        "mob" => "+000000000",
        "email" => "info@test.loc"
      ],

    ];
    $page = [
      'title' => lang('Contact_us'),
    ];

    $this->load->view('pages/contact',['page' => $page,"contacts" => $contacts]);
  }

  /**
  *
  *
  */
  function privacy_policies(){
    $page = [
      'title' => lang('Privacy_policies'),
    ];
    $this->load->view('pages/privacy_policies',['page' => $page]);
  }

  /**
  *
  *
  */
  function terms_and_conditions(){
    $page = [
      'title' => lang('Terms_and_conditions'),
    ];
    $this->load->view('pages/terms_and_conditions',['page' => $page]);
  }

  /**
  *
  *
  */
  function faq(){
    $this->load->model("Pages_model","model");
    $params = [
      "type" => "faq"
    ];
    $data = $this->model->faqOrAbout($params);
    $page = [
      "title" => lang("FAQ"),
    ];
    $this->load->view("pages/faq",["page" => $page,"data" => $data]);
  }

  /**
  *
  *
  */
  function getStaticPageData($slug){
    $datas = $this->config->item("menu_items");
    $name = '';$id = '';
    foreach (array_keys($datas) as $key => $item) {
      if ($datas[$item]["slug"] === $slug) {
        $name = $item;
        $id = $datas[$item]["id"];
      }
    }
    // echo json_encode($datas);die;
    $this->load->view('list/static_page',["name" => lang($name),"id" => $id]);
  }

}
