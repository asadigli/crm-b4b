<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  function history($params){
    $sql_query = "SELECT
                  `id`,
                  `type`,
                  `request_type`,
                  `created_at` as `datetime`
              FROM ".local_table_name("cached_history")."
              WHERE `deleted_at` IS NULL
              ORDER BY created_at DESC";

    $history_query = $this->local_db->query($sql_query);

    $history = $history_query->result_array();
    $list = [];
    $types = $this->config->item("cache_list");
    foreach ($history as $key => $item) {
      if (in_array($item["type"],$types)) {
        if (!isset($list[$item["type"]])) {
          $list[$item["type"]] = [
            "id" => $item["id"],
            "type" => $item["type"],
            "request_type" => $item["request_type"],
            "datetime" => $item["datetime"],
          ];
        }
      }
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $list
    );
  }

}
