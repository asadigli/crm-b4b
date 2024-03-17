<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
class User extends MY_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('user/Auth_model','model');
    $this->load->model('user/User_model','umodel');
  }

  function changeAvatar(){
    $user = $this->input->post('user');
    $avatar = $this->input->post('avatar');
    $data = $this->umodel->changeAvatar($user,$avatar);
    $this->response($data);
  }

  function resetPasswordToken() {
    $email = $this->input->post('email');
    $data = $this->model->resetPasswordToken($email,$this->date);
    $this->response($data);
  }

  function checkResetPassword() {
    $token = $this->input->post('token');
    $data = $this->model->checkResetPassword($token);
    $this->response($data);
  }

  function changePassword(){
    $token = $this->input->post('token');
    $password = $this->input->post('password');
    $confirm_password = $this->input->post('confirm_password');
    $data = $this->model->changePassword($token,$password,$confirm_password,$this->date);
    $this->response($data);
  }

  function register(){
    $params = [
      "email" => $this->input->post('email'),
      "password" => $this->input->post('password'),
      "confirm_password" => $this->input->post('confirm_password'),
      "name" => $this->input->post('name'),
      "surname" => $this->input->post('surname'),
      "birthdate" => $this->input->post('birthdate'),
      "phone" => $this->input->post('phone'),
      "type" => $this->input->post('type'),
      "gender" => $this->input->post('gender'),
      "token_needed" => $this->input->post('token_needed'),
      "user_token" => $this->input->post('user_token')
   ];
    $response = $this->model->register($params);
    $this->response($response);
  }

  function updateUserInfo(){
    $params = [
      "user" => $this->input->post("user"),
      "type" => $this->input->post("type"),
      "value" => $this->input->post("value")
    ];
    $response = $this->model->updateUserInfo($params);
    $this->response($response);
  }

  function login(){
    $email = $this->input->post('email');
    $password = $this->input->post('password');
    $remember_me = $this->input->post('remember_me');
    $user_token = $this->input->post('user_token');
    $response = $this->model->login($email,$password,$remember_me,$user_token);
    $this->response($response);
  }

  function verifyAccount(){
    $token = $this->input->post('token');
    $data = $this->model->verifyAccount($token,$this->date);
    $this->response($data);
  }

  function verifyAccountToken($user = NULL){
    $user = $this->input->post('user');
    $data = $this->model->verifyAccountToken($user,$this->date);
    $this->response($data);
  }

  function login_with_token(){
    $token = $this->input->post('token');
    $data = $this->model->login_with_token($token);
    $this->response($data);
  }

  function checkUser(){
    $username = $this->input->get('username');
    $response = $this->umodel->checkUser($username);
    $this->response($response);
  }
}
