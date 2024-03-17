<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages_model extends CI_Model{

  function __construct(){
    parent::__construct();
  }

  function faqOrAbout($params){
    $res = Base::callAPI("GET","page/list",$params);
    return $res;
  }



}
