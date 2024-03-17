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
    $start_date_query = "";
    $end_date_query   = "";
    if (isset($params["end_date"]) && $params["end_date"]) {
      $params["end_date"] = date('Y-m-d H:i:s', strtotime($params["end_date"] . ' +1 day'));
      $end_date_query     = " AND `news_date` <= '{$params['end_date']}'";
    }

    if (isset($params["start_date"]) && $params["start_date"]) {
      $start_date_query = " AND `news_date` > '{$params['start_date']}'";
    }

    $news_sql = " SELECT
                      news.`id`        as `news_id`,
                      news.`title`     as `news_title`,
                      news.`body`      as `news_text`,
                      news.`start_date`,
                      news.`end_date`,
                      news.`type`,
                      news.`is_active`,
                      news.`is_popup`,
                      news.`created_at` as `date`
                  FROM `".local_table_name("news_popup")."` news
                  WHERE news.`deleted_at` IS NULL
                  AND news.`type` = '{$params["type"]}'
                  AND news.`is_active` = '".STATUS_ACTIVE."'
                  ORDER BY news.`id` DESC";

    // $news_sql = "SELECT
    //                 MD5(`news_id`) as news_id,
    //           			`news_title`,
    //           			`news_text`,
    //           			`news_photo_url`,
    //           			`news_date` as `date`
    //           		FROM
    //           			`".local_table_name("b4b_news")."`
    //           		WHERE
    //         			`news_delete`	= '".STATUS_NO."'
    //               $start_date_query
    //               $end_date_query
    //               ORDER BY `news_id` DESC";

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
      $news_ids = array_map(function($item){ return $item["news_id"];},$news);

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
        $news[$key]["news_photo_url"] = isset($images_list[$item["news_id"]]) ? $images_list[$item["news_id"]] : [];
      }
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $news
    );
  }

  public function details($params){
    $news_sql = " SELECT
                     news.`id` as `news_id`,
                     news.`title` as `news_title`,
                     news.`body` as `news_text`,
                     news.`start_date`,
                     news.`end_date`,
                     news.`type`,
                     news.`is_active`,
                     news.`is_popup`,
                     news.`start_date` as `news_date`
                  FROM `".local_table_name("news_popup")."` news
                  WHERE news.`deleted_at` IS NULL
                  AND news.`id` = '{$params["id"]}'
                  AND news.`is_active` = '".STATUS_ACTIVE."'
                  ORDER BY news.`id` DESC";

    $news_query = $this->local_db->query($news_sql);

    if (!$news_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("News not found")
      );
    }

    $news = $news_query->row_array();
    if ($news["news_text"]) {
      $news["news_text"] = html_escape($news["news_text"]);
    }

    if($news){
      $images_sql = " SELECT
                          news_images.`news_id`,
                          news_images.`path`
                      FROM `".local_table_name("news_popup_images")."` news_images
                      WHERE news_images.`deleted_at` IS NULL
                      AND news_images.`news_id` = {$news["news_id"]} ";

      $images_query = $this->local_db->query($images_sql);
      $images = $images_query->result_array();

      $images_list = [];
      foreach ($images as $key => $image) {
        $images_list[$image["news_id"]][] = $image["path"];
      }
      $news["news_photo_url"] = isset($images_list[$news["news_id"]]) ? $images_list[$news["news_id"]][0] : [];
    }


    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $news
    );
  }
}
