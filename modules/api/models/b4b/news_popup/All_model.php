<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  public function index($params){
    escapeAllKeys($params,null,$this->local_db);

    $news_sql = " SELECT
                      news.`id`,
                      news.`title`,
                      news.`is_popup`,
                      news.`body`
                  FROM `".local_table_name("news_popup")."` news
                  WHERE news.`deleted_at` IS NULL
                  AND news.`end_date` >= '{$params["date"]}'
                  AND news.`start_date` <= '{$params["date"]}'
                  AND news.`is_active` = '".STATUS_ACTIVE."'
                  AND news.`is_popup`  = '".STATUS_ACTIVE."'
                  ORDER BY news.`end_date` DESC ";

    $news_query = $this->local_db->query($news_sql);

    if(!$news_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No news found")
      );
    }

    $news = $news_query->result_array();

    $news_ids = [];
    if($news) {
      $news_ids = array_map(function($item){ return $item["id"];},$news);

      $images_sql = " SELECT
                          news_images.`news_id`,
                          news_images.`path`
                      FROM `".local_table_name("news_popup_images")."` news_images
                      WHERE news_images.`deleted_at` IS NULL
                      AND news_images.`news_id` IN (".implode(",",$news_ids).") ";

      $images_query = $this->local_db->query($images_sql);
      $images       = $images_query->result_array();

      $images_list = [];
      foreach ($images as $key => $image) {
        $images_list[$image["news_id"]][] = $image["path"];
      }

      foreach ($news as $key => $item) {
        $news[$key]["images"] = isset($images_list[$item["id"]]) ? $images_list[$item["id"]] : [];
      }
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $news
    );
  }
}
