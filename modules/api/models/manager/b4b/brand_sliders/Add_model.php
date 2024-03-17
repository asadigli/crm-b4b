<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  public function index($params){
    removeDashboardCache("product_search");
    escapeAllKeys($params,null,$this->local_db);

    $this->local_db->insert(local_table_name("b4b_slides"),[
      "title" => $params["title"],
      "description" => $params["description"],
      "start_date" => $params["start_date"],
      "end_date" => $params["end_date"],
      "url" => $params["url"],
      "image" => $params["image"],
      "is_active" => $params["is_active"],
      "is_clickable" => $params["is_clickable"],
      "type" => $params["type"],
    ]);
    $insert_id = $this->local_db->insert_id();

    $code = str_pad($insert_id, 8, '0', STR_PAD_LEFT);
    $this->local_db->where("id",$insert_id)->update(local_table_name("b4b_slides"),["code" => $code]);

    return rest_response(
      Status_codes::HTTP_CREATED,
      lang("Brand slider created")
    );
  }

}
