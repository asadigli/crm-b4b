<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  function index($params){
    $sql_query = "SELECT
                      slides.`id`,
                      slides.`title`,
                      slides.`description`,
                      slides.`url`,
                      slides.`image`,
                      slides.`start_date`,
                      slides.`end_date`,
                      slides.`is_active`,
                      slides.`type`
                  FROM ".local_table_name("b4b_slides")." slides
                  WHERE slides.`deleted_at` IS NULL
                  AND slides.`is_active` = '".STATUS_ACTIVE."'
                  AND slides.`type` = '{$params["type"]}'
                  AND slides.`start_date` < '{$params["today"]}'
                  AND slides.`end_date` > '{$params["tomorrow"]}'
                  ORDER BY slides.`created_at` DESC";

    $query = $this->local_db->query($sql_query);

    if(!$query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No slider found"),
      );
    }

    $res = $query->result_array();

    // foreach ($res as $key => $item) {
    //   $res[$key]["image"] = decode_blob($item["image"]);
    // }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $res
    );
  }
}
