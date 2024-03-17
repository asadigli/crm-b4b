<?php
defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('memory_limit','1200006M');
ini_set('max_execution_time', 10); //300 seconds = 5 minutes

class Properties_model extends CI_Model{

  public function __construct() {
    parent::__construct();
    $this->remote_db = $this->load->database("remote_db",true);
    $this->local_db = $this->load->database("local_db",true);
  }

  function models($params){
    $cache_types = $this->config->item("cache_types");
    $limit_hour = $params["limit_hour"];

    $date_sql_query = "";
    if($limit_hour && is_numeric($limit_hour)) {
      $limit_hour = $limit_hour * 60;
      $date_sql_query = " AND ([CAPIBLOCK_EXTCREATEDDATE] > DATEADD(MINUTE, -{$limit_hour}, GETDATE()) OR
                                    [CAPIBLOCK_EXTMODIFIEDDATE] > DATEADD(MINUTE, -{$limit_hour}, GETDATE())) ";
    }

    $sql = "SELECT
        			SPECODE2,
        			PRODUCERCODE
        		FROM
        			".remote_table_name("products")."
        		WHERE
        			SPECODE2 != ''
            AND
        			PRODUCERCODE != ''

            $date_sql_query

        		GROUP BY
        			SPECODE2,
        			PRODUCERCODE
        		ORDER BY
        			PRODUCERCODE
        		ASC";

    $query = $this->remote_db->query($sql);

    if (!$query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $res = $query->result_array();

    $list = [];
    foreach ($res as $key => $item) {
      $producercode = str_replace("'", "\'",htmlentities($item["PRODUCERCODE"]));
      $producercode = str_replace("'", '\"',htmlentities($producercode));

      $specode2 = str_replace("'", "\'",htmlentities($item["SPECODE2"]));
      $specode2 = str_replace("'", '\"',htmlentities($specode2));


      $list[] = [
        "token"         => md5($producercode),
        "name"          => $producercode,
        "brand"     => $specode2,
      ];
    }


    if($list){
      $cached_details_insert_sql = insert_dublicate_key(local_table_name("cached_models"), $list);
      $this->local_db->query($cached_details_insert_sql);

      $this->local_db->insert(local_table_name("cached_history"), [
        "type" => $params["type"],
        "request_type" => $params["request_type"],
        "creator_ip" => $params["creator_ip"],
          "creator_id" => $params["creator_id"],
        "result_count" => count($list),
        "operation_date" => $params["date"],
        "interval_start_date" => $params["limit_hour"] ? (date("Y-m-d H:i:s", strtotime('-'.($params["limit_hour"] * 60).' minutes'))) : NULL,
        "interval_end_date" => now()
      ]);
    }

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Cache refreshed"),
      [
        "date" => $params["date"]
      ]
    );

  }

  function carbrands($params){
    $cache_types = $this->config->item("cache_types");
    $limit_hour = $params["limit_hour"];

    $date_sql_query = "";
    if($limit_hour && is_numeric($limit_hour)) {
      $limit_hour = $limit_hour * 60;
      $date_sql_query = " AND ([CAPIBLOCK_EXTCREATEDDATE] > DATEADD(MINUTE, -{$limit_hour}, GETDATE()) OR
                                    [CAPIBLOCK_EXTMODIFIEDDATE] > DATEADD(MINUTE, -{$limit_hour}, GETDATE())) ";
    }

    $sql = "SELECT
              SPECODE2
            FROM
              ".remote_table_name("products")."
            WHERE
              SPECODE2 != ''
            $date_sql_query
            GROUP BY
              SPECODE2
            ORDER BY
              SPECODE2
            ASC";

    $query = $this->remote_db->query($sql);

    if (!$query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $res = $query->result_array();

    $list = [];
    foreach ($res as $key => $item) {
      $specode2 = str_replace("'", "\'",htmlentities($item["SPECODE2"]));
      $specode2 = str_replace("'", '\"',htmlentities($specode2));


      $list[] = [
        "token"         => md5($specode2),
        "name"          => $specode2,
      ];
    }


    if($list){
      $cached_details_insert_sql = insert_dublicate_key(local_table_name("cached_carbrands"), $list);
      $this->local_db->query($cached_details_insert_sql);

      $this->local_db->insert(local_table_name("cached_history"), [
        "type" => $params["type"],
        "request_type" => $params["request_type"],
        "creator_ip" => $params["creator_ip"],
          "creator_id" => $params["creator_id"],
        "result_count" => count($list),
        "operation_date" => $params["date"],
        "interval_start_date" => $params["limit_hour"] ? (date("Y-m-d H:i:s", strtotime('-'.($params["limit_hour"] * 60).' minutes'))) : NULL,
        "interval_end_date" => now()
      ]);
    }

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Cache refreshed"),
      [
        "date" => $params["date"]
      ]
    );

  }

  public function productBrands($params)
  {
    $cache_types = $this->config->item("cache_types");
    $limit_hour = $params["limit_hour"];

    $date_sql_query = "";
    if($limit_hour && is_numeric($limit_hour)) {
      $limit_hour = $limit_hour * 60;
      $date_sql_query = " AND ([CAPIBLOCK_EXTCREATEDDATE] > DATEADD(MINUTE, -{$limit_hour}, GETDATE()) OR
                                    [CAPIBLOCK_EXTMODIFIEDDATE] > DATEADD(MINUTE, -{$limit_hour}, GETDATE())) ";
    }

    $sql = "SELECT
              SPECODE
            FROM ".remote_table_name("products")."
            GROUP BY
              SPECODE
            ORDER BY SPECODE ASC
            ";
    $query = $this->remote_db->query($sql);

    if (!$query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $res = $query->result_array();

    $list = [];
    foreach ($res as $key => $item) {
      $specode = trim(str_replace(["'", "\"",";"], " ", $item["SPECODE"]));

      $list[] = [
        "token" => md5($specode),
        "name" => $specode,
      ];
    }


    if($list){
      $cached_details_insert_sql = insert_dublicate_key(local_table_name("cached_product_brands"), $list);
      $this->local_db->query($cached_details_insert_sql);

      $this->local_db->insert(local_table_name("cached_history"), [
        "type" => $params["type"],
        "request_type" => $params["request_type"],
        "creator_ip" => $params["creator_ip"],
          "creator_id" => $params["creator_id"],
        "result_count" => count($list),
        "operation_date" => $params["date"],
        "interval_start_date" => $params["limit_hour"] ? (date("Y-m-d H:i:s", strtotime('-'.($params["limit_hour"] * 60).' minutes'))) : NULL,
        "interval_end_date" => now()
      ]);
    }

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Cache refreshed"),[
        "date" => $params["date"]
      ]
    );
  }
}
