<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  private function userAccessibleGroups($params)
  {
    if($params["auth_user"]["role"] === special_codes("system_users.roles.developer") || $params["auth_user"]["role"] === special_codes("system_users.roles.main_admin")) {
      $su_order_groups_sql = "SELECT
                                b4b_order_groups.`id` as `group_id`
                              FROM `".local_table_name("b4b_order_groups")."` b4b_order_groups
                              WHERE b4b_order_groups.`deleted_at` IS NULL
                              AND b4b_order_groups.`is_active` = '".STATUS_ACTIVE."'
                              ORDER BY b4b_order_groups.`order` ASC";
    } else {
      $su_order_groups_sql = "SELECT
                                su_order_groups.`order_group_id` as `group_id`
                              FROM `".local_table_name("su_order_groups")."` su_order_groups
                              WHERE su_order_groups.`deleted_at` IS NULL
                              AND su_order_groups.`system_user_id` = {$params["auth_user"]["id"]}
                              ";
    }

    $su_order_groups_query = $this->local_db->query($su_order_groups_sql);
    $su_order_groups = $su_order_groups_query->result_array();
    $su_order_groups_list = [];
    if($su_order_groups){
      foreach ($su_order_groups as $key => $item) {
        $su_order_groups_list[] = $item["group_id"];
      }
    }

    return $su_order_groups_list;
  }

  public function index($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    // $is_developer_query = $params["is_dev"] ? " AND folders.`is_dev` IN ('0','1') " : " AND folders.`is_dev` = '0' ";
    $group_ids = $this->userAccessibleGroups($params);

    $folders_sql = "SELECT
                      folders.`id`,
                      folders.`name`,
                      folders.`description`,
                      folders.`icon`,
                      folders.`color`,
                      folders.`is_active`

                    FROM `".local_table_name("b4b_order_folders")."` folders
                    WHERE folders.`deleted_at` IS NULL
                    AND folders.`system_user_id` = {$params["system_user_id"]}
                      ";

    $folders_query = $this->local_db->query($folders_sql);
    if(!$folders_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $orders_list_sql = "SELECT
                          MD5(b4b_orders.`product_order_id`) as `id`,
                          b4b_orders.`code`,
                          (SELECT
                            SUM(IFNULL(`orlist_quantity`,0) * IFNULL(`orlist_price`,0))
                            FROM `".local_table_name("b4b_order_details")."`
                            WHERE `orlist_order_id` = b4b_orders.`product_order_id`
                            AND `group_id` = b4b_orders.`group_id`
                            AND `deleted_at` IS NULL
                          ) as `product_order_amount`,
                          (SELECT
                            COUNT(`orlist_id`)
                            FROM `".local_table_name("b4b_order_details")."`
                            WHERE `orlist_order_id` = b4b_orders.`product_order_id`
                            AND `group_id` = b4b_orders.`group_id`
                            AND `deleted_at` IS NULL
                          ) as `product_order_product_count`,
                          order_folder_joints.`folder_id`
                        FROM `".local_table_name("b4b_order_folder_joints")."` order_folder_joints
                        LEFT JOIN `".local_table_name("b4b_orders")."` b4b_orders ON b4b_orders.`product_order_id` = order_folder_joints.`order_id`
                          AND b4b_orders.`deleted_at` IS NULL
                        WHERE order_folder_joints.`deleted_at` IS NULL
                        AND b4b_orders.`group_id` IN (".implode(",",$group_ids).")
                        ";
    $orders_list_query = $this->local_db->query($orders_list_sql);
    $orders = $orders_list_query->result_array();

    $orders_list = [];
    foreach ($orders as $key => $item) {
      $orders_list[$item["folder_id"]][] = $item;
    }

    $folders = $folders_query->result_array();
    foreach ($folders as $key => $item) {

      $folders[$key]["is_active"] = $item["is_active"] === STATUS_ACTIVE;
      $folders[$key]["orders_count"] = isset($orders_list[$item["id"]]) ? count($orders_list[$item["id"]]) : 0;
      $folders[$key]["orders"] = isset($orders_list[$item["id"]]) ? $orders_list[$item["id"]] : 0;
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => count($folders),
        "list" => $folders
      ]
    );
  }

  public function list($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    // $is_developer_query = $params["is_dev"] ? " AND folders.`is_dev` IN ('0','1') " : " AND folders.`is_dev` = '0' ";
    $group_ids = $this->userAccessibleGroups($params);
    
    $has_current_order_query = "'0' as `has_current_order`";
    if($params["order_id"]) {
      $has_current_order_query = "(SELECT COUNT(1)
                                    FROM `".local_table_name("b4b_order_folder_joints")."`
                                  WHERE `deleted_at` IS NULL
                                  AND `folder_id` = folders.`id`
                                  AND MD5(`order_id`) = '".$params["order_id"]."') as `has_current_order`
                                  ";
    }

    $folders_sql = "SELECT
                      folders.`id`,
                      folders.`name`,
                      folders.`description`,
                      folders.`icon`,
                      folders.`color`,
                      (SELECT COUNT(1)
                        FROM `".local_table_name("b4b_order_folder_joints")."` order_folder_joints
                        LEFT JOIN `".local_table_name("b4b_orders")."` b4b_orders ON b4b_orders.`product_order_id` = order_folder_joints.`order_id`
                          AND b4b_orders.`deleted_at` IS NULL
                        WHERE order_folder_joints.`deleted_at` IS NULL
                        AND order_folder_joints.`folder_id` = folders.`id`
                        AND b4b_orders.`group_id` IN (".implode(",",$group_ids).")
                      ) as `orders_count`,
                      $has_current_order_query
                    FROM `".local_table_name("b4b_order_folders")."` folders
                    WHERE folders.`deleted_at` IS NULL
                    AND folders.`system_user_id` = {$params["system_user_id"]}
                    AND folders.`is_active` = '".STATUS_ACTIVE."'
                      ";

    $folders_query = $this->local_db->query($folders_sql);
    if(!$folders_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $folders = $folders_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => count($folders),
        "list" => $folders
      ]
    );
  }

  public function ordersList($params)
  {
    // $is_developer_query = $params["is_dev"] ? " AND folders.`is_dev` IN ('0','1') " : " AND folders.`is_dev` = '0' ";

    $check_folder_sql = "SELECT
                          folders.`id`,
                          folders.`name`
                        FROM `".local_table_name("b4b_order_folders")."` folders
                        WHERE folders.`deleted_at` IS NULL
                        AND folders.`is_active` = '".STATUS_ACTIVE."'
                        AND folders.`id` = {$params["folder_id"]}
                        AND folders.`system_user_id` = {$params["system_user_id"]}
                        ";

    $check_folder_query = $this->local_db->query($check_folder_sql);
    if(!$check_folder_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_OK,
        lang("Folder not found")
      );
    }

    // $is_developer_query = $params["is_dev"] ? " AND folder_order.`is_dev` IN ('0','1') " : " AND folder_order.`is_dev` = '0' ";

    $check_order_folder_sql = "SELECT
                                folder_order.`id`,
                                MD5(folder_order.`order_id`) as `order_id`,
                                folder_order.`order_code`
                              FROM `".local_table_name("b4b_order_folder_joints")."` folder_order
                              WHERE folder_order.`deleted_at` IS NULL
                              AND folder_id = {$params["folder_id"]}
                              ";
    $check_order_folder_query = $this->local_db->query($check_order_folder_sql);
    if(!$check_order_folder_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $check_order_folder = $check_order_folder_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => count($check_order_folder),
        "list" => $check_order_folder
      ]
    );
  }
}
