<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Auth_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  function register($email = null,$name = null,$surname = null,$birthdate = null,$gender = null,$password = null,$cpassword = null,$phone = null,$user_token = NULL){
    $list = [
      'email' => $email,
      'name' => $name,
      'surname' => $surname,
      'birthdate' => $birthdate,
      'gender' => $gender,
      'password' => $password,
      'confirm_password' => $cpassword,
      'phone' => $phone,
      'user_token' => $user_token
    ];
    $data = Base::callAPI('POST','user/authorization/register',$list);
    return $data;
  }

  function login($email = null,$password = null,$remember = null,$user_token = NULL){
    $list = ['email' => $email,'password' => $password,'remember_me' => $remember,'user_token' => $user_token];
    $data = Base::callAPI('POST','user/authorization/login',$list);
    return $data;
  }

  function send_otp($list = []){
    $name_from = $list['name_from'];
    $mail_from = $list['mail_from'];
    $mail_to = $list['mail_to'];
    $subject = $list['subject'];
    $message = $list['message'];
    $res = Base::sendEmail($name_from,$mail_from,$mail_to,$subject,$message);
    return $res;
  }

  function getResetToken($email = NULL) {
    $list = [
      'email' => $email
    ];
    $data = Base::callAPI('POST','user/authorization/reset-password-token',$list);
    return $data;
  }

  function password_reset_view($token = NULL){
    $list = ['token' => $token];
    $data = Base::callAPI('POST','user/authorization/check-password-token',$list);
    return $data;
  }

  function change_password($token = NULL,$password = NULL,$confirm_password = NULL){
    $list = [
      'token' => $token,
      'password' => $password,
      'confirm_password' => $confirm_password
    ];
    $data = Base::callAPI('POST','user/authorization/change-password',$list);
    return $data;
  }

  function remember_me() {
    // if (get_cookie('userToken')) {setcookie('userToken', null, -1,base_url('/'));}
    $key_name = $this->config->item('remember_key_name');
    if (!$this->session->userdata('token') && isset($_COOKIE[$key_name]) && $_COOKIE[$key_name]) {
      $list = ["token" => $_COOKIE[$key_name]];
      $data = Base::callAPI('POST','user/authorization/login-with-token',$list);
      if ($data['code'] == 200) {
        $row = $data['data'];
        $this->session->set_userdata([
          'token' => $row['token'],
          'birthdate' => $row['birthdate'],
          'email' => $row['email'],
          'gender' => $row['gender'],
          'name' => $row['name'],
          'surname' => $row['surname'],
          'avatar' => $row['avatar'] !== 'default.png' ? $row['avatar'] : NULL,
          'username' => $row['username'],
          'phone' => $row['phone'],
          'role' => $row['role']
        ]);
      }
    }
    return $this->session->userdata('token');
  }


  function verifyEmail($user = NULL,$lang = null){
    $list = ['user' => $user];
    $data = Base::callAPI('POST','user/authorization/verify-account-token',$list);
    if ($data['code'] == 200) {
      $url = base_url('profile',$lang).$this->session->userdata('username').'?token='.$data['data']['key'];

      $name_from = $this->config->item('PWD_reset_name');
      $mail_from = $this->config->item('PWD_reset_email');
      $mail_to = $this->session->userdata('email');
      $subject = lang('Verify_password_subject');
      $message = sprintf(lang('Verify_email_text'),$url);
      $res = Base::sendEmail($name_from,$mail_from,$mail_to,$subject,$message);
    }
    return $data;
  }


}
