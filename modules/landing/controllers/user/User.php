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
    $this->load->model('user/User_model','model');
  }

  function profile($username = null){
    if (!$this->auth_user) {redirect(base_url('/'));}
    $data = $this->model->profile($username);
    if ($data['code'] !== 200) {
      $this->redError(500,NULL,isset($data['message']) ? $data['message'] : lang('Internal_Server_Error'));
    }
    if ($this->input->get('token')) {
      $token = $this->input->get('token');
      $verify_data = $this->model->verifyAccount($token);
      if ($verify_data['code'] == 200) {
        $this->session->set_flashdata("type","success");
        $this->session->set_flashdata("message",$verify_data['message']);
        $this->session->set_userdata("verified",1);
        redirect(base_url('profile').'/'.$data['data']['username']);
      }
    }
    if ($this->input->get('action') === 'change-password') {
      $this->load->view('user/change_password',['data' => $data['data']]);
    }else{
      $this->load->view('user/profile',['data' => $data['data']]);
    }
  }

  function changeAvatar(){
    $path = APPPATH . $this->config->item("user_avatar_path");
    if (isset($_FILES["file"]["name"])) {
      $ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
      if (!in_array($ext,['jpg','jpeg','gif','png'])) {
        return json_response(['code' => 409,'message' => lang('Now_allowed_format')]);die;
      }
      $filename = pathinfo($_FILES["file"]["name"]);
      $avatar = uniqid().rand(1000000,999999999999).'.'.$ext;
      $data = $this->model->changeAvatar($this->auth_user,$avatar);
      if ($data['code'] == 200) {
        if (isset($data['data']['previous_image']) &&
              $data['data']['previous_image'] !== 'default.png' &&
                file_exists($path.$data['data']['previous_image'])) {
          unlink($path.$data['data']['previous_image']);
        }
        move_uploaded_file($_FILES['file']["tmp_name"],$path.$avatar);
        Base::resize($path.$avatar,$path.$avatar,200,200);
        $this->session->set_userdata('avatar',$avatar);
      }else{$avatar = NULL;}
      return json_response(['code' => 200,'avatar' => base_url($this->config->item('user_avatar_path')).$avatar]);
    }
  }


}
