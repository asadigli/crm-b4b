<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  function profile($username = null){
    $list = ['username' => $username];
    $data = Base::callAPI('GET','user/check',$list);
    return $data;
  }

  function verifyAccount($token = null){
    $list = ['token' => $token];
    $data = Base::callAPI('POST','user/authorization/verify-account',$list);
    return $data;
  }

  function changeAvatar($user = NULL, $avatar = NULL){
    $list = ['user' => $user,'avatar' => $avatar];
    $data = Base::callAPI('POST','user/avatar/change',$list);
    return $data;
  }

}
