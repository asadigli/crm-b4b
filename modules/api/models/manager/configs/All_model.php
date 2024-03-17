<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  function index($params){
    $keyword = "'%{$params["keyword"]}%'";
    $keyword_query = $params["keyword"] ? "AND (`key` LIKE $keyword
                                                  OR `value` LIKE $keyword) " : "";

    $show_developer_sql = $params["auth_role"] === ROLE_DEVELOPER ? "" : "AND config.`is_dev` = '0' ";

    $configs_sql = "SELECT
                      `id`,
                      `group`,
                      `key`,
                      `resource`,
                      `group`,
                      `type`,
                      `value`,
                      `is_active`
                      FROM `".local_table_name("b4b_configs")."` config
                      WHERE config.`deleted_at` IS NULL
                      $keyword_query
                      $show_developer_sql ";

    $configs_query = $this->local_db->query($configs_sql);

    if (!$configs_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Config not found")
      );
    }

    $configs = $configs_query->result_array();

    foreach ($configs as $key => $config) {
        $configs[$key]["is_active"] = $config["is_active"] === STATUS_ACTIVE;
        $configs[$key]["resource"] = special_codes("configs.resources",$config["resource"]);
        $configs[$key]["group"] = special_codes("configs.groups",$config["group"]);
        $configs[$key]["type"] = special_codes("configs.types",$config["type"]);
    }

    $types = new stdClass();
    foreach (special_codes("configs.types") as $key => $value) {
      $types->$key = $key;
    }

    $resources = new stdClass();
    foreach (special_codes("configs.resources") as $key => $value) {
      $resources->$key = $key;
    }

    $groups = new stdClass();
    foreach (special_codes("configs.groups") as $key => $value) {
      $groups->$key = $key;
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "configs" => $configs,
        "resources" => $resources,
        "types" => $types,
        "groups" => $groups
      ]
    );
  }


}
