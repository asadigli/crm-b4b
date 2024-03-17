<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller{

  public function __construct() {
    parent::__construct();
    checkHeaders();
    $this->load->model("manager/auth/Login_model","model");
  }

  public function action() {
    $params = [
      "email" => $this->input->post("email") ?: null,
      "password" => $this->input->post("password") ?: null,
      "remember_me" => $this->input->post("remember_me") ?: null,
      "user_ip" => headers("useraddress"),
      "user_agent" => headers("useragent"),
      // "entry_token" => $this->input->post("entry_token") ?: null,
    ];
    // $params["remember_me"] = $params["remember_me"] ? ((string)$params["remember_me"] === "1" ? true : false) : NULL;
    $params["email"] = trim($params["email"]);
    $params["email"] = str_replace(["'", "\""], "", $params["email"]);
    $params["password"] = trim($params["password"]);
    $params["password"] = str_replace(["'", "\""], "", $params["password"]);

    validateArray($params,["email","password"]);
    $res = $this->model->index($params);
    return json_response($res, true);
  }

  function loginWithToken(){
    $params = [
      "user_ip" => headers("useraddress"),
      "token" => $this->input->post("token"),
      "user_agent" => headers("useragent"),
    ];
    validateArray($params,["user_ip","token"]);
    $res = $this->model->loginWithToken($params);
    return json_response($res);
  }

}
