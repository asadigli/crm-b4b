<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  public function index($params){
    $sliders_sql = "SELECT
                        slides.`id`,
                        slides.`code`,
                        slides.`title`,
                        slides.`description`,
                        slides.`url`,
                        slides.`image`,
                        slides.`type`,
                        slides.`start_date`,
                        slides.`end_date`,
                        slides.`is_active`,
                        slides.`is_clickable`
                    FROM `".local_table_name("b4b_slides")."` slides
                    WHERE slides.`deleted_at` IS NULL
                    AND slides.`type` = '{$params["type"]}'
                    ORDER BY slides.`id` DESC ";

    $sliders_query = $this->local_db->query($sliders_sql);

    if(!$sliders_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No slider found")
      );
    }

    $sliders = $sliders_query->result_array();

    foreach ($sliders as $key => $item) {
      $sliders[$key]["is_active"] = $item["is_active"] === STATUS_ACTIVE;
      $sliders[$key]["is_clickable"] = $item["is_clickable"] === STATUS_ACTIVE;
      // $sliders[$key]["image"] = decode_blob($item["image"]);
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $sliders
    );

  }

}
