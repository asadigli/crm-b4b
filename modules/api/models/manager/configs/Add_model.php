<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  function index($params){
    $exist_data_check = $this->local_db->query("SELECT
                                                `id`
                                          FROM `".local_table_name("b4b_configs")."`
                                          WHERE `deleted_at` IS NULL
                                          AND `group` = '{$params["group"]}'
                                          AND `key` = '{$params["key"]}'
                                          AND `value` = '{$params["value"]}'");
    if ($exist_data_check->num_rows()) {
      return rest_response(
        Status_codes::HTTP_IM_USED,
        lang("This config already exists")
      );
    }

    $insert_list = [
      "resource" => $params["resource"],
      "group" => $params["group"],
      "type" => $params["type"],
      "key" => $params["key"],
      "value" => $params["value"],
      "is_active" => $params["is_active"],
      "is_dev" => $params["is_dev"] ? STATUS_ACTIVE : STATUS_DEACTIVE,
    ];

    $this->local_db->insert(local_table_name("b4b_configs"),$insert_list);

    $this->clearConfigCache($params["group"]);

    return rest_response(
      Status_codes::HTTP_CREATED,
      lang("Success")
    );
  }

  public function clearConfigCache($group)
  {
    $groups = special_codes("configs.groups") ?: [];
    $group = array_search(special_codes("configs.groups.other"),$groups);

    $file = md5($group) . "_configs.cache";

    $dir = APPPATH . "cache";
    if (file_exists($dir . DIRECTORY_SEPARATOR . $file)) {
        unlink($dir . DIRECTORY_SEPARATOR . $file);
    }
  }

}
