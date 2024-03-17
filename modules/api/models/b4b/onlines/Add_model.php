<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  function index($params){
    $this->local_db->insert(local_table_name("b4b_onlines"),[
      "company_id"      => $params["b4buserid"],
      "company_device"  => $params["company_device"],
      "company_ip"      => $params["company_ip"],
      "entry_token"     => $params["entry_token"],
      "url_path"        => $params["url_path"],
      "online_at"       => $params["online_at"]
    ]);

    return rest_response(
      Status_codes::HTTP_CREATED,
      lang("Success")
    );
  }
}
