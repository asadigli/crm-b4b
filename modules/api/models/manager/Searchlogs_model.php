<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Searchlogs_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db", true);
  }

  function index($params)
  {
    $start_date_sql = "";
    if (@$params["start_date"]) {
      $start_date_sql = " AND `search_ins_date` > '{$params['start_date']}'";
    }
    $end_date_sql = "";
    if (@$params["end_date"]) {
      $params["end_date"] = date('Y-m-d H:i:s', strtotime($params["end_date"] . ' +1 day'));
      $end_date_sql = " AND keywords.`search_ins_date` <= '{$params['end_date']}'";
    }
    $customer_sql = "";
    if (@$params["customer_id"]) {
      $customer_sql = " AND keywords.`remote_customer_id`={$params["customer_id"]} ";
    }

    $offset = (int)$params["offset"] >= 0 ? (int)$params["offset"] : 0;

    $possible_data_sql = "SELECT COUNT(1) as count FROM
                            (
                              SELECT COUNT(1) as grouped_count
                          FROM `" . local_table_name("b4b_searched_keywords") . "` as keywords 
                           WHERE  keywords.`deleted_at` is NULL
                           $start_date_sql
                           $end_date_sql
                           $customer_sql
                           GROUP BY 
                                    keywords.`search_code`, 
                                    keywords.`search_brand`, 
                                    keywords.`search_marka`
                            ) as tbl
                          ";
    $possible_data_query = $this->local_db->query($possible_data_sql);
    $possible_data_count = $possible_data_query->row_array()["count"];

    $logs_sql = "SELECT 
                        `search_code`,
                        `search_brand`,
                        `search_marka`,
                        COUNT(`search_code`)  as search_count,
                        MAX(`result_count`) as result_count,
                        COUNT(DISTINCT `remote_customer_id`) as customers_count,
                        (SELECT name 
                        FROM  `" . local_table_name("cached_product_brands") . "` brands 
                        WHERE brands.`deleted_at` is NULL 
                        AND brands.`id`  = keywords.`search_brand` 
                        LIMIT 1) as `search_brand_name`,
                        (
                        SELECT
                            carbrands.`name`
                          FROM `".local_table_name("cached_carbrands")."` carbrands
                          WHERE carbrands.`deleted_at` IS NULL
                          AND carbrands.`id` = keywords.`search_marka`
                        ) as  search_marka_name
                 FROM " . local_table_name("b4b_searched_keywords") . " as keywords
                 WHERE  keywords.`deleted_at` is NULL
                 $start_date_sql
                 $end_date_sql
                 $customer_sql
                 GROUP BY 
                     keywords.`search_code`, 
                     keywords.`search_brand`, 
                     keywords.`search_marka`
                     ORDER BY `search_count` DESC
                LIMIT " . $this->config->item("manager_search_logs_limit") . "
                 OFFSET $offset 
                   ";
    $logs_query = $this->local_db->query($logs_sql);
    if (!$logs_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }
    $logs = $logs_query->result_array();


    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "possible_data_count" => $possible_data_count,
        "logs" => $logs,
        "sql1" => $possible_data_sql,
        "sql2" => $logs_sql
      ],
    );


  }

  function onlyCustomers($params)
  {
    $start_date_sql = "";
    if (@$params["start_date"]) {
      $start_date_sql = " AND `search_ins_date` > '{$params['start_date']}'";
    }
    $end_date_sql = "";
    if (@$params["end_date"]) {
      $params["end_date"] = date('Y-m-d H:i:s', strtotime($params["end_date"] . ' +1 day'));
      $end_date_sql = " AND keywords.`search_ins_date` <= '{$params['end_date']}'";
    }
    $customer_sql = "";
    if (@$params["customer_id"]) {
      $customer_sql = " AND keywords.`remote_customer_id`={$params["customer_id"]} ";
    }

    $search_code_sql = "AND keywords.`search_code` = '{$params["search_code"]}' ";

    $search_brand_sql = "AND keywords.`search_brand` is NULL ";
    if (@$params["search_brand"]){
      $search_brand_sql = "AND keywords.`search_brand` = '{$params["search_brand"]}' ";
    }
    $search_marka_sql = "AND keywords.`search_marka` is NULL";
    if (@$params["search_marka"]){
      $search_marka_sql = "AND keywords.`search_marka` = '{$params["search_marka"]}' ";
    }



    $sql = "SELECT `remote_customer_id`,
                    cus.`name` as customer_name,
                    COUNT(1) as count
            FROM  " . local_table_name("b4b_searched_keywords") . " as keywords 
            LEFT JOIN `" . local_table_name("cached_customers") . "` cus
            ON cus.`remote_id` = keywords.`remote_customer_id`
            WHERE  keywords.`deleted_at` is NULL
            $start_date_sql
            $end_date_sql
            $customer_sql
            $search_code_sql
            $search_brand_sql
            $search_marka_sql
            GROUP BY `remote_customer_id` ";

//    return ["298","298",$sql];
    $query = $this->local_db->query($sql);
    $data = $query->result_array();
    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $data
    );
  }


}


