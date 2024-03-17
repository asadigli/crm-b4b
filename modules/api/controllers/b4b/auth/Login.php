<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller{

  public function __construct() {
    parent::__construct();
    checkHeaders();
    $this->load->model("b4b/auth/Login_model","model");
  }

  public function action() {
    $params = [
      "email"           => $this->input->post("email") ?: null,
      "entry_device"    => headers("userdevice"),
      "entry_ip"        => headers("useraddress"),
      "password"        => $this->input->post("password") ?: null,
      "password_free"   => (string)$this->input->post("password_free")  === STATUS_ACTIVE,
      "get_token"       => (string)$this->input->post("get_token")      === STATUS_ACTIVE,
      "url_path"        => $this->input->post("url_path") ?: null,
      "entry_token"     => $this->input->post("entry_token") ?: null,
      "customer_id"     => $this->input->post("customer_id") ?: null,
      "user_secret"     => $this->input->post("user_secret") ?: null,
      "access_token"    => $this->input->post("access_token") ?: null,
    ];

    // if (!$params["password_free"]) {
    //   $params["email"] = trim($params["email"]);
    //   $params["email"] = str_replace(["'", "\""], "", $params["email"]);
    //   $params["password"] = trim($params["password"]);
    //   validateArray($params,["email","password"]);
    // } else {
    //   validateArray($params,["entry_token","user_secret"]);
    // }

    if (!$params["password_free"] && !$params["access_token"]) {
      $params["email"] = trim($params["email"]);
      $params["email"] = str_replace(["'", "\""], "", $params["email"]);
      $params["password"] = trim($params["password"]);
      validateArray($params,["email","password"]);
    } else if($params["access_token"]) {
      validateArray($params,["entry_token"]);
    } else {
      validateArray($params,["entry_token","user_secret"]);
    }

    $res = $this->model->index($params);

    if (isset($res["code"]) && $res["code"] === Status_codes::HTTP_OK) {

      if (!$params["get_token"]) {

        $res["data"]["default_warehouse_id"]  = $res["data"]["default_warehouse_id"] === STATUS_DEACTIVE ? NULL : $res["data"]["default_warehouse_id"];
        $res["data"]["price_show"]            = $res["data"]["price_show"] === STATUS_ACTIVE;
        $res["data"]["stock_show"]            = $res["data"]["stock_show"] === STATUS_ACTIVE;
      }


      if ($params["entry_token"]) {
        $token_result = $this->model->checkValidityOfToken(["user_id" => $res["data"]["id"],"entry_token" => $params["entry_token"],"device" => headers("userdevice")]);
        if (isset($token_result["code"]) && $token_result["code"] === Status_codes::HTTP_OK) {
          $res["data"]["entry_token"] = $token_result["data"]["token"];
        } else {
          $token_data = $this->model->getValidEntryToken(["user_id" => $res["data"]["id"],"device" => headers("userdevice")]);
          if (isset($token_data["code"]) && $token_data["code"] === Status_codes::HTTP_OK) {
            $res["data"]["entry_token"] = $token_data["data"]["token"];
          } else {
            return json_response($token_result);
          }
        }
      }else{
        $token_data = $this->model->getValidEntryToken(["user_id" => $res["data"]["id"],"device" => headers("userdevice")]);
        if (isset($token_data["code"]) && $token_data["code"] === Status_codes::HTTP_OK) {
          $res["data"]["entry_token"] = $token_data["data"]["token"];
        } else {
          return json_response(rest_response(
            Status_codes::HTTP_IM_USED,
            lang("You are out of limit")
          ));
        }
      }


      // $this->load->model("b4b/onlines/Add_model","online_model");
      // $this->online_model->index([
      //   "b4buserid" => $res["data"]["id"],
      //   "company_device" => headers("userdevice"),
      //   "company_ip" => headers("useraddress"),
      //   "online_at"  => now(),
      //   "entry_token" => $res["data"]["entry_token"],
      //   "url_path" => $params["url_path"]
      // ]);
    }
    return json_response($res);
  }
}
