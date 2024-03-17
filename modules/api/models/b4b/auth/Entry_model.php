<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entry_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  function add($params){
    escapeAllKeys($params,null,$this->local_db);
    $entry_tokens = [];
    if ($params["entry_count"]) {
      for ($i=0; $i < $params["entry_count"] ; $i++) {
        $entry_tokens[] = [
          "entry_id" => $params["entry_id"],
          "token" => bin2hex(openssl_random_pseudo_bytes(40)),
          "status" => ETS_IDLE,
        ];
      }
    }

    $entry_tokens ? $this->local_db->insert_batch(local_table_name("b4b_entry_tokens"),$entry_tokens) : "";

    return rest_response(
      Status_codes::HTTP_CREATED,
      lang("Success")
    );
  }

}
