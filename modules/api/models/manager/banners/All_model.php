<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  function index($params){
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
                  ORDER BY created_at DESC";

    $query = $this->local_db->query($sql_query);
    $res = $query->result_array();

    foreach ($res as $key => $item) {
      $res[$key]["image"] = decode_blob($item["image"]);
      $res[$key]["is_active"] = $item["is_active"] === STATUS_ACTIVE;
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $res
    );
  }

  function edit($params){
    escapeAllKeys($params,null,$this->local_db);
    if (!$params["image"]) {
      unset($params["image"]);
    }
    $this->local_db->where("id",$params["id"]);
    unset($params["id"]);
    $this->local_db->update(local_table_name("b4b_slides"),$params);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Banner updated")
    );
  }

  function delete($params){
    escapeAllKeys($params,null,$this->local_db);

    $sql_query = "SELECT
                      *
                  FROM ".local_table_name("b4b_slides")."
                  WHERE `deleted_at` IS NULL
                  AND `id` = {$params["id"]}";
    $query = $this->local_db->query($sql_query);

    if (!$query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Banner not found")
      );
    }

    $this->local_db->where("id",$params["id"])->update(local_table_name("b4b_slides"),["deleted_at" => $params["date"]]);
    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Banner deleted")
    );
  }
}
