<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  function index($params){
    $configs_sql = "SELECT
                        `id`,
                        `key`,
                        `value`,
                        `group`,
                        `resource`
                   FROM `".local_table_name("b4b_configs")."`
                   WHERE `group` IN  ('".implode("','",array_values(special_codes("configs.groups")))."')
                   AND `resource` IN ('".implode("','",array_values(special_codes("configs.resources")))."')
                   AND `type` IN ('".implode("','",array_values(special_codes("configs.types")))."')
                   AND `is_active` = '".STATUS_ACTIVE."'
                   AND `deleted_at` IS NULL ";
    $configs_query = $this->local_db->query($configs_sql);

    if (!$configs_query->num_rows()) {
        return rest_response(
          Status_codes::HTTP_NO_CONTENT,
          lang("Config not found")
        );
    }



    $configs = $configs_query->result_array();

    $res = [];
    foreach (special_codes("configs.groups") as $key => $item) {
      foreach ($configs as $conf_key => $config) {
        if ($item === $config["group"]) {
          $res[$key][$config["key"]][] = $config["value"];
        }
      }

    }



    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $res
    );

  }

}
