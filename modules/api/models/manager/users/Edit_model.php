<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  function index($params){
    $update_list = [
      "admin_firstname" => $params["firstname"],
      "admin_email" => $params["email"],
      "admin_lastname" => $params["lastname"],
      "admin_mobile" => $params["phone"],
      // "admin_group_id" => $params["group_id"],
      "role" => $params["role"],
      "admin_block" => $params["blocked"] === STATUS_YES ? STATUS_YES : STATUS_NO,
      "admin_dashboard" => $params["dashboard"] === STATUS_YES ? STATUS_YES : STATUS_NO
    ];

    $this->local_db->where("admin_id",$params["id"])->update(local_table_name("system_users"),$update_list);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Success")
    );
  }

  function password($params){
    escapeAllKeys($params,null,$this->local_db);

    if ($params["password"]) {
      if (!trim($params["password"])) {
        return rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("Invalid password")
        );
      }


      $params["password"] = str_replace(' ', '-', $params["password"]);
      $params["password"] = preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$params["password"]);

      $password = $params["password"];



      if ($params["type"] === "edit-password") {
        $exist_password = $this->local_db->query("SELECT
                                                `admin_id`,
                                                `new_password` as password
                                            FROM `".local_table_name("system_users")."`
                                            WHERE `deleted_at` IS NULL
                                            AND `admin_id` = {$params["user_id"]} ");
        $password_row = $exist_password->row_array();

        if ($password_row["password"]) {
          $verify_pass = password_verify($params["old_password"],$password_row["password"]);

          if (!$verify_pass) {
            return rest_response(
              Status_codes::HTTP_BAD_REQUEST,
              lang("Old password is incorrect")
            );
          }
        }
      }

      $params["password"] = password_hash($params["password"],PASSWORD_DEFAULT);
      $this->local_db->where("admin_id",$params["user_id"])->update(local_table_name("system_users"),["new_password" => $params["password"]]);
    }
    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Password updated"),
      ["password" => $password]
    );
  }


  function editGroup($params){
    $exist_group_sql = "SELECT
                            `id`,
                            `order_group_id`
                       FROM  `".local_table_name("su_order_groups")."`
                       WHERE `deleted_at` IS NULL
                       AND `system_user_id` = {$params["id"]}";
    $exist_group_query = $this->local_db->query($exist_group_sql);

    $order_group_insert_list = [];
    $order_group_update_list = [];

    if (!$exist_group_query->num_rows()) {
      foreach ($params["groups"] as $key => $item) {
        $order_group_insert_list[] = [
          "system_user_id" => $params["id"],
          "order_group_id" => $item,
        ];
      }
    }else{
      $exist_groups = $exist_group_query->result_array();

      $exist_group_ids = array_map(function($i) { return (int)$i["order_group_id"]; },$exist_groups);
      $groups_key_val = [];

      foreach ($exist_groups as $key => $item) {
        $groups_key_val[(int)$item["order_group_id"]] = $item["id"];
      }

      $all_group_ids = array_values(array_unique(array_merge($params["groups"],$exist_group_ids)));

      foreach ($all_group_ids as $key => $group_id) {
        if (!in_array($group_id,$params["groups"]) && isset($groups_key_val[$group_id])) {
          $order_group_update_list[] = [
            "id" => $groups_key_val[$group_id],
            "deleted_at" => now()
          ];
        }else if(!in_array($group_id,$exist_group_ids)){
          $order_group_insert_list[] = [
            "system_user_id" => $params["id"],
            "order_group_id" => $group_id,
          ];
        }
      }

    }

    $order_group_insert_list ? $this->local_db->insert_batch(local_table_name("su_order_groups"),$order_group_insert_list) : "";
    $order_group_update_list ? $this->local_db->update_batch(local_table_name("su_order_groups"),$order_group_update_list,"id") : "";

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Success")
    );
  }

  function delete($params){
    $this->local_db->where("admin_id",$params["id"])->update(local_table_name("system_users"),["deleted_at" => now()]);
    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Admin deleted")
    );
  }


}
