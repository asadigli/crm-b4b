<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set("Asia/Baku");

class MY_Controller extends MX_Controller
{

  private $__filter_params;

  public $minify_assets;

  public $inline_styles_css;

  public $inline_styles_scss;

  public $inline_scripts;

  public $minify_styles;

  public $minify_scripts;

  public $extraBeforeJS;
  public $extraModuleJS;
  public $extraJS;
  public $extraBeforeCSS;
  public $extraCSS;
  public $page_class;
  public $page_title;

  function __construct() {
    parent::__construct();
    $this->custom_input = new Input;
    if($this->config->item("current_module_name") === "b4b" || $this->config->item("current_module_name") === "manager"){
      $this->lang->load("text",$this->config->item("current_language"));
    }
    $this->load->driver("cache", [
      "adapter" => "file",
      "backup" => "file"
    ]);


    if ($this->config->item("current_module_name") === "landing") {
      $this->auth_user = $this->remember_me();
      $this->nonauth_user = $this->getCookieToken();

      // roles
      $this->admin = $this->checkAuthMid('role',['admin','main_admin','developer']);
      $this->main_admin = $this->checkAuthMid('role',['main_admin','developer']);
      $this->developer = $this->checkAuthMid('role',['developer']);

      // current language
      $this->local = $this->config->item("current_language");
      $this->language = isset($_SERVER['HTTP_LANGUAGE']) ? $_SERVER['HTTP_LANGUAGE'] : $this->local;

      $this->lang->load("landing/app", $this->local);
      $this->lang->load("landing/contacts", $this->local);
      $this->lang->load("landing/texts", $this->local);
      $this->lang->load("landing/slide", $this->local);
      $this->lang->load("landing/seo", $this->local);

      $this->date = date('Y-m-d H:i:s');
      $this->page_title = "";
      $this->pageType = "";
      $this->headCSS = "";
      $this->extraJS = "";
      $this->extraJSBefore = "";
      $this->OG_image_alt = "";

      $this->metaDesc = "";
      $this->metaKeys = "";

      if (!isset($_SERVER['HTTP_HEADERKEY'])) {
        $this->user_avatar = $this->getAvatar();
        // $this->footer = $this->footerConfig();
      } else {
        header('Content-Type: application/json');
      }
    } else if($this->config->item("current_module_name") === "landing_api") {
      $this->lang = isset($_SERVER['HTTP_LANG']) && in_array($_SERVER['HTTP_LANG'],["az","ru","tr","en"]) ? $_SERVER['HTTP_LANG'] : "az";
    } else {
      $this->lang->load($this->config->item("current_module_name"),$this->config->item("current_language"));
    }


    $this->lang_dom = in_array($this->config->item("current_module_name"),["b4b","manager"])? $this->lang_dom() : [];

    $this->__filter_params = [$this->uri->uri_string()];
    $this->call_filters("before");
  }

  public function _remap($method, $parameters = [])  {
    empty($parameters) ? $this->$method() : call_user_func_array(array($this, $method), $parameters);

    if($method != 'call_filters') {
      $this->call_filters('after');
    }
  }


  private function call_filters($type) {

     $loaded_route = $this->router->get_active_route();
     $filter_list = Route::get_filters($loaded_route, $type);

     foreach($filter_list as $filter_data) {
       $param_list = $this->__filter_params;

       $callback 	= $filter_data['filter'];
       $params = $filter_data['parameters'];

       // check if callback has parameters
       if(!is_null($params)) {
          // separate the multiple parameters in case there are defined
          $params = explode(':', $params);

          // search for uris defined as parameters, they will be marked as {(.*)}
          foreach($params as &$p) {
            if (preg_match('/\{(.*)\}/', $p, $match_p)) {
              $p = $this->uri->segment($match_p[1]);
            }
          }

          $param_list = array_merge($param_list, $params);
        }

        call_user_func_array($callback, $param_list);
      }
  }



  function lang_dom() {
    $body = "";
    $js_langs = $this->config->item("js_langs");

    foreach ($js_langs as $key => $item) {
      $body .= "'{$item["key"]}':'".lang($item["value"])."' " . ($key < count($js_langs) - 1 ? "," : "");
    }
    return "<script data-role=\"js-all-langs\"> var JSallLangs = {" . $body . "}</script>";
  }

  function view($params,$data = []){
    $device = $this->config->item("device_active") ? device() : "";
    if (is_array($params)) {
      foreach ($params as $path) {
        $this->load->view($device . $path,$data);
      }
    }else{
      $this->load->view($device . $params,$data);
    }
  }





  // LANDING FUNCTION STARTS
  function redError($code = NULL,$url = NULL,$msg = NULL){
    $code ? $this->session->set_flashdata("error_code",$code) : "";
    $this->session->set_flashdata("error_prev",$url ? $url : base_url('/'));
    $this->session->set_flashdata("error_mess",$msg ? $msg : "Not found");
    redirect(base_url('error'));
  }

  function checkAuthMid($type,$val){
    return in_array($this->session->userdata($type),$val);
  }

  function remember_me() {
    $this->load->model('user/Auth_model','auth_model');
    $data = $this->auth_model->remember_me();
    return $data;
  }

  function getCookieToken(){
    $key = "user_session_key";
    if (isset($_COOKIE[$key]) && $_COOKIE[$key]) {
      $token = $_COOKIE[$key];
    }else{
      $token = md5(microtime().rand(1000000,9999999999));
      setcookie($key,$token,time() + (10 * 365 * 24 * 60 * 60),'/', NULL, 0);
    }
    return $token;
  }

  function getAvatar(){
    $avt_sess = $this->session->userdata('avatar');
    $avt = base_url($this->config->item('user_avatar_path').$avt_sess);
    $def_avt = assets('img/default.png');
    $path = $this->config->item('user_avatar_path');
    return $avt_sess && $avt_sess !== 'default.png' && file_exists(APPPATH.$path.$avt_sess) ? $avt : $def_avt;
  }
  // function footerConfig(){
  //   $data = getConfig('footer');
  //   return $data;
  // }
  // LANDING FUNCTIONS ENDS
}
