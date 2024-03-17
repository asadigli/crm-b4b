<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Onlines_model extends CI_Model{

  function __construct() {
    parent::__construct();
  }

  function index($params) {
    $b4b_online_time = $this->config->item("b4b_online_time");
    $sql = "SELECT
                  COUNT(DISTINCT `company_id`) as `count`
            FROM `".local_table_name("b4b_onlines")."`
            WHERE `deleted_at` IS NULL
            AND `online_at` > date_sub(now(), INTERVAL $b4b_online_time SECOND)
            ORDER BY `company_id` DESC";

    $entry_query = $params["local_db"]->query($sql);
    $current_online = $entry_query->row_array();
    $current_online = isset($current_online["count"]) ? (int)$current_online["count"] : 0;

    $cache_key = "last_one_day_cache";

    $sql_query = "SELECT
                    MAX(`online_at`) as `time`,
                    COUNT(DISTINCT `company_id`) as `count`
                  FROM `".local_table_name("b4b_onlines")."`
                  WHERE `online_at` > '". date("Y-m-d 00:00:00", strtotime("-10 days",time())) ."'
                  GROUP BY YEAR(`online_at`), MONTH(`online_at`), DAY(`online_at`), HOUR(`online_at`), MINUTE(`online_at`)
                  ORDER BY `time` ASC";
    // $sql_query = "SELECT
    //             DATE_FORMAT(MIN(`online_at`), '%Y-%m-%d %H:00:00') as `time`,
    //             COUNT(DISTINCT `company_id`) as `count`
    //         FROM ".local_table_name("b4b_onlines")."
    //         WHERE `deleted_at` IS NULL
    //         ORDER BY DATE_FORMAT(MIN(`online_at`), '%Y-%m-%d %H:00:00') ASC";
    // $online_query = $params["local_db"]->query($sql_query);

    // if (!$online_query->num_rows()) {
    //   return rest_response(
    //     Status_codes::HTTP_NO_CONTENT,
    //     lang("No result")
    //   );
    // }

    // $onlines = $online_query->result_array();


    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "current" => $current_online,
        "list" => []//$onlines
      ]
    );
  }

}
