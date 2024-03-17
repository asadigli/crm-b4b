<?php
defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('memory_limit','1200006M');
ini_set('max_execution_time', 30); //300 seconds = 5 minutes

class Customer_model extends MY_Controller{

  public function __construct() {
    parent::__construct();
    $this->remote_db = $this->load->database("remote_db",true);
    $this->local_db = $this->load->database("local_db",true);
  }

  function index($params) {
    $cache_types = $this->config->item("cache_types");
    $limit_hour = $params["limit_hour"];

    $currency = get_currency();
    $currency_list = [];
    foreach ($currency as $key => $item) {
      $currency_list[$item["main_name"]] = $item;
    }

    $date_sql_query = "";
    if($limit_hour && is_numeric($limit_hour)) {
      $limit_hour = $limit_hour * 60;
      $date_sql_query = " AND ([CAPIBLOCK_EXTCREATEDDATE] > DATEADD(MINUTE, -{$limit_hour}, GETDATE()) OR
                                    [CAPIBLOCK_EXTMODIFIEDDATE] > DATEADD(MINUTE, -{$limit_hour}, GETDATE())) ";
    }

    $date_interval_sql_query = "";
    if ($params["start_date"] && $params["end_date"]) {
      $date_sql_query = "";
      $end_date = date("Y-m-d",strtotime("+1 day",strtotime($params["end_date"])));

      $date_interval_sql_query = " AND ( ([CAPIBLOCK_EXTCREATEDDATE] >= '{$params["start_date"]}' AND [CAPIBLOCK_EXTCREATEDDATE] <= '{$end_date}')
                                        OR ([CAPIBLOCK_EXTMODIFIEDDATE] >= '{$params["start_date"]}' AND [CAPIBLOCK_EXTMODIFIEDDATE] <= '{$end_date}') ) ";
    }


    $sql = "SELECT
      				LOGICALREF,
              CODE,
      				DEFINITION_,
              SPECODE,
      				SPECODE2,
              ACTIVE
      			FROM
      				".remote_table_name("customers")."
            WHERE LOGICALREF > 0
            $date_sql_query
            $date_interval_sql_query ";

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
      $code = str_replace("'", "\'",htmlentities($item["CODE"]));
      $code = str_replace("'", '\"',htmlentities($code));

      $definition = str_replace("'", "\'",htmlentities($item["DEFINITION_"]));
      $definition = str_replace("'", '\"',htmlentities($definition));

      $specode = str_replace("'", "\'",htmlentities($item["SPECODE"]));
      $specode = str_replace("'", '\"',htmlentities($specode));


      $list[] = [
        "remote_id"         => $item["LOGICALREF"],
        "code"              => $code,
        "name"              => $definition,
        "description"       => $specode,
        "currency_id"       => isset($currency_list[$item["SPECODE2"]]) ? $currency_list[$item["SPECODE2"]]["id"] : $currency_list[CURRENCY_EUR]["id"],
        "remote_is_active"  => $item["ACTIVE"] === 0 ? STATUS_ACTIVE : STATUS_DEACTIVE,
      ];
    }

    if($list){
      $cached_details_insert_sql = insert_dublicate_key(local_table_name("cached_customers"), $list);
      $this->local_db->query($cached_details_insert_sql);

      $this->local_db->insert(local_table_name("cached_history"), [
        "type" => $params["type"],
        "request_type" => $params["request_type"],
        "creator_ip" => $params["creator_ip"],
          "creator_id" => $params["creator_id"],
        "result_count" => count($list),
        "operation_date" => $params["date"],
        "interval_start_date" => $params["start_date"] ? $params["start_date"] : (date("Y-m-d H:i:s", strtotime('-'.($params["limit_hour"] * 60).' minutes'))),
        "interval_end_date" => $params["end_date"] ?: now()
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

}
