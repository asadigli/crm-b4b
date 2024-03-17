<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function addToHistory($params)
  {
    $this->local_db->insert(local_table_name("b4b_exports"),[
      "entry_id" => $params["entry_id"],
      "key" => $params["key"],
      "keyword" => $params["keyword"],
      "params" => $params["params"],
    ]);

    $insert_id = $this->local_db->insert_id();
    $code = str_pad($insert_id, 8, '0', STR_PAD_LEFT);
    $this->local_db->where("id",$insert_id)->update(local_table_name("b4b_exports"),["code" => $code]);

    return rest_response(
      Status_codes::HTTP_CREATED,
      lang("File export tracked")
    );
  }
}
