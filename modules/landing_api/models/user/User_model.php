<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  function checkUser($username = null){
    if (!$username) {
      $msg = "'username' cannot be empty";
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }
    $user_query = $this->db->select("id,name,role,surname,username,birthdate,email,phone,
                                        gender,avatar,token")
                                          ->from("sa_users")->where('username',$username)->get();
    if ($user_query->num_rows() == 0) {
      $msg = "User not found";
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }
    $user_data = $user_query->result_array();
    $user_data = isset($user_data[0]) ? $user_data[0] : $user_data;
    return rest_response(Status_codes::HTTP_OK,'success',$user_data);
  }

  function changeAvatar($user = NULL,$avatar = NULL){
    if (!$user || !$avatar) {
      $msg = (!$user ? "'user'" : "'avatar'")." cannot be empty";
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $user_data = $this->db->select("id,avatar")->from("sa_users")->where('token',$user)->get();
    if ($user_data->num_rows() == 0) {
      $msg = "User not found";
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }
    $user = $user_data->row();
    $previous_image = $user->avatar;
    $this->db->where('id',$user->id);
    $this->db->update('sa_users',['avatar' => $avatar]);
    return rest_response(Status_codes::HTTP_OK,'changed',['previous_image' => $previous_image]);
  }

}
