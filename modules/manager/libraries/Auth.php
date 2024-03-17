<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth {

  public static $refresh_time_interval = 60; // 300;

  private static $datas = [
    "id",
    "name",
    "lastname",
    "surname",
    "admin_email",
    "phone",
    "photo",
    "ava_manager",
    "password",
    "new_password",
    "group_id",
    "group_name",
    "dashboard",
    "allowed_order_groups",
    "is_developer",
    "role"
  ];

  public static function setOneSession($key = null,$value = null){
    if (!$key || !$value) {
      return false;
    }
    $_SESSION[$key] = $value;
    return true;
  }


  public static function setSessions($body){
    $CI = get_instance();

    foreach (self::$datas as $data) {
      $_SESSION[$data] = isset($body[$data]) ? $body[$data] : null;
    }

    if (isset($body["token"]) && $body["token"]) {
      setcookie("access_token", $body["token"], time() + (86400 * 7), "/");
    }

    $rm_key_name = $CI->config->item("remember_key_name");
    if ($body["remember_me_token"]) {
      setcookie($rm_key_name,$body["remember_me_token"],time() + $CI->config->item("remember_me_time"),"/");
    } else {
      setcookie($rm_key_name, '', -1,"/");
    }

    return true;
  }


  public static function unset($name = null){
    $CI = get_instance();

    if ($name) {
      if (isset($_SESSION[$name])) {
        unset($_SESSION[$name]);
      }
    } else {
      foreach (self::$datas as $data) {
        if (isset($_SESSION[$data])) {
          unset($_SESSION[$data]);
        }
      }
      $rm_key_name = $CI->config->item("remember_key_name");
      setcookie($rm_key_name, '', -1,"/");
    }

    return true;
  }


  public static function getValue($name){
    $session_data = isset($_SESSION[$name]) ? $_SESSION[$name] : null;

    if (!$session_data) {
      $CI = get_instance();

      $rm_key_name = $CI->config->item("remember_key_name");
      if (!$rm_key_name) {
        return false;
      }
      $token = isset($_COOKIE[$rm_key_name]) ? $_COOKIE[$rm_key_name] : NULL;
      if ($token) {
        $CI->load->model("auth/Login_model","auth_login_model");

        $cookie_value = md5(microtime()) . md5(uniqid());

        $users = isset($users["users"]) ? $users["users"] : [];

        $data = $CI->auth_login_model->loginWithToken([
          "token" => $token,
        ]);

        if (isset($data["code"])) {
          if ($data["code"] === 200) {
            self::setSessions($data["data"]);
            return $CI->session->userdata($name);
          } else if(in_array($data["code"],[204])) {
            setcookie($rm_key_name, '', -1,"/");
            return false;
          }
        } else {
          return false;
        }
      } else {
        return false;
      }
    }

    return $session_data;
  }


  public static function check($redirect_back = null, $roles = [], $back_uri = null) {
    if (self::role() === "developer") {
      return true;
    }
    $is_authed = Auth::user();
    $is_role_authed = true;
    if ($is_authed && $roles) {
      $is_role_authed = in_array(self::role(),$roles);
      $is_authed = $is_role_authed;
    }

    if ($is_role_authed) {
      $back_uri = $back_uri ?: $_SERVER['REQUEST_URI'];
      $current_path = $redirect_back ? base_url("auth/login?r=").base64_encode($back_uri) : base_url("auth/login");
    } else {
      $current_path = $redirect_back ? base_url($back_uri) : base_url("profile");
    }


    if ($is_authed) {
      return true;
    } else {
      if (isset($_SERVER['HTTP_HEADERKEY'])) {
        header('Content-Type: application/json');
        echo json_response(rest_response(
          Status_codes::HTTP_UNAUTHORIZED,
          lang("Unauthorized")
        ));
        die;
      }
      Flash::set("login_error", lang("Your session ended because of long time inactivity"));
        redirect($current_path);
        die;
    }
  }


  public static function guest($redirect = false) {
    $is_authed = Auth::user();
    if (!$is_authed) {
      return true;
    } else {
      if (!$redirect) {
        return false;
      } else {
        $CI = get_instance();
        $su_roles = $CI->config->item("su_roles");
        $redirect_back = isset($su_roles[self::role()]["default_page"]) ? $su_roles[self::role()]["default_page"] : "home";
        redirect(path_local($redirect_back));
        die;
      }
    }
  }


  public static function user(){
    return self::getValue("id");
  }

  public static function remote_id(){
    return self::getValue("remote_id");
  }

  public static function role(){
    return self::getValue("role");
  }

  public static function allowedOrderGroups(){
    return self::getValue("allowed_order_groups");
  }

  public static function checkRole($roles = []){
    $role = self::role();
    if (!$role) {
      return false;
    }
    if($role === "developer") {
      return true;
    }
    return $role && $roles && in_array($role,$roles);
  }

  public static function email(){
    return self::getValue("admin_email");
  }

  public static function currencies(){
    return self::getValue("currencies");
  }

  public static function avatar(){
    return self::getValue("avatar");
  }

  public static function price_show(){
    return self::getValue("price_show");
  }


  public static function official_name(){
    return self::getValue("official_name");
  }


  public static function name(){
    return self::getValue("name");
  }

  public static function group_name(){
    return self::getValue("group_name");
  }


  public static function surname(){
    return self::getValue("lastname");
  }

  public static function fullname(){
    return self::getValue("name") . " " .  self::getValue("lastname");
  }

  public static function phone(){
    return self::getValue("phone");
  }


  public static function code(){
    return self::getValue("code");
  }

  public static function isDeveloper(){
    return self::getValue("role") === ROLE_DEVELOPER;
  }

  public static function isMainDeveloper(){
    return self::getValue("is_developer");
  }


}
