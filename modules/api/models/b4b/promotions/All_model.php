<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  function index($params){
    escapeAllKeys($params,null,$this->local_db);

    $show_main_query = $params["show_main"] === STATUS_YES ? " AND `action_show_main` = '{$params["show_main"]}' " : "";

    $limit_query = (int)$params["limit"]  ? "LIMIT {$params["limit"]}" : "";

    $promotions_sql = "SELECT
                        news.`id` as `promotion_id`,
                        news.`title` as `promotion_title`,
                        news.`body` as `promotion_text`,
                        news.`start_date`,
                        news.`end_date`,
                        news.`type`,
                        news.`is_active`,
                        news.`is_popup`,
                        news.`start_date` as `promotion_ins_date`
                       FROM `".local_table_name("news_popup")."` news
                       WHERE news.`deleted_at` IS NULL
                       AND news.`type` = '{$params["type"]}'
                       AND news.`is_active` = '".STATUS_ACTIVE."'
                       ORDER BY news.`id` DESC
                       $limit_query";

    $promotions_query = $this->local_db->query($promotions_sql);

    if (!$promotions_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Result not found")
      );
    }

    $promotions = $promotions_query->result_array();

    foreach ($promotions as $key => $item) {
      $promotions[$key]["is_active"] = $item["is_active"] === STATUS_ACTIVE;
      $promotions[$key]["is_popup"] = $item["is_popup"] === STATUS_ACTIVE;
      $promotions[$key]["type"] = array_search($item["type"], special_codes("pop_up.types")) ?: null;
    }

    $promotions_ids = [];
    if($promotions) {
      $promotions_ids = array_map(function($item){ return $item["promotion_id"];},$promotions);

      $images_sql = "SELECT
                      news_images.`news_id`,
                      news_images.`path`
                    FROM `".local_table_name("news_popup_images")."` news_images
                    WHERE news_images.`deleted_at` IS NULL
                    AND news_images.`news_id` IN (".implode(",",$promotions_ids).") ";

      $images_query = $this->local_db->query($images_sql);
      $images = $images_query->result_array();

      $images_list = [];
      foreach ($images as $key => $image) {
        $images_list[$image["news_id"]][] = $image["path"];
      }

      foreach ($promotions as $key => $item) {
        $promotions[$key]["promotion_photo_url"] = isset($images_list[$item["promotion_id"]]) ? $images_list[$item["promotion_id"]] : [];
      }
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $promotions
    );
  }


  function details($params){
    $sql = "SELECT
        			`id` as promotion_id,
        			`title` as promotion_title,
        			`body` as promotion_text,
              `created_at` as promotion_ins_date
        		FROM
        			`".local_table_name("news_popup")."`
        		WHERE
        			`id`	= '{$params["id"]}'
        		ORDER BY
        			`id`
        		DESC
        			LIMIT 1";

    $promotion_query = $this->local_db->query($sql);
    if (!$promotion_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Promotion not found")
      );
    }

    $promotion = $promotion_query->row_array();
    if ($promotion["promotion_text"]) {
      $promotion["promotion_text"] = html_escape($promotion["promotion_text"]);
    }

    if($promotion) {

      $images_sql = "SELECT
                      news_images.`news_id`,
                      news_images.`path`
                    FROM `".local_table_name("news_popup_images")."` news_images
                    WHERE news_images.`deleted_at` IS NULL
                    AND news_images.`news_id` = {$promotion["promotion_id"]} ";

      $images_query = $this->local_db->query($images_sql);
      $images = $images_query->result_array();

      $images_list = [];
      foreach ($images as $key => $image) {
        $images_list[$image["news_id"]][] = $image["path"];
      }


      $promotion["promotion_photo_url"] = isset($images_list[$promotion["promotion_id"]]) ? $images_list[$promotion["promotion_id"]] : [];

    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $promotion
    );
  }

}
