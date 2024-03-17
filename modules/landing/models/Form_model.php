<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form_model extends CI_Model{

  function __construct(){
    parent::__construct();
  }

  function sendOrder($params){
    $data = Base::callApi2("GET","http://customer.loc/services/company/send_request_form",$params);
    return $data;
  }


  function sendContactMessage($params){
    $data = Base::callApi2("POST",$this->config->item('data_cisct_url')."services/email/send",$params);
    return $data;
  }


}
