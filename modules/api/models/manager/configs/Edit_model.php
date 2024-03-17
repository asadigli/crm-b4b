<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }





  function index($params){
    $existence_check = $this->local_db->query("SELECT
                                                    `id`,
                                                    `group`,
                                                    `type`,
                                                    `key`,
                                                    `resource`,
                                                    `value`
                                               FROM `".local_table_name("b4b_configs")."`
                                               WHERE `deleted_at` IS NULL
                                               AND `id` = {$params["id"]}");
    if (!$existence_check->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Config doesn't exist")
      );
    }

    $exist_config = $existence_check->row_array();

    $and_clause = "";

    if ($params["key"] === "resource") {
      $and_clause = "AND `type` = '{$exist_config["type"]}'
                      AND `group` = '{$exist_config["group"]}'
                        AND `value` = '{$exist_config["value"]}'
                          AND `key` = '{$exist_config["key"]}'";
    }else if ($params["key"] === "type"){
      $and_clause = "AND `resource` = '{$exist_config["resource"]}'
                      AND `group` = '{$exist_config["group"]}'
                        AND `value` = '{$exist_config["value"]}'
                          AND `key` = '{$exist_config["key"]}'";
    }else if ($params["key"] === "group"){
      $and_clause = "AND `resource` = '{$exist_config["resource"]}'
                      AND `type` = '{$exist_config["type"]}'
                        AND `value` = '{$exist_config["value"]}'
                          AND `key` = '{$exist_config["key"]}'";
    }else if ($params["key"] === "value"){
      $and_clause = "AND `resource` = '{$exist_config["resource"]}'
                      AND `type` = '{$exist_config["type"]}'
                        AND `group` = '{$exist_config["group"]}'
                          AND `key` = '{$exist_config["key"]}'";
    }else if ($params["key"] === "key"){
      $and_clause = "AND `resource` = '{$exist_config["resource"]}'
                      AND `type` = '{$exist_config["type"]}'
                        AND `group` = '{$exist_config["group"]}'
                          AND `value` = '{$exist_config["value"]}'";
    }

    $already_exist_sql = "SELECT
                                `id`
                           FROM `".local_table_name("b4b_configs")."`
                           WHERE `deleted_at` IS NULL
                           AND `".$params["key"]."` = '{$params["value"]}'
                           $and_clause
                           AND `id` != {$params["id"]} ";

    $already_exist_query = $this->local_db->query($already_exist_sql);

    if ($already_exist_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_IM_USED,
        lang("This config already exists")
      );
    }

    $update_list = [
      "{$params["key"]}" => "{$params["value"]}",
    ];
    $this->local_db->where("id",$params["id"])->update(local_table_name("b4b_configs"),$update_list);

    $this->clearConfigCache($exist_config["group"]);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Success")
    );
  }


  function delete($params){
    $existence_check = $this->local_db->query("SELECT
                                                    `id`,
                                                    `group`
                                               FROM `".local_table_name("b4b_configs")."`
                                               WHERE `deleted_at` IS NULL
                                               AND `id` = {$params["id"]}");
    if (!$existence_check->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Config doesn't exist")
      );
    }
    $exist_config = $existence_check->row_array();

    $this->local_db->where("id",$params["id"])->update(local_table_name("b4b_configs"),["deleted_at" => now()]);

    $this->clearConfigCache($exist_config["group"]);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Config deleted")
    );
  }

  function status($params){
    $existence_check = $this->local_db->query("SELECT
                                                    `id`,
                                                    `group`
                                               FROM `".local_table_name("b4b_configs")."`
                                               WHERE `deleted_at` IS NULL
                                               AND `id` = {$params["id"]}");
    if (!$existence_check->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Config doesn't exist")
      );
    }
    $exist_config = $existence_check->row_array();

    $this->local_db->where("id",$params["id"])->update(local_table_name("b4b_configs"),["is_active" => $params["is_active"]]);

    $this->clearConfigCache($exist_config["group"]);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Status updated")
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
