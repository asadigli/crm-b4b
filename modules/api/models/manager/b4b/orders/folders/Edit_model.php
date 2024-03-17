<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  public function editName($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $check_folder_sql = "SELECT
                          folders.`id`,
                          folders.`name`
                        FROM `".local_table_name("b4b_order_folders")."` folders
                        WHERE folders.`deleted_at` IS NULL
                        AND folders.`id` = {$params["id"]}
                        AND folders.`system_user_id` = {$params["system_user_id"]}
                        ";

    $check_folder_query = $this->local_db->query($check_folder_sql);
    if(!$check_folder_query){
      return rest_response(
        Status_codes::HTTP_OK,
        lang("Folder not found")
      );
    }

    $this->local_db->where("id", $params["id"]);
    $this->local_db->update(local_table_name("b4b_order_folders"),[
      "name" => $params["name"]
    ]);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Success")
    );
  }

  public function editDescription($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $check_folder_sql = "SELECT
                          folders.`id`,
                          folders.`name`
                        FROM `".local_table_name("b4b_order_folders")."` folders
                        WHERE folders.`deleted_at` IS NULL
                        AND folders.`id` = {$params["id"]}
                        AND folders.`system_user_id` = {$params["system_user_id"]}
                        ";

    $check_folder_query = $this->local_db->query($check_folder_sql);
    if(!$check_folder_query){
      return rest_response(
        Status_codes::HTTP_OK,
        lang("Folder not found")
      );
    }

    $this->local_db->where("id", $params["id"]);
    $this->local_db->update(local_table_name("b4b_order_folders"),[
      "description" => $params["description"]
    ]);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Success")
    );
  }

  public function editIsActive($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $check_folder_sql = "SELECT
                          folders.`id`,
                          folders.`name`
                        FROM `".local_table_name("b4b_order_folders")."` folders
                        WHERE folders.`deleted_at` IS NULL
                        AND folders.`id` = {$params["id"]}
                        AND folders.`system_user_id` = {$params["system_user_id"]}
                        ";

    $check_folder_query = $this->local_db->query($check_folder_sql);
    if(!$check_folder_query){
      return rest_response(
        Status_codes::HTTP_OK,
        lang("Folder not found")
      );
    }

    $this->local_db->where("id", $params["id"]);
    $this->local_db->update(local_table_name("b4b_order_folders"),[
      "is_active" => $params["is_active"]
    ]);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Success")
    );
  }

  public function delete($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $check_folder_sql = "SELECT
                          folders.`id`,
                          folders.`name`
                        FROM `".local_table_name("b4b_order_folders")."` folders
                        WHERE folders.`deleted_at` IS NULL
                        AND folders.`id` = {$params["id"]}
                        AND folders.`system_user_id` = {$params["system_user_id"]}
                        ";

    $check_folder_query = $this->local_db->query($check_folder_sql);
    if(!$check_folder_query){
      return rest_response(
        Status_codes::HTTP_OK,
        lang("Folder not found")
      );
    }

    $this->local_db->where("id", $params["id"]);
    $this->local_db->update(local_table_name("b4b_order_folders"),[
      "deleted_at" => $params["deleted_at"]
    ]);

    $this->local_db->where("folder_id", $params["id"]);
    $this->local_db->update(local_table_name("b4b_order_folder_joints"),[
      "deleted_at" => $params["deleted_at"]
    ]);

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success")
    );
  }

  public function removeOrder($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $check_folder_sql = "SELECT
                          folders.`id`,
                          folders.`name`
                        FROM `".local_table_name("b4b_order_folders")."` folders
                        WHERE folders.`deleted_at` IS NULL
                        AND folders.`id` = {$params["id"]}
                        AND folders.`system_user_id` = {$params["system_user_id"]}
                        ";

    $check_folder_query = $this->local_db->query($check_folder_sql);
    if(!$check_folder_query){
      return rest_response(
        Status_codes::HTTP_OK,
        lang("Folder not found")
      );
    }

    $check_order_folder_sql = "SELECT
                                folder_order.`id`
                              FROM `".local_table_name("b4b_order_folder_joints")."` folder_order
                              WHERE folder_order.`deleted_at` IS NULL
                              AND folder_id = {$params["id"]}
                              AND MD5(order_id) = {$params["order_id"]}
                              ";
    $check_order_folder_query = $this->local_db->query($check_order_folder_sql);
    if(!$check_order_folder_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_IM_USED,
        lang("Order not found in this folder")
      );
    }
    $check_order_folder = $check_order_folder_query->row_array();

    $this->local_db->where("id", $check_order_folder["id"]);
    $this->local_db->update(local_table_name("b4b_order_folder_joints"),[
      "deleted_at" => $params["deleted_at"]
    ]);

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Order removed from folder")
    );
  }
}
