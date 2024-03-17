<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model{

  public function __construct() {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }


  function index($params){
    if (isset($params["email"])) {
      $old_password = md5(md5($params["password"].'13').md5($params["password"].'30').'17');
      $params["email"] = strtolower($params["email"]);
      $where_condition = " WHERE sytem_user.`admin_email` = '{$params["email"]}' ";
    } else {
      $where_condition = " WHERE sytem_user.`admin_id` = '{$params["admin_id"]}' ";
    }

    $user_query = $this->local_db->query("SELECT
                                            sytem_user.`admin_id` as id,
                                            sytem_user.`admin_firstname` as name,
                                            sytem_user.`admin_lastname` as lastname,
                                            sytem_user.`admin_email` as admin_email,
                                            sytem_user.`admin_mobile` as phone,
                                            sytem_user.`admin_photo_url` as photo,
                                            sytem_user.`admin_ava_manager` as ava_manager,
                                            sytem_user.`admin_password` as password,
                                            sytem_user.`new_password` as new_password,
                                            sytem_user.`admin_group_id` as group_id,
                                            admin_group.`admin_group_name` as group_name,
                                            sytem_user.`admin_dashboard` as dashboard,
                                            sytem_user.`role` as role,
                                            sytem_user.`is_developer`
                                        FROM `".local_table_name("system_users")."` sytem_user
                                        LEFT JOIN `".local_table_name("su_groups")."` admin_group ON admin_group.`admin_group_id` = sytem_user.`admin_group_id`
                                        $where_condition
                                        AND sytem_user.`admin_block` = '".STATUS_NO."'
                                        AND sytem_user.`deleted_at` IS NULL
                                        LIMIT 1");

    if (!$user_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("User does not exist")
      );
    }
    $user_row = $user_query->row_array();

    if($user_row["role"] === special_codes("system_users.roles.developer") || $user_row["role"] === special_codes("system_users.roles.main_admin")) {
      $su_order_groups_sql = "SELECT
                                b4b_order_groups.`id`,
                                b4b_order_groups.`default_start_date` as `default_start_date`,
                                b4b_order_groups.`description` as `name`
                              FROM `".local_table_name("b4b_order_groups")."` b4b_order_groups
                              WHERE b4b_order_groups.`deleted_at` IS NULL
                              AND b4b_order_groups.`is_active` = '".STATUS_ACTIVE."'
                              ORDER BY b4b_order_groups.`order` ASC ";
    } else {
      $su_order_groups_sql = "SELECT
                                su_order_groups.`order_group_id` as `id`,
                                b4b_order_groups.`default_start_date` as `default_start_date`,
                                b4b_order_groups.`description` as `name`
                              FROM `".local_table_name("su_order_groups")."` su_order_groups
                              LEFT JOIN `".local_table_name("b4b_order_groups")."` b4b_order_groups ON b4b_order_groups.`id` = su_order_groups.`order_group_id`
                                AND b4b_order_groups.`deleted_at` IS NULL
                                AND b4b_order_groups.`is_active` = '".STATUS_ACTIVE."'
                              WHERE su_order_groups.`deleted_at` IS NULL
                              AND   su_order_groups.`system_user_id` = {$user_row["id"]}
                              ORDER BY b4b_order_groups.`order` ASC ";
    }

    $su_order_groups_query = $this->local_db->query($su_order_groups_sql);
    $su_order_groups = $su_order_groups_query->result_array();
    $su_order_groups_list = [];
    if($su_order_groups){
      foreach ($su_order_groups as $key => $item) {
        $su_order_groups_list[] = $item;
      }
    }

    $remember_me_token = $this->getRememberMeToken([
      "remember_me" => $params["remember_me"],
      "user_ip" => $params["user_ip"],
      "admin_id" => $user_row["id"],
      "user_agent" => $params["user_agent"],
      "previous_token" => isset($params["previous_token"]) ? $params["previous_token"] : NULL
    ]);

    $user_row["role"] = $user_row["role"] ? special_codes("system_users.roles", $user_row["role"]) : NULL;

    $user_row["dashboard"] = $user_row["dashboard"] === STATUS_YES;

    if (isset($params["email"])) {
      $user_row["is_developer"] = $user_row["is_developer"] === STATUS_ACTIVE;
      if (!$user_row["new_password"]) {
        $verify_pass = $user_row["password"] === $old_password;
      }else{
        $verify_pass = password_verify($params["password"],$user_row["new_password"]);
      }

      if (!$verify_pass) {
        return rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("Password or username is incorrect")
        );
      }

      if (!$user_row["new_password"]) {
        $new_password = password_hash($params["password"],PASSWORD_DEFAULT);
        $this->local_db->where("admin_id",$user_row["id"])->update(local_table_name("system_users"),["new_password" => $new_password]);
      }
      $user_row["password"] = $user_row["new_password"];
      unset($params["new_password"]);
    }

    $user_row["remember_me_token"] = $remember_me_token;
    $user_row["allowed_order_groups"] = $su_order_groups_list;
    unset($user_row["new_password"]);
    unset($user_row["password"]);

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $user_row
    );
  }
  /*
  *
  *
  */
  function loginWithToken($params){
    escapeAllKeys($params,null,$this->local_db);
    $user_agent = md5(isset($params["user_secret"]) ? $params["user_secret"] : "nothing");

    $token_query = $this->local_db->select("*")
                                  ->from(local_table_name("user_remember_tokens"))
                                  ->where("token",$params["token"])
                                  ->where("deleted_at",null)
                                  ->where("expired_at",null)
                                  ->order_by("id","DESC")
                                  ->limit(1)
                                  ->get();

    if (!$token_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("User not found")
      );
    }
    $token = $token_query->row_array();

    return $this->index([
      "admin_id" => $token["system_user_id"],
      "remember_me" => 1,
      "user_ip" => $params["user_ip"],
      "user_agent" => headers("useragent"),
      "previous_token" => $params["token"],
    ]);

  }
  /*
  *
  *
  */
  function getRememberMeToken($params) {
    $remember_me_token = null;

    $user_secret = md5(isset($params["user_agent"]) ? $params["user_agent"] : "nothing");

    if ($params["previous_token"]) {
      $token_query = $this->local_db->select("*")
                                ->from(local_table_name("user_remember_tokens"))
                                  ->where("system_user_id",$params["admin_id"])
                                    ->where("token",$params["previous_token"])
                                      ->where("expired_at",NULL)
                                        ->order_by("id","DESC")
                                          ->get();
    }

   if ($params["previous_token"] && $token_query->num_rows()) {
     $token = $token_query->row_array();
     if (time() - strtotime($token["created_at"]) >= $this->config->item("token_reliability")) {
       $remember_me_token = bin2hex(openssl_random_pseudo_bytes(40)) . md5(microtime());

       $this->local_db->where("id",$token["id"]);
       $this->local_db->update(local_table_name("user_remember_tokens"),[
         "expired_at" => date("Y-m-d H:i:s")
       ]);
       $this->local_db->insert(local_table_name("user_remember_tokens"),[
         "token" => $remember_me_token,
         "previous_token" => $params["previous_token"],
         "system_user_id" => $params["admin_id"],
         "user_ip" => $params["user_ip"],
         "user_agent" => $params["user_agent"] ?: null,
         "user_secret" => $user_secret,
       ]);
     } else {
       $remember_me_token = $token["token"];
     }
   } else {
     $remember_me_token = bin2hex(openssl_random_pseudo_bytes(40)) . md5(microtime());

     $this->local_db->insert(local_table_name("user_remember_tokens"),[
       "token" => $remember_me_token,
       "system_user_id" => $params["admin_id"],
       "user_ip" => $params["user_ip"] ?: null,
       "user_agent" => $params["user_agent"] ?: null,
       "user_secret" => $user_secret ?: null,
     ]);
   }

   return $remember_me_token;
  }


}
