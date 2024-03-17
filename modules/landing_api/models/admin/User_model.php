<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model{

  function __construct(){
    parent::__construct();
  }

  private $db_table = 'sa_users';


  function checkUserExist($email = NULL){
    if (!$email) {
      $msg = $this->lang->line("User information has not been provided");
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
    }

    $user_query = $this->db->select("id")->from($this->db_table)->where('email',$email)->where("deleted_at",null)->get();
    $msg = !$user_query->num_rows() ? "user_not_exist" : "user_exist";

    return rest_response(Status_codes::HTTP_OK,$msg,[]);
  }



  function updateInfo($params){
    $user = $params["user"];
    $email = $params["email"];
    $gender = $params["gender"];
    $role = $params["role"];
    $operator = $params["operator"];

    if (!$user || !$email || !$gender || !$role || !$operator) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }

    $u = isAdmin($operator,["developer","main_admin"]);
    if (!$u["status"]) return $u["body"];

    $user_query = $this->db->select("id")->from($this->db_table)->where("id",$user)->where("deleted_at",null)->get();
    if (!$user_query->num_rows()) {
      $msg = $this->lang->line("User not found");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }
    $user_id = $user_query->row()->id;

    if (in_array($role,["user","admin","main_admin","developer"]) && in_array($gender,["male","female"])) {
      $update_array = [
        "gender" => $gender,
        "role" => $role,
        "email" => $email
      ];
      $this->db->where("id",$user_id);
      $this->db->update($this->db_table,$update_array);
    }

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("User information updated"),[]);
  }

  function delete($params){
    $user = $params["user"];
    $operator = $params["operator"];
    $date = $params["date"];

    if (!$user || !$operator) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }

    $u = isAdmin($operator,["developer","main_admin"]);
    if (!$u["status"]) return $u["body"];

    $user_query = $this->db->select("id")->from($this->db_table)->where("id",$user)->where("deleted_at",null)->get();
    if (!$user_query->num_rows()) {
      $msg = $this->lang->line("User not found");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }
    $user_id = $user_query->row()->id;

    $update_array = [
      "deleted_at" => $date
    ];
    $this->db->where("id",$user_id);
    $this->db->update($this->db_table,$update_array);


    return rest_response(Status_codes::HTTP_OK,$this->lang->line("User deleted"),[]);
  }

  function updateStatus($params){
    $user = $params["user"];
    $operator = $params["operator"];
    $blocked = (string)$params["blocked"] === "1" ? "1" : "0";

    if (!$user || !$operator) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }

    $u = isAdmin($operator,["developer","main_admin"]);
    if (!$u["status"]) return $u["body"];

    $user_query = $this->db->select("blocked,id")->from($this->db_table)->where("id",$user)->where("deleted_at",null)->get();
    if (!$user_query->num_rows()) {
      $msg = $this->lang->line("User not found");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }
    $user_row = $user_query->row();
    $user_id = $user_row->id;
    $current_status = $user_row->blocked;

    if ($current_status !== $blocked) {
      $update_array = [
        "blocked" => $blocked,
      ];
      $this->db->where("id",$user_id);
      $this->db->update($this->db_table,$update_array);
    }

    $message = $blocked === "1" ? $this->lang->line("User blocked") : $this->lang->line("User unblocked");
    return rest_response(Status_codes::HTTP_OK,$message,[]);
  }

  function getAll($params){
    $user = $params["user"];
    $role = $params["role"];

    if (!$user) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }

    $u = isAdmin($user,["developer","main_admin"]);
    if (!$u["status"]) return $u["body"];


    $users_query = $this->db->select("id,name,surname,username,birthdate,email,blocked,phone,role,gender,avatar,token,created_at")
                              ->from($this->db_table)
                                ->where($role ? " role = '$role' " : " id > 0 ",NULL,FALSE)
                                  ->where("deleted_at",NULL)
                                    ->order_by("id","desc")
                                      ->get();
    $users = $users_query->result();
    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),$users);
  }


  function addNew($params){
    $user = $params["user"];
    $name = strip_tags($params["name"]);
    $surname = strip_tags($params["surname"]);
    $email = $params["email"];
    $password = $params["password"];
    $phone = strip_tags($params["phone"]);
    $gender = $params["gender"];
    $role = $params["role"];

    if (!$user || !$email || !$password || !$name || !$surname) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameters")
      );
    }

    $u = isAdmin($user,["developer","main_admin"]);
    if (!$u["status"]) return $u["body"];

    if (!in_array($role,['user', 'admin', 'main_admin', 'developer'])) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Unknown user role")
      );
    }


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return rest_response(
        Status_codes::HTTP_METHOD_NOT_ALLOWED,
        $this->lang->line("Invalid email format")
      );
    }

    $token = md5(microtime().rand(1000,99999999));
    $exist_user = $this->db->select("id,token")->from("sa_users")->where("email",$email)->where("deleted_at",null)->get();

    if ($exist_user->num_rows()) {
      return rest_response(
        Status_codes::HTTP_IM_USED,
        $this->lang->line("User already exist")
      );
    }

    $insert_values = [
      "name" => $name,
      "avatar" => "default.png",
      "surname" => $surname,
      "username" => $token,
      "email" => $email,
      "phone" => $phone,
      "gender" => $gender,
      "role" => $role,
      "token" => $token,
      "password" => password_hash($password, PASSWORD_DEFAULT),
    ];
    $this->db->insert("sa_users",$insert_values);
    $user_id = $this->db->insert_id();


    $new_username = Base::slugify($name.".".$surname)."-".($user_id * 99999)*333;
    $this->db->query("UPDATE `sa_users` SET `username` = '$new_username' WHERE `id` = $user_id");

    return rest_response(
      Status_codes::HTTP_CREATED,
      $this->lang->line("User created")
    );
  }


}
