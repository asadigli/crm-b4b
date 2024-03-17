<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
class Lang extends MY_Controller
{

  function __construct()
  {
    parent::__construct();
  }
  function words($arr){
    $langs = [];
    for ($i=0; $i < count($arr); $i++) {
      $langs[] = [$arr[$i] => lang($arr[$i])];
    }
    return $langs;
  }


  function languages(){
    $arr = [
      'Error_message','Choose','Cannot_be_empty','Choose product brand',
      'See_list','Password_not_matched','Searching','Product_code','Product','Sum',
      'No_result_found','View','Previous','Next','Are_u_sure_to_delete_tag','Price','Quantity','No product found',
      'More','Product not found','Category','Brand','Sub category','Choose region',
      'Choose brand','Choose year','Choose model','Choose engine','Yes','Cancel','Are you sure to delete brand',
      'Are you sure to delete news','Choose category','Choose second category','We will contact you soon','Thank you for keeping in touch with us'
    ];
    $res = $this->words($arr);
    return json_response($res,"no_auth");
  }


}
