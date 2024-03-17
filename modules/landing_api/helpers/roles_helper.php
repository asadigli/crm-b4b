<?php

if (!function_exists('isAdmin')) {
  function isAdmin($token = null,$roles = ["developer","admin","main_admin"]){
    if(!$token) return false;
    $CI = get_instance();
    $exist_user = $CI->db->select("id")->from("sa_users")
                        ->where("token",$token)->where("deleted_at",null)
                          ->where_in("role",$roles)
                            ->limit(1)->get();
    if (!$exist_user->num_rows()) {
      return [
        "status" => false,
        "body" => rest_response(
          Status_codes::HTTP_NOT_FOUND,
          $CI->lang->line("User not found"),
          []
        )
      ];
    }
    return [
      "status" => true,
      "body" => [
        "id" => $exist_user->row()->id
      ]
    ];
  }
}


if (!function_exists('isDev')) {
  function isDev($token = null){
    if(!$token) return false;
    $CI = get_instance();
    $exist_user = $CI->db->select("id")->from("sa_users")
                        ->where("token",$token)->where("deleted_at",null)
                          ->where_in("role",["developer"])
                            ->limit(1)->get();
    if (!$exist_user->num_rows()) {
      return [
        "status" => false,
        "body" => rest_response(
          Status_codes::HTTP_NOT_FOUND,
          $CI->lang->line("User not found"),
          []
        )
      ];
    }
    return [
      "status" => true,
      "body" => [
        "id" => $exist_user->row()->id
      ]
    ];
  }
}


if (!function_exists('isMainAdmin')) {
  function isMainAdmin($token = null){
    if(!$token) return false;
    $CI = get_instance();
    $exist_user = $CI->db->select("id")->from("sa_users")
                        ->where("token",$token)->where("deleted_at",null)
                          ->where_in("role",["main_admin"])
                            ->limit(1)->get();
    if (!$exist_user->num_rows()) {
      return [
        "status" => false,
        "body" => rest_response(
          Status_codes::HTTP_NOT_FOUND,
          $CI->lang->line("User not found"),
          []
        )
      ];
    }
    return [
      "status" => true,
      "body" => [
        "id" => $exist_user->row()->id
      ]
    ];
  }
}
