<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model{

  public function __construct(){
    parent::__construct();
  }


  function dashboardReports($params){
    $data = Api::call("GET",endpoint_name("dashboard_reports"),$params);
    return $data;
  }

}
