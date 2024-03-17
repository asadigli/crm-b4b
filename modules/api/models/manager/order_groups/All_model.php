<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  function list($params){
    $keyword = "'%{$params["keyword"]}%'";
    $keyword_query = $params["keyword"] ? "AND (order_group.`name` LIKE $keyword
                                                  OR order_group.`description` LIKE $keyword
                                                    OR order_group.`details` LIKE $keyword
                                                      OR warehouse.`depo_name` LIKE $keyword
                                                        OR admin.`admin_firstname` LIKE $keyword
                                                          OR admin.`admin_lastname` LIKE $keyword) " : "";

    $sql = "SELECT
                order_group.`id`,
                order_group.`name`,
                order_group.`description`,
                CAST(order_group.`default_start_date` AS DATE) as `default_start_date`,
                order_group.`details`,
                order_group.`is_remote`,
                order_group.`is_active`,
                order_group.`warehouse_id`,
                CONCAT(admin.`admin_firstname`,' ',admin.`admin_lastname`) as admin,
                warehouse.`depo_name` as warehouse
            FROM `".local_table_name("b4b_order_groups")."` order_group
            LEFT JOIN `".local_table_name("system_users")."` admin ON admin.`admin_id` = order_group.`creator_id`
            LEFT JOIN `".local_table_name("warehouses")."` warehouse ON warehouse.`depo_id` = order_group.`warehouse_id`
            WHERE order_group.`deleted_at` IS NULL
            $keyword_query
            ORDER BY `order` ASC,`id` DESC";

    $query = $this->local_db->query($sql);

    if (!$query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Result not found")
      );
    }

    $result = $query->result_array();

    foreach ($result as $key => $item) {
      $result[$key]["is_active"] = $item["is_active"] === STATUS_ACTIVE;
      $result[$key]["is_remote"] = $item["is_remote"] === STATUS_ACTIVE;
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $result
    );

  }

  function add($params){
    escapeAllKeys($params,null,$this->local_db);

    $exist_group_sql = "SELECT `id`
                        FROM `".local_table_name("b4b_order_groups")."`
                        WHERE `deleted_at` IS NULL
                        AND `name` = '{$params["name"]}'";
    $exist_group_query = $this->local_db->query($exist_group_sql);

    if ($exist_group_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_IM_USED,
        lang("Group with this name already exists")
      );
    }
    $this->local_db->insert(local_table_name("b4b_order_groups"),$params);

    return rest_response(
      Status_codes::HTTP_CREATED,
      lang("Success")
    );
  }

  function detail($params){
    escapeAllKeys($params,null,$this->local_db);

    $this->groupExistenceCheck($params["id"]);
    $this->local_db->where("id",$params["id"])->update(local_table_name("b4b_order_groups"),["{$params["key"]}" => $params["value"]]);
    $key = ucfirst($params["key"]);
    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("{$key} updated")
    );
  }


  function delete($params){
    $this->groupExistenceCheck($params["id"]);
    $this->local_db->where("id",$params["id"])->update(local_table_name("b4b_order_groups"),["deleted_at" => now()]);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Deleted")
    );
  }

  function warehouses($params){
    $sql = "SELECT
                  `depo_id` as id,
                  `depo_name` as name
            FROM ".local_table_name("warehouses")."
            WHERE `deleted_at` IS NULL ";
    $query = $this->local_db->query($sql);

    if (!$query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $warehouses = $query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $warehouses
    );
  }


  function groupExistenceCheck($id){
    $sql_query = "SELECT
                    `id`
                  FROM `".local_table_name("b4b_order_groups")."`
                  WHERE `deleted_at` IS NULL
                  AND `id` = $id";
    $query = $this->local_db->query($sql_query);
    if (!$query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Result not found")
      );
    }
    return true;
  }

}
