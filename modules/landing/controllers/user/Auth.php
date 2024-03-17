<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('user/Auth_model','model');
  }

  protected $admin_redirect = "admin/dashboard";

  function login_page(){
    if ($this->auth_user) {redirect(base_url('/'));}
    $page = [
      'title' => lang('Login')
    ];
    $this->load->view('user/auth/login',['page' => $page]);
  }

  // function send_otp(){
  //   if ($this->auth_user) {redirect(base_url('/'));}
  //   $email = $this->input->post('email');
  //   $base_url = base_url('authorization/password-reset');
  //   $data = $this->model->getResetToken($email);
  //   // var_dump($data);die;
  //   if (!isset($data['code'])) {
  //     $this->redError(500,$base_url,"Internal Error");die;
  //   }
  //   if ($data['code'] !== 200) {
  //     $this->session->set_flashdata("type","danger");
  //     $this->session->set_flashdata("message",$data['message']);
  //     redirect($base_url);
  //   }
  //   $list = [
  //     'name_from' => $this->config->item('PWD_reset_name'),
  //     'mail_from' => $this->config->item('PWD_reset_email'),
  //     'mail_to' => $email,
  //     'subject' => lang('Reset_your_password'),
  //     'message' => lang('Reset_via_email_text')." <a href=".$base_url.'?token='.$data['data']['key']." target='_blank'>".lang('Click_here')."</a>",
  //   ];
  //   $res = $this->model->send_otp($list);
  //   $this->session->set_flashdata("type",$res['code'] == 200 ? "success" : "danger");
  //   $this->session->set_flashdata("message",$res['message']);
  //   redirect($base_url);
  // }

  function register_page(){
    if ($this->auth_user) {redirect(base_url('/'));}
    $page = ['title' => lang('Register')];
    $this->load->view('user/auth/register',['page' => $page]);
  }

  function email_confirmation(){
    if ($this->auth_user) {redirect(base_url('/'));}
    $page = [
      'title' => lang('Email_confirmation')
    ];
    $this->load->view('user/auth/email_confirmation',['page' => $page]);
  }

  function password_reset_view(){
    if ($this->auth_user) {redirect(base_url('/'));}
    $token = $this->input->get('token');
    if ($token) {
      $res = $this->model->password_reset_view($token);
      if (!isset($res['code'])) {
        $this->redError(500,'','Internal Error');die;
      }
      if ($res['code'] !== 200) {
        $this->session->set_flashdata("type","danger");
        $this->session->set_flashdata("message","Token not found");
      }
    }
    $page = ['title' => lang('Reset_password')];
    $this->load->view('user/auth/reset_password',['page' => $page]);
  }

  function change_password(){
    if ($this->auth_user) {redirect(base_url('/'));}
    $base_url = base_url('sign-in',$this->local());
    $token = $this->input->post('otp_key');
    $password = $this->input->post('password');
    $confirm_password = $this->input->post('confirm_password');
    $data = $this->model->change_password($token,$password,$confirm_password);
    $this->session->set_flashdata("type",$data['code'] == 200 ? "success" : "danger");
    $this->session->set_flashdata("message",$data['message']);
    if ($data['code'] == 200) {
      redirect($base_url);
    }else{
      redirect(base_url('password-reset',$this->local()));
    }
  }

  function register(){
    if ($this->auth_user) {die;}
    $email = $this->input->post('email');
    $name = $this->input->post('name');
    $surname = $this->input->post('surname');
    $birthdate = $this->input->post('birthdate');
    $gender = $this->input->post('gender');
    $password = $this->input->post('password');
    $cpassword = $this->input->post('cpassword');
    $phone = $this->input->post('phone');
    $user_token = $this->nonauth_user;
    $data = $this->model->register($email,$name,$surname,$birthdate,$gender,$password,$cpassword,$phone,$user_token);
    if ($data['code'] == 200) {
      $row = $data['data'];
      $this->session->set_userdata([
        'token' => $row['token'],
        'avatar' => $row['avatar'] !== 'default.png' ? $row['avatar'] : NULL,
        'birthdate' => $row['birthdate'],
        'email' => $row['email'],
        'gender' => $row['gender'],
        'name' => $row['name'],
        'surname' => $row['surname'],
        'username' => $row['username'],
        'phone' => $row['phone'],
        'verified' => (int)$row['verified'],
        'role' => 'user',
      ]);
    }
    return json_response(['code' => $data['code'],'redirect' => $this->input->post('redirect') ? base64_decode($this->input->post('redirect')) : NULL]);
  }

  function login(){
    // echo "what";die;
    if ($this->admin) {
      // return json_response(['message' => 'You are already logged in']);die;
      $this->session->set_flashdata("message", "Already logged in");
      redirect(base_url("admin/login"));
    }
    $email = $this->input->post('email');
    $user_token = $this->nonauth_user;
    $password = $this->input->post('password');
    $remember = $this->input->post('remember');
    $data = $this->model->login($email,$password,$remember,$user_token);
    // return json_response(["what"]);die;

    if (isset($data['code']) && $data['code'] == 200) {
      $row = $data['data'];
      if (!in_array($row['role'],['admin','main_admin','developer'])) {
        $this->session->set_flashdata('message',"Wrong credentials");
        redirect(base_url("admin/login"));
      }
      $this->session->set_userdata([
        'token' => $row['token'],
        'birthdate' => $row['birthdate'],
        'avatar' => $row['avatar'] !== 'default.png' ? $row['avatar'] : NULL,
        'email' => $row['email'],
        'gender' => $row['gender'],
        'name' => $row['name'],
        'surname' => $row['surname'],
        'username' => $row['username'],
        'phone' => $row['phone'],
        'verified' => (int)$row['verified'],
        'role' => $row['role'],
      ]);
      setcookie($this->config->item("remember_key_name"),$row['remember_me'],time() + (10 * 365 * 24 * 60 * 60));
      $redirect = ['redirect' => $this->input->post('redirect') ? base64_decode($this->input->post('redirect')) : $this->admin_redirect];
      redirect(base_url($redirect));
      die;
    }
    $this->session->set_flashdata('message', (isset($data['message']) ? $data['message'] : lang('Internal_Server_Error')));
    redirect(base_url("admin/login"));
  }

  function verifyEmail(){
    $user = $this->auth_user;
    $data = $this->model->verifyEmail($user,$this->local());
    return json_response($data);
  }


  function logout(){
    if (!$this->auth_user) {redirect(base_url('/'));}
    setcookie($this->config->item("remember_key_name"), null, -1,base_url('/'));
    $this->session->unset_userdata(['token','birthdate','email','gender','name','surname','username','phone','type','verified','role','page_slug']);
    redirect(base_url('/'));
  }

}
