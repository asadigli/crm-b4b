<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  function action($params){
    escapeAllKeys($params,null,$this->local_db);

    $this->local_db->insert(local_table_name("b4b_slides"),$params);
    $insert_id = $this->local_db->insert_id();

    $code = str_pad($insert_id, 8, '0', STR_PAD_LEFT);
    $this->local_db->where("id",$insert_id)->update(local_table_name("b4b_slides"),["code" => $code]);

    return rest_response(
      Status_codes::HTTP_CREATED,
      lang("Banner created")
    );
  }

}
