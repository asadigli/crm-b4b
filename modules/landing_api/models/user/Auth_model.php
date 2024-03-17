<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Auth_model extends CI_Model{

  function __construct()
  {
    parent::__construct();
    // $this->load->database();
  }

  function resetPasswordToken($email = NULL,$date){
    if (!$email) {
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,'Enter email',[]);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$this->lang->line("Invalid email format"),[]);
    }

    $exist_user = $this->db->select("id")
                            ->from("sa_users")
                              ->where("email",$email)
                                ->where("blocked","0")
                                  ->where("deleted_at",null)
                                    ->get();
    if (!$exist_user->num_rows()) {
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,'User not found',[]);
    }
    $key = bin2hex(openssl_random_pseudo_bytes(40));
    $user_id = $exist_user->row()->id;
    $this->db->where('user_id',$user_id);
    $this->db->update('sa_password_reset_keys',['expire_at' => $date]);
    $this->db->insert('sa_password_reset_keys',['user_id' => $user_id,'token' => $key]);

    return rest_response(Status_codes::HTTP_OK,'success',['key' => $key]);

  }

  function checkResetPassword($token = NULL){
    $exist_key = $this->db->select("*")
                            ->from("sa_password_reset_keys")
                              ->where("token",$token)
                                ->where("expire_at",NULL)
                                  ->get();
    if ($exist_key->num_rows() == 0) {
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,"Token not found",[]);
    }

    return rest_response(Status_codes::HTTP_OK,"success",[]);
  }

  function changePassword($token = NULL,$password = NULL,$confirm_password = NULL,$date){
    if (!$token || !$password || !$confirm_password) {
      $msg = 'Missed parameters';
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    if ($password !== $confirm_password) {
      $msg = $this->lang->line("Not matched password");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $update_values = [
      'password' => password_hash($password, PASSWORD_DEFAULT)
    ];

    $exit_token = $this->db->select("id,user_id")
                            ->from("sa_password_reset_keys")
                              ->where("token",$token)
                                ->where("expire_at",NULL)
                                  ->limit(1)
                                    ->get();

    if ($exit_token->num_rows() == 0) {
      $msg = "Token not found";
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $id = $exit_token->row()->id;
    $user_id = $exit_token->row()->user_id;

    $this->db->where("id",$id);
    $this->db->update("sa_password_reset_keys",["expire_at" => $date]);

    $this->db->where("id",$user_id);
    $this->db->update("sa_users",$update_values);

    return rest_response(Status_codes::HTTP_OK,'success',[]);
  }

  function register($params){
    $email = $params["email"];
    $password = $params["password"];
    $confirm_password = $params["confirm_password"];
    $name = strip_tags($params["name"]);
    $surname = strip_tags($params["surname"]);
    $birthdate = $params["birthdate"];
    $phone = strip_tags($params["phone"]);
    $type = $params["type"];
    $gender = $params["gender"];
    $token_needed = $params["token_needed"];
    $user_token = strip_tags($params["user_token"]);

    if (!$email || !$password || !$confirm_password || !$name || !$surname) {
      return rest_response(Status_codes::HTTP_CONFLICT,$this->lang->line("Missed parameters"),[]);
    }

    if ($password !== $confirm_password) {
      return rest_response(Status_codes::HTTP_CONFLICT,$this->lang->line("Not matched password"),[]);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$this->lang->line("Invalid email format"),[]);
    }

    $token = md5(microtime().rand(1000,99999999));
    $username = Base::slugify($name.".".$surname).'-'.uniqid();
    $exist = $this->db->select("id,token")->from("sa_users")->where('email',$email)->where("deleted_at",null)->get();
    if ($exist->num_rows()) {
      return rest_response(Status_codes::HTTP_IM_USED,$this->lang->line("User already exist"),['token' => $exist->row()->token]);
    }
    $insert_values = [
      "name" => $name,
      "surname" => $surname,
      "avatar" => "default.png",
      "username" => $username,
      "birthdate" => $birthdate,
      "email" => $email,
      "phone" => $phone,
      "gender" => $gender,
      "password" => password_hash($password, PASSWORD_DEFAULT),
      "token" => $token,
    ];
    $this->db->insert("sa_users",$insert_values);
    $user_id = $this->db->insert_id();

    if ($user_token) {
      $this->db->where("user_key",$user_token)->where("deleted_at",NULL)->where("user_id",NULL);
      $this->db->update("sa_cart",["user_id" => $user_id]);


      $this->db->where("user_key",$user_token)->where("deleted_at",NULL)->where("user_id",NULL);
      $this->db->update("sa_wishlist",["user_id" => $user_id]);
    }

    $list = [
      "name" => $name,
      "surname" => $surname,
      "username" => $username,
      "avatar" => "default.png",
      "birthdate" => $birthdate,
      "email" => $email,
      "phone" => $phone,
      "role" => "user",
      "gender" => $gender,
      "token" => $token,
      "verified" => "0"
    ];
    $resp = $token_needed ? ['token' => $token] : $list;
    return rest_response(Status_codes::HTTP_OK,$this->lang->line("User created"),$resp);
  }

  function updateUserInfo($params){
    $user = $params["user"];
    $type = $params["type"];
    $value = strip_tags($params["value"]);
    if (!$type || $value) {
      return rest_response(Status_codes::HTTP_CONFLICT,$this->lang->line("Missed parameters") ,[]);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$this->lang->line("Invalid email format"),[]);
    }

    $exist_user = $this->db->select("id")->from("sa_users")->where('id',$user)->get();
    if (!$exist_user->num_rows()) {
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$this->lang->line("User not found"),[]);
    }
    if (!in_array($type,['name','surname','username','birthdate','email','phone','gender'])) {
      return rest_response(Status_codes::HTTP_CONFLICT,'Unknown type' ,[]);
    }

    $this->db->where('id',$user);
    $this->db->update('sa_users',[$type => $value]);

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("User information updated"),[]);
  }

  function login($email = null,$password = null,$remember_me = null,$user_token = NULL){
    if (!$email || !$password) {
      $error_msg = sprintf($this->lang->line("_ cannot be NULL"),(!$email ? $this->lang->line("email") : $this->lang->line("password")));
      return rest_response(Status_codes::HTTP_CONFLICT,$error_msg,[]);
    }
    $user_query = $this->db->select("id,name,role,surname,username,birthdate,avatar,
                                    email,phone,gender,password,token,remember_me,verified")
                                  ->from("sa_users")
                                    ->where("email",$email)
                                      ->where("blocked","0")
                                        ->where("deleted_at",null)
                                          ->limit(1)
                                            ->get();
    if ($user_query->num_rows() == 0) {
      $msg = $this->lang->line("User not found");
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
    }
    $user = $user_query->row();
    if (!password_verify($password,$user->password)) {
      $msg = $this->lang->line("Email or password is not correct");
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
    }

    $user_id = $user->id;
    $user = (array)$user;
    $new_user_data = [
      "token" => $user['token'],
      "name" => $user['name'],
      "role" => $user['role'],
      "surname" => $user['surname'],
      "username" => $user['username'],
      "birthdate" => $user['birthdate'],
      "avatar" => $user['avatar'],
      "email" => $user['email'],
      "phone" => $user['phone'],
      "gender" => $user['gender'],
      "remember_me" => $user['remember_me'],
      "verified" => $user['verified'],
    ];
    $remember_me_token = NULL;
    if ($remember_me) {
      $remember_me_token = $user['remember_me'];
      if (!$user['remember_me']) {
        $remember_me_token = bin2hex(openssl_random_pseudo_bytes(40));
        $this->db->where('id',$user_id);
        $this->db->update('sa_users',['remember_me' => $remember_me_token]);
      }
    }

    if ($user_token) {
      $this->db->where('user_key',$user_token)->where('deleted_at',NULL)->where('user_id',NULL);
      $this->db->update('sa_cart',['user_id' => $user_id]);

      $this->db->where('user_key',$user_token)->where('deleted_at',NULL)->where('user_id',NULL);
      $this->db->update('sa_wishlist',['user_id' => $user_id]);
    }

    $body = array_merge($new_user_data,['remember_me' => $remember_me_token]);
    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Logged In Text"),$body);
  }

  function login_with_token($token = null){
    if (!$token) {
      $error_msg = "'token' cannot be NULL";
      return ['code' => Status_codes::HTTP_CONFLICT,'message' => $error_msg,'response' => []];
    }
    $user_query = $this->db->select("id,name,role,surname,username,birthdate,avatar,
                                email,phone,gender,password,token,verified")
                                  ->from("sa_users")
                                    ->where("remember_me",$token)
                                      ->where("blocked","0")
                                        ->where("deleted_at",null)
                                          ->limit(1)
                                            ->get();

    if ($user_query->num_rows() > 0) {
      $user = $user_query->row();
      $success_msg = 'Logged in';
      $u_id = $user->id;

      $user = (array)$user;
      $new_user_data = [
        // "id" => $user['id'],
        "token" => $user["token"],
        "name" => $user["name"],
        "role" => $user["role"],
        "surname" => $user["surname"],
        "username" => $user["username"],
        "birthdate" => $user["birthdate"],
        "avatar" => $user["avatar"],
        "email" => $user["email"],
        "phone" => $user["phone"],
        "gender" => $user["gender"],
        "verified" => $user["verified"],
      ];

      return rest_response(Status_codes::HTTP_OK,$success_msg,$new_user_data);
    }

    $success_msg = "'user' user not found";
    return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$success_msg,[]);
  }

  function verifyAccountToken($user = NULL,$date){
    if (!$user) {
      $msg = "'user' cannot be NULL";
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $exist_user = $this->db->select("id,verified")
                            ->from("sa_users")
                              ->where("token",$user)
                                ->where("blocked","0")
                                  ->where("deleted_at",null)->get();
    if ($exist_user->num_rows() == 0) {
      $msg = "User not found";
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    if ((int)$exist_user->row()->verified) {
      return rest_response(Status_codes::HTTP_CONFLICT,'User"s already verified',[]);
    }


    $user_id = $exist_user->row()->id;
    $token = bin2hex(random_bytes(40));

    $this->db->where('user_id',$user_id);
    $this->db->update('`sa_verify_user_tokens`',['expire_at' => $date]);

    $this->db->insert('`sa_verify_user_tokens`',['user_id' => $user_id,'token' => $token]);
    return rest_response(Status_codes::HTTP_OK,'success',['key' => $token]);
  }

  function verifyAccount($token = NULL,$date){
    if (!$token) {
      $msg = "'token' cannot be NULL";
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }
    $exist_token = $this->db->select("id,user_id")->from("`sa_verify_user_tokens`")
                                ->where('token',$token)
                                  ->where('expire_at',NULL)->get();
    if ($exist_token->num_rows() == 0) {
      $msg = "Token is not valid";
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }


    $this->db->where('id',$exist_token->row()->user_id);
    $this->db->update('`sa_users`',["verified" => "1"]);


    $this->db->where('id',$exist_token->row()->id);
    $this->db->update('`sa_verify_user_tokens`',['expire_at' => $date]);

    return rest_response(Status_codes::HTTP_OK,'User verified');
  }

}
