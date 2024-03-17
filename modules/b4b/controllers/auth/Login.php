<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

  function __construct() {
    parent::__construct();
  }

  function resetSessions() {
    $params = [
      "access_token"  => $this->input->get("ext_tk"),
      "entry_token"   => $this->input->get("spc_tk")
    ];


    $this->load->model("auth/Login_model","model");
    $res = $this->model->index($params);

    if (isset($res["code"]) && $res["code"] === Status_codes::HTTP_OK) {
      if (Auth::user()) {
        Auth::unset();
      }

      Auth::setSessions($res["data"]);
      redirect(base_url("home"));
      die;
    } else if(isset($res["code"]) && $res["code"] === Status_codes::HTTP_MOVED_PERMANENTLY) {
      $access_token_list = isset($_COOKIE["access_token"]) && $_COOKIE["access_token"] ? @unserialize(@base64_decode($_COOKIE["access_token"])) : [];
      $secret_key = md5("SECRET" . $params["email"] . "SECRET");
      if (isset($access_token_list[$secret_key])) {
        unset($access_token_list[$secret_key]);
        setcookie("access_token", @base64_encode(@serialize($access_token_list)), time() + (86400 * 7), "/");
      }
    }
    Auth::check(true);
  }

  function index() {
    Auth::guest(true);

    $this->extraJS = [
      "../globals/js/libs/vendors.js",
      "js/pages/login.js",
    ];

    $this->view([
      "auth/layouts/head",
      "auth/login",
      "auth/layouts/footer",
    ]);
  }

  function action(){
    Auth::guest(true);

    $params = [
      "email"         => $this->input->post("email"),
      "password"      => $this->input->post("password"),
      "customer_id"   => isset($_COOKIE[Auth::$last_customer]) ? $_COOKIE[Auth::$last_customer] : NULL
    ];

    $params["entry_token"] = Auth::accessToken($params["email"]);

    $is_valid = ["email","password"];
    $error = false;
    foreach ($is_valid as $key => $item) {
      if (!$params[$item]) {
        $error = true;
        Flash::set("error_{$item}",lang(ucfirst($item) . " is required"));
      }
    }
    if ($error) {
      redirect(base_url("auth/login"));
    }


    $this->load->model("auth/Login_model","model");
    $res = $this->model->index($params);

    if (isset($res["code"]) && $res["code"] === Status_codes::HTTP_OK) {
      Auth::setSessions($res["data"]);
      redirect(base_url("home"));
      die;
    } else if(isset($res["code"]) && $res["code"] === Status_codes::HTTP_MOVED_PERMANENTLY) {
      $access_token_list = isset($_COOKIE["access_token"]) && $_COOKIE["access_token"] ? @unserialize(@base64_decode($_COOKIE["access_token"])) : [];
      $secret_key = md5("SECRET" . $params["email"] . "SECRET");
      if (isset($access_token_list[$secret_key])) {
        unset($access_token_list[$secret_key]);
        setcookie("access_token", @base64_encode(@serialize($access_token_list)), time() + (86400 * 7), "/");
      }
    } else if(isset($res["code"]) && $res["code"] === Status_codes::HTTP_IM_USED){


      Flash::set("error",$res["message"]);
      redirect(base_url("auth/login"));
    }
    Flash::set("last_email",$params["email"]);

    Flash::set("error",isset($res["message"]) ? $res["message"] : lang("INTERNAL SERVER ERROR"));
    redirect(base_url("auth/login"));
  }



}
