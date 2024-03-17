<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  public function index($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $this->local_db->insert(local_table_name("b4b_order_folders"),[
      "name" => $params["name"],
      "icon" => $params["icon"],
      "color" => $params["color"],
      "is_active" => $params["is_active"],
      "is_dev" => $params["is_dev"],
      "order" => $params["order"],
      "description" => $params["description"],
      "system_user_id" => $params["system_user_id"],
    ]);

    return rest_response(
      Status_codes::HTTP_CREATED,
      $params["name"] . " " . lang("_folder created")
    );
  }

  public function addOrder($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $check_order_sql = "SELECT
                          b4b_orders.`product_order_id` as `id`,
                          b4b_orders.`code`,
                          b4b_orders.`group_id`
                        FROM `".local_table_name("b4b_orders")."` b4b_orders
                        WHERE b4b_orders.`deleted_at` IS NULL
                        AND MD5(b4b_orders.`product_order_id`) = '{$params["order_id"]}'
                        ";

    $check_order_query = $this->local_db->query($check_order_sql);
    if(!$check_order_query){
      return rest_response(
        Status_codes::HTTP_OK,
        lang("Order not found")
      );
    }
    $check_order = $check_order_query->row_array();

    checkAdminOrderGroup($check_order["group_id"], $params["auth_user"]);

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
    if(!$check_folder_query){
      return rest_response(
        Status_codes::HTTP_OK,
        lang("Folder not found")
      );
    }
    $check_folder = $check_folder_query->row_array();

    $check_order_folder_sql = "SELECT
                                folder_order.`id`
                              FROM `".local_table_name("b4b_order_folder_joints")."` folder_order
                              WHERE folder_order.`deleted_at` IS NULL
                              AND folder_id = {$params["folder_id"]}
                              AND MD5(order_id) = '{$params["order_id"]}'
                              ";
    $check_order_folder_query = $this->local_db->query($check_order_folder_sql);
    if($check_order_folder_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_IM_USED,
        lang("This order already added to this folder")
      );
    }

    $this->local_db->insert(local_table_name("b4b_order_folder_joints"),[
      "folder_id" => $params["folder_id"],
      "order_id" => $check_order["id"],
      "order_code" => $check_order["code"],
      "is_dev" => $params["is_dev"],
    ]);

    return rest_response(
      Status_codes::HTTP_CREATED,
      lang("Order added to folder")
    );
  }
}
