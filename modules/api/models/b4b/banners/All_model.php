<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  function index($params){
    $today = now();
    $tomorrow = date('Y-m-d H:i:s', strtotime($today . ' +1 day'));

    $sql_query = "SELECT
                      `id`,
                      `title`,
                      `description`,
                      `url`,
                      `image`,
                      `start_date`,
                      `end_date`,
                      `is_active`
                  FROM ".local_table_name("b4b_slides")."
                  WHERE `deleted_at` IS NULL
                  AND `is_active` = '".STATUS_ACTIVE."'
                  AND `start_date` < '{$today}'
                  AND `end_date` > '{$tomorrow}'
                  ORDER BY created_at DESC";

    $query = $this->local_db->query($sql_query);
    $res = $query->result_array();

    foreach ($res as $key => $item) {
      $res[$key]["image"] = decode_blob($item["image"]);
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $res
    );
  }
}
