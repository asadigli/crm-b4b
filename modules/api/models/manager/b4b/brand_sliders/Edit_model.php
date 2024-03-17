<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db", true);
  }

  public function index($params){
    removeDashboardCache("product_search");
    escapeAllKeys($params,null,$this->local_db);

    if (!$params["image"]) {
      unset($params["image"]);
    }

    $this->local_db->where("id",$params["id"]);
    unset($params["id"]);
    $this->local_db->update(local_table_name("b4b_slides"),$params);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Brand slider updated")
    );
  }

  public function delete($params){
      removeDashboardCache("product_search");
    escapeAllKeys($params,null,$this->local_db);

    $slide_sql = "SELECT
                      slides.`id`,
                      slides.`image`
                   FROM ".local_table_name("b4b_slides")." slides
                   WHERE slides.`deleted_at` IS NULL
                   AND slides.`id` = {$params["id"]} ";
    $slide_query = $this->local_db->query($slide_sql);

    if (!$slide_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No slide found")
      );
    }

    $slide = $slide_query->row_array();

    $file_path = APPPATH . ".." . DIRECTORY_SEPARATOR . $slide["image"];

    if($slide["image"] && file_exists($file_path)) {
      unlink($file_path);
    }

    $this->local_db->where("id",$params["id"])->update(local_table_name("b4b_slides"),["deleted_at" => $params["date"]]);
    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Brand slider deleted")
    );
  }

}
