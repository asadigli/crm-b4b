<?php
defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('memory_limit','1200006M');
ini_set('max_execution_time', 10); //300 seconds = 5 minutes

class Currency_model extends CI_Model{

  public function __construct() {
    parent::__construct();
    $this->remote_db = $this->load->database("remote_db",true);
    $this->local_db = $this->load->database("local_db",true);
  }

  function index($params){
    $cache_types = $this->config->item("cache_types");
    $limit_hour = $params["limit_hour"];

    $date_sql_query = "";
    if($limit_hour && is_numeric($limit_hour)) {
    $limit_hour = $limit_hour * 60;
    $date_sql_query = " WHERE ([EDATE] > DATEADD(MINUTE, -{$limit_hour}, GETDATE())";
    }

    $sql = "SELECT
              currencies.[CURCODE] as [code],
              MAX(active_currencies.[static_id]) as static_id,
              MAX([values].rate) as [rate]
            FROM (SELECT
                    DISTINCT (CASE WHEN CRTYPE = 0 THEN 20 ELSE CRTYPE END) as static_id
                    FROM ".remote_table_name("currency_values").") as active_currencies
            OUTER APPLY (SELECT
                          TOP 1 RATES1 as rate
                          FROM [".remote_table_name("currency_values")."]
                          WHERE (CASE WHEN CRTYPE = 0 THEN 20 ELSE CRTYPE END) = active_currencies.[static_id]
                          ORDER BY EDATE DESC) as [values]
            LEFT JOIN ".remote_table_name("currencies")." currencies ON currencies.[CURTYPE] = active_currencies.[static_id]
            GROUP BY currencies.[CURCODE]";

    $query = $this->remote_db->query($sql);
    $res = $query->result_array();

    if (!$query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $res = $query->result_array();
    $list = [];
    $ava_currencies = $this->config->item("ava_currencies");

    foreach ($res as $key => $item) {
      $list[] = [
        "short_name" => $item["code"],
        "main_name" => isset($ava_currencies[$item["static_id"]]) ? $ava_currencies[$item["static_id"]] : NULL,
        "value" => $item["rate"],
        "remote_id" => $item["static_id"]
      ];
    }

    $cached_info_insert_sql = insert_dublicate_key(local_table_name($cache_types[$params["type"]]["table"]), $list);
    $this->local_db->query($cached_info_insert_sql);

    $this->local_db->insert(local_table_name("cached_history"), [
      "type" => $params["type"],
      "request_type" => $params["request_type"],
      "creator_ip" => $params["creator_ip"],
        "creator_id" => $params["creator_id"],
      "result_count" => count($list),
      "operation_date" => $params["date"],
      "interval_start_date" => $params["limit_hour"] ? (date("Y-m-d H:i:s", strtotime('-'.($params["limit_hour"] * 60).' minutes'))) : null,
      "interval_end_date" => now()
    ]);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Cache refreshed"),
      [
        "date" => $params["date"]
      ]
    );

  }

}
