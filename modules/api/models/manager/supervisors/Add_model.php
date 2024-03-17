<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  function action($params){
    escapeAllKeys($params,null,$this->local_db);

    $this->local_db->insert(local_table_name("b4b_curators"),$params);

    return rest_response(
      Status_codes::HTTP_CREATED,
      lang("Success")
    );
  }

}
