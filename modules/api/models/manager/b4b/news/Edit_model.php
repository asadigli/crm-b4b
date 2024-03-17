<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db", TRUE);
  }

  public function index($params){
    $check_news_sql = "SELECT
                          news.`id`
                        FROM `".local_table_name("news_popup")."` news
                        WHERE news.`deleted_at` IS NULL
                        AND news.`id` = {$params["id"]}";
    $check_news_query = $this->local_db->query($check_news_sql);

    if (!$check_news_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("News not found")
      );
    }

    $this->local_db->where("id", $params["id"]);

    $this->local_db->update(local_table_name("news_popup"), [
      "title" => $params["title"],
      "body" => $params["body"],
      "type" => $params["type"],
      "start_date" => $params["start_date"],
      "end_date" => $params["end_date"],
      "is_active" => $params["is_active"],
      "is_popup" => $params["is_popup"],
    ]);

    if(!$params["deleted_image"]){
      $this->local_db->where("news_id", $params["id"]);
      $this->local_db->update(local_table_name("news_popup_images"), ["path" => $params["path"]]);
    }

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("News updated")
    );
  }

  public function delete($params){
    $check_news_sql = "SELECT
                          news.`id`
                        FROM `".local_table_name("news_popup")."` news
                        WHERE news.`deleted_at` IS NULL
                        AND news.`id` = '{$params["id"]}' ";
                        // var_dump($check_news_sql);die;
    $check_news_query = $this->local_db->query($check_news_sql);

    if (!$check_news_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No news found")
      );
    }

    $this->local_db->where("id",$params["id"]);
    $this->local_db->update(local_table_name("news_popup"),["deleted_at" => $params["date"]]);

    $this->local_db->where("news_id",$params["id"]);
    $this->local_db->update(local_table_name("news_popup_images"),["deleted_at" => $params["date"]]);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("News deleted")
    );
  }

}
