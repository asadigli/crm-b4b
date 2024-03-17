<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db", TRUE);
  }

  public function index($params){
    escapeAllKeys($params,null,$this->local_db);


    $this->local_db->insert(local_table_name("news_popup"),[
      "creator_id" => $params["creator_id"],
      "title" => $params["title"],
      "body" => $params["body"],
      "type" => $params["type"],
      "start_date" => $params["start_date"],
      "end_date" => $params["end_date"],
      "is_active" => $params["is_active"],
      "is_popup" => $params["is_popup"],
    ]);

    $news_id = $this->local_db->insert_id();

    $this->local_db->insert(local_table_name("news_popup_images"),[
      "news_id" => $news_id,
      "path" => $params["image"],
    ]);

    return rest_response(
      Status_codes::HTTP_CREATED,
      lang("News created")
    );
  }

}
