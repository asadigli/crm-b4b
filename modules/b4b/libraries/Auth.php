<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require __DIR__ . "/Api.php";

class Auth extends Api {

  public static $refresh_time_interval    = 60; // 300;

  private static $user_secret_name        = "us_key";

  private static $user_email_secret_name  = "ue_key";

  public static $last_customer           = "uc_key";

  private static $session_datas           = [
    "id",
    "code",
    "name",
    "surname",
    "email",
    // "company_email",
    "phone",
    "official_name",
    "currencies",
    "price_show",
    "avatar",
    "remote_id",
    "supervisor",
    "default_warehouse_id",
    "customers",
    "cart_info",
    "entry_limit",
    "used_limit"
  ];

  public static function setOneSession($key = null,$value = null){
    if (!$key || !$value) {
      return false;
    }
    $_SESSION[$key] = $value;
    return true;
  }


  public static function setSessions($body){

    foreach (self::$session_datas as $data) {
      $_SESSION[$data] = isset($body[$data]) ? (is_string($body[$data]) ? trim($body[$data]) : $body[$data]) : null;
    }

    if (isset($body["entry_token"]) && $body["entry_token"]) {
      $access_token_list = isset($_COOKIE["access_token"]) && $_COOKIE["access_token"] ? @unserialize(@base64_decode($_COOKIE["access_token"])) : [];
      $secret_key = md5("SECRET" . self::email() . "SECRET");
      if (!isset($access_token_list[$secret_key])) {
        $access_token_list[$secret_key] = $body["entry_token"];
      }
      setcookie("access_token", @base64_encode(@serialize($access_token_list)), time() + (86400 * 365 * 10), "/");
    }

    setcookie(self::$user_secret_name, md5(self::user()), time() + (86400 * 365 * 10), "/");
    setcookie(self::$user_email_secret_name, md5("SECRET" . self::email() . "SECRET"), time() + (86400 * 365 * 10), "/");
    return true;
  }

  public static function accessToken($email = null, $hashed = false){
    $secret_key = $email ? ($hashed ? $email : md5("SECRET" . trim($email) . "SECRET")) : md5("SECRET" . self::email() . "SECRET");

    $access_token = isset($_COOKIE["access_token"]) && $_COOKIE["access_token"] ? @base64_decode($_COOKIE["access_token"]) : [];
    $access_token = $access_token ? @unserialize($access_token) : [];
    return isset($access_token[$secret_key]) ? $access_token[$secret_key] : NULL;
  }


  public static function unset($name = null){
    if ($name) {
      if (isset($_SESSION[$name])) {
        unset($_SESSION[$name]);
      }
    } else {
      foreach (self::$session_datas as $data) {
        if (isset($_SESSION[$data])) {
          unset($_SESSION[$data]);
        }
      }

      if (isset($_COOKIE[self::$user_secret_name])) {
        unset($_COOKIE[self::$user_secret_name]);
        setcookie(self::$user_secret_name, null, -1, '/');
      }

      if (isset($_COOKIE[self::$user_email_secret_name])) {
        unset($_COOKIE[self::$user_email_secret_name]);
        setcookie(self::$user_email_secret_name, null, -1, '/');
      }
    }

    return true;
  }


  public static function getValue($name){
    return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
  }


  public static function check($redirect_back = null, $back_uri = null) {
    $is_authed = Auth::user();
    $back_uri = $back_uri ?: $_SERVER["REQUEST_URI"];
    $current_path = $redirect_back ? base_url("auth/login?r=").base64_encode($back_uri) : base_url("auth/login");
    if ($is_authed) {
      return true;
    } else {
      if (isset($_SERVER["HTTP_ATOKEN"])) {
        header('Content-Type: application/json');
        echo json_response(rest_response(Status_codes::HTTP_UNAUTHORIZED,"Unauthorized"));
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
        redirect(path_local());
        die;
      }
    }
  }

  public static function switchUser($customer_id = null, $redirect = true, $response = false) {

    // $user = $user ?: self::getValue("id");

    if (isset($_COOKIE[self::$user_secret_name]) && $_COOKIE[self::$user_secret_name]
            && isset($_COOKIE[self::$user_email_secret_name]) && $_COOKIE[self::$user_email_secret_name]) {
      $res = self::call("POST",endpoint_name("b4b_login"),[
        "entry_token"   => self::accessToken($_COOKIE[self::$user_email_secret_name], true),
        "password_free" => 1,
        "user_secret"   => $_COOKIE[self::$user_secret_name],
        "customer_id"   => $customer_id ?: (isset($_COOKIE[self::$last_customer]) ? $_COOKIE[self::$last_customer] : NULL)
      ]);

      if (isset($res["code"]) && $res["code"] === Status_codes::HTTP_OK) {
        self::setSessions($res["data"]);
        if ($redirect) {
          redirect(base_url("home"));
        }
        return $response ? $res : true;
      } else if(isset($res["code"]) && $res["code"] === Status_codes::HTTP_MOVED_PERMANENTLY) {
        $access_token_list = isset($_COOKIE["access_token"]) && $_COOKIE["access_token"] ? @unserialize(@base64_decode($_COOKIE["access_token"])) : [];
        $secret_key = md5("SECRET" . $params["email"] . "SECRET");
        if (isset($access_token_list[$secret_key])) {
          unset($access_token_list[$secret_key]);
          setcookie("access_token", @base64_encode(@serialize($access_token_list)), time() + (86400 * 7), "/");
        }
        return $response ? $res : false;
      }
    }
  }


  public static function user(){
    $user = self::getValue("id");
    if(!$user) {
      self::switchUser();
    }
    return $user;
  }

  public static function remote_id(){
    return self::getValue("remote_id");
  }

  public static function warehouse_id(){
    return self::getValue("default_warehouse_id");
  }

  public static function email(){
    return self::getValue("email");
  }

  public static function entry_limits($key = "entry_limit"){
    return $key === "used_limit" ? self::getValue("used_limit") : self::getValue("entry_limit");
  }

  public static function currencies(){
    return self::getValue("currencies");
  }

  public static function customers(){
    return self::getValue("customers");
  }

  public static function supervisor($name = null){
    $supervisor = self::getValue("supervisor");
    return $name ? (isset($supervisor[$name]) ? $supervisor[$name] : NULL) : $supervisor;
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


  public static function surname(){
    return self::getValue("surname");
  }


  public static function phone(){
    return self::getValue("phone");
  }


  public static function code(){
    return self::getValue("code");
  }

  public static function currentAccountCurrency(){
    $customers = self::customers();
    $current_account_currency = CURRENCY_EUR;
    if(is_array($customers)){
      foreach ($customers as $key => $item) {
        if($item["is_current"]) {
          $current_account_currency = $item["currency_name"];
        }
      }
    }
    return $current_account_currency;
  }

}
