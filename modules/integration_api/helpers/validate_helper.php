<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists("validateUrl")) {
  function validateUrl($url) {
    $valid_url = filter_var($url, FILTER_VALIDATE_URL);
    if (!$valid_url) {
      return json_response(rest_response(
        Status_codes::HTTP_CONFLICT,
        lang("Wrong url format")
      ));
    }
  }
}

if (!function_exists("validateItemByGroup")) {
  function validateItemByGroup($group, array $groups, $name = null, $key = false) {

    if ($key && !array_key_exists($group, $groups)) {
      return json_response(rest_response(
        Status_codes::HTTP_CONFLICT,
        lang("Undefined parameter") . ($name ? ": $name" : "")
      ));
    }
     if (!$key && !in_array($group, $groups)) {
       return json_response(rest_response(
         Status_codes::HTTP_CONFLICT,
         lang("Undefined parameter") . ($name ? ": $name" : "")
       ));
     }

   }
}

if (!function_exists("escapeAllKeys")) {
  function escapeAllKeys($params,$keys = [],$database_connection = null){
    if (!$params || !is_array($params)) {
      return [];
    }
    if ($database_connection) {
      $CI = get_instance();
      $database_connection = $CI->load->database(LOCAL_DB, TRUE);
    }
    if ($keys) {
      foreach ($keys as $item) {
        $params[$item] = isset($params[$item]) && $params[$item] && (is_string($params[$item]) || is_int($params[$item])) ? $database_connection->escape_str($params[$item]) : $params[$item];
        $params[$item] = $params[$item] && is_string($params[$item]) ? trim($params[$item]) : $params[$item];
      }
    } else {
      foreach (array_keys($params) as $item) {
        $params[$item] = $params[$item] && (is_string($params[$item]) || is_int($params[$item])) ? $database_connection->escape_str($params[$item]) : $params[$item];
        $params[$item] = $params[$item] && is_string($params[$item]) ? trim($params[$item]) : $params[$item];
      }
    }
    return $params;
  }
}

if (!function_exists("validateBlob")) {
  function validateBlob($code = null,$message = null){
    if(!$code) return false;
    if(strlen($code) < 30 || strlen($code) > 40 || $code[1] !== "x" || $code[0] !== "0" || preg_match('/[^A-Z0-9]+/', substr($code, 2))) {
      json_response(rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        $message ?: lang("Invalid blob id")
      ));
      die;
    }
    return true;
  }
}

if (!function_exists("validateDatetime")) {
  function validateDatetime($date, $format = 'Y-m-d\TH:i:s') {
      // var_dump(strtotime(1));die;
      if (!strtotime($date)) {
        json_response(rest_response(
          415,lang("Invalid date format")
        ));
      } else {
        return true;
      }
  }
}

if (!function_exists("validateDate")) {
  function validateDate($date, $format = 'Y-m-d') {
      // var_dump(strtotime(1));die;
      if (!strtotime($date)) {
        return json_response(rest_response(
          415,lang("Invalid date format")
        ));
      } else {
        $newDate = date($format, strtotime($date));
        // var_dump($newDate);die;
        if ($date !== $newDate) {
          return json_response(rest_response(
            415,lang("Invalid date format")
          ));
        } else {
          return true;
        }
      }
  }
}

if (!function_exists("validateName")) {
  function validateName($value = null){
    if(!$value || strlen($value) < 2) return false;

    return (bool)preg_match('~[0-9]+~', $value) || (bool)preg_match("/[a-z]/i", $value);
  }
}

if (!function_exists("validatePhone")) {
  function validatePhone($value = null){
    if(!$value) return false;
    // '000-0000-0000';
    // '994-55-111-11-11';
    return preg_match("/^[0-9]{12}$/", $value);
  }
}

if (!function_exists("validateEmail")) {
  function validateEmail($value = null){
    if(!$value) return false;
    $valid_email = filter_var($value, FILTER_VALIDATE_EMAIL);
    if (!$valid_email) {
      return json_response(rest_response(
        Status_codes::HTTP_CONFLICT,
        lang("app.Invalid email format")
      ));
    }
  }
}

if (!function_exists("validateArray")) {
  function validateArray($params = [],$keys = [], $deliminiter = "OR"){
    if(!$params) return false;
    $status = true;
    $data = [];
    foreach ($params as $index => $param) {
      foreach ($keys as $index_sub => $key) {
        if (!isset($params[$key]) || (!$params[$key])) {
          $data[] = $key;
          $status = false;
        }
      }
    }
    if (!$status) {
      json_response(rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Missed parameters"),
         array_unique($data)
      ));die;
    }
  }
}
