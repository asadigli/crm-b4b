<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  function index($params){
    if ($params["mode"] === "short") {
      $sql = "SELECT
                    curator_id as id,
                    CONCAT(curator_name, ' ' , curator_surname) as name
              FROM ".local_table_name("b4b_curators")."
              WHERE `deleted_at` IS NULL ";
      $query = $this->local_db->query($sql);

      if (!$query->num_rows()) {
        return rest_response(
          Status_codes::HTTP_NO_CONTENT,
          lang("No result")
        );
      }

      $curators = $query->result_array();

      return rest_response(
        Status_codes::HTTP_OK,
        lang("Success"),
        $curators
      );


    }
    $sql = "SELECT
                  curator_id as id,
                  curator_name as name,
                  curator_surname as surname,
                  curator_ava_name as ava_name,
                  curator_mobile as phone,
                  curator_email as email,
                  curator_whatsapp as whatsapp,
                  image,
                  curator_photo_url as old_image
            FROM ".local_table_name("b4b_curators")."
            WHERE `deleted_at` IS NULL ";

    $query = $this->local_db->query($sql);

    if (!$query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $res = $query->result_array();
    foreach ($res as $key => $item) {
      $res[$key]["image"] = $item["image"] ? decode_blob($item["image"]) : null;
      unset($res[$key]["old_image"]);
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
    $this->local_db->where("curator_id",$params["id"]);
    unset($params["id"]);
    $this->local_db->update(local_table_name("b4b_curators"),$params);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Success")
    );
  }

}
