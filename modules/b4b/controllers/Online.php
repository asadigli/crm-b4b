<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Online extends MY_Controller {

  function __construct() {
    parent::__construct();
  }

  function index() {
    $params = [
      "url_path"    => $this->input->post("url_path"),
      "entry_token" => Auth::accessToken()
    ];

    $this->load->model("Online_model","model");
    $res = $this->model->index($params);

    if (isset($res["code"]) && $res["code"] === Status_codes::HTTP_UNAUTHORIZED) {
      $access_token_list = isset($_COOKIE["access_token"]) && $_COOKIE["access_token"] ? @unserialize(@base64_decode($_COOKIE["access_token"])) : [];
      $secret_key = md5("SECRET" . Auth::email() . "SECRET");
      if (isset($access_token_list[$secret_key])) {
        unset($access_token_list[$secret_key]);
        setcookie("access_token", @base64_encode(@serialize($access_token_list)), time() + (86400 * 7), "/");
      }
      Auth::unset();
    }
    return json_response($res);
  }

}
