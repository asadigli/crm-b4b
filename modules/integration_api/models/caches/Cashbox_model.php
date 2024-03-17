<?php
defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('memory_limit','1200006M');
ini_set('max_execution_time', 10); //300 seconds = 5 minutes

class Cashbox_model extends CI_Model{

  public function __construct(){
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
      $date_sql_query = " AND ([CAPIBLOCK_EXTCREATEDDATE] > DATEADD(MINUTE, -{$limit_hour}, GETDATE()) OR
                                  [CAPIBLOCK_EXTMODIFIEDDATE] > DATEADD(MINUTE, -{$limit_hour}, GETDATE())) ";
    }

    $sql = "SELECT
                  LOGICALREF,
                  CODE,
                  NAME,
                  ACTIVE
            FROM ".remote_table_name("cashboxes")."
            WHERE LOGICALREF > 0 ";

    $query = $this->remote_db->query($sql);
    $res = $query->result_array();

    if (!$query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_UNAUTHORIZED,
        lang("No result")
      );
    }

    $res = $query->result_array();
    $list = [];
    foreach ($res as $key => $item) {
      $list[] = [
        "remote_id"         => $item["LOGICALREF"],
        "remote_code"       => $item["CODE"],
        "name"              => $item["NAME"],
        "remote_is_active"  => $item["ACTIVE"] === 0 ? STATUS_ACTIVE : STATUS_DEACTIVE,
      ];
    }

    if($list){
      $cached_details_insert_sql = insert_dublicate_key(local_table_name("cached_cashboxes"), $list);
      $this->local_db->query($cached_details_insert_sql);

      $this->local_db->insert(local_table_name("cached_history"), [
        "type" => $params["type"],
        "request_type" => $params["request_type"],
        "creator_ip" => $params["creator_ip"],
          "creator_id" => $params["creator_id"],
        "result_count" => count($list),
        "operation_date" => $params["date"],
        "interval_start_date" => $params["limit_hour"] ? date("Y-m-d H:i:s", strtotime('-'.($params["limit_hour"] * 60).' minutes')) : null,
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

}
