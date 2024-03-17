<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists("checkCompany")) {
  function checkCompany($id = null,$full_data = false,$auth_check = true,$not_actives_too = false){
    $id = $id ?: headers("b4buserid");
    $customer_id = headers("remoteb4buserid");

    if(!$id) {
      json_response(rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Entry not found")
      ));
    }

    $CI = get_instance();
    $CI->local_db = $CI->load->database("local_db",true);

    $not_actives_too_query = $not_actives_too ? "" : "AND entry.`is_active` = '".STATUS_ACTIVE."'";

    $entry_sql_query = "SELECT
                          entry.`company_id` as `id`,
                          entry.`company_city_id` as `city_id`,
                          entry.`company_depo_id` as `depo_id`,
                          entry.`__company_ava_id` as `ava_id`,
                          entry.`company_user_count` as `user_count`,
                          entry.`company_curator_id` as `curator_id`,
                          entry.`company_percent` as `percent`,
                          entry.`company_price_percent` as `price_percent`,
                          entry.`company_discount_percent` as `discount_percent`,
                          entry.`company_monthly_limit` as `monthly_limit`,
                          entry.`company_name` as `name`,
                          entry.`company_phone` as `phone`,
                          entry.`price_show` as `price_show`,
                          entry.`stock_show` as `stock_show`,
                          entry.`company_email` as `email`,
                          entry.`is_store_active`,
                          entry.`is_blocked`
                        FROM `".local_table_name("b4b_companies")."` entry
                        WHERE entry.`deleted_at` IS NULL
                        $not_actives_too_query
                        AND entry.`company_id` = {$id}
                        LIMIT 1";
                        // log_message("ERROR",$entry_sql_query);

    $entry_query = $CI->local_db->query($entry_sql_query);

    if (!$entry_query->num_rows()) {
      json_response(rest_response(
        $auth_check ? Status_codes::HTTP_UNAUTHORIZED : Status_codes::HTTP_NO_CONTENT,
        lang("Entry not found")
      ));
    }


    $entry = $entry_query->row_array();

    if($auth_check && $entry["is_blocked"] === STATUS_ACTIVE) {
      json_response(rest_response(
        Status_codes::HTTP_LOCKED,
        lang("User is blocked")
      ));
    }



    if($auth_check) {
      if (!$customer_id){
         json_response(rest_response(
          Status_codes::HTTP_NO_CONTENT,
          lang("Customer not found")
        ));
      }
      $check_customer_sql = "SELECT
                                customers.`id`,
                                customers.`is_blocked`
                              FROM `".local_table_name("cached_customers")."` customers
                              WHERE customers.`deleted_at` IS NULL
                              AND customers.`remote_id` = {$customer_id}
                              ";
      $check_customer_query = $CI->local_db->query($check_customer_sql);
      if(!$check_customer_query->num_rows()) {
        json_response(rest_response(
          Status_codes::HTTP_NO_CONTENT,
          lang("Customer not found")
        ));
      }

      $check_customer = $check_customer_query->row_array();

      if($check_customer["is_blocked"] === STATUS_ACTIVE) {
        json_response(rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("Customer is blocked")
        ));
      }

    }



    return $full_data ? $entry : true;
  }
}

if (!function_exists("checkAdmin")) {
  function checkAdmin($user = null,$show_full = null){
    $user_id = $user ?: headers("userid");
    $CI = get_instance();
    if (!is_numeric($user_id)) {
      json_response(rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Entry not found")
      ));
      die;
    }
    $local_db = $CI->load->database("local_db",true);

    $sql_query = "SELECT
                                    system_users.`admin_id` as  `id`,
                                    system_users.`admin_group_id` as `group_id`,
                                    system_users.`admin_dashboard` as `dashboard`,
                                    system_users.`admin_email` as `email`,
                                    system_users.`admin_firstname` as `first_name`,
                                    system_users.`admin_lastname` as `last_name`,
                                    system_users.`admin_mobile` as `mobile`,
                                    system_users.`role` as `role`,
                                    system_users.`admin_ava_manager` as `ava_manager`,
                                    system_users.`admin_photo_url` as `photo_url`,
                                    -- system_users.`is_main_developer`
                                    system_users.`is_developer`
                              FROM `".local_table_name("system_users")."` system_users
                              WHERE system_users.`deleted_at` IS NULL
                              -- AND system_users.`admin_block` = '".STATUS_NO."'
                              AND system_users.`admin_delete` = '".STATUS_NO."'
                              AND system_users.`admin_id` = {$user_id}";
    // log_message("ERROR",$sql_query);
    $query = $local_db->query($sql_query);
    if (!$query->num_rows()) {
      // return rest_response(
      //   Status_codes::HTTP_NO_CONTENT,
      //   lang("Entry not found")
      // );
      json_response(rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("System user not found")
      ));
      die;
    }

    $user = $query->row_array();

    return $show_full ? $user : true;
  }
}

if(!function_exists("checkAdminOrderGroup")) {

  function checkAdminOrderGroup($group_id, $auth_user){
    $CI = get_instance();
    $CI->local_db = $CI->load->database("local_db",true);

    if($auth_user["role"] === special_codes("system_users.roles.developer") || $auth_user["role"] === special_codes("system_users.roles.main_admin")) {
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
                              AND su_order_groups.`system_user_id` = {$auth_user["id"]}
                              ";
    }

    $su_order_groups_query = $CI->local_db->query($su_order_groups_sql);
    $su_order_groups = $su_order_groups_query->result_array();
    $su_order_groups_list = [];
    if($su_order_groups){
      foreach ($su_order_groups as $key => $item) {
        $su_order_groups_list[] = $item["group_id"];
      }
    }

    if(!in_array($group_id, $su_order_groups_list)) {
      return json_response(rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Special error").": ".special_codes("app_status_codes.errors.group_not_found.code")
      ));
    }
  }
}

if (!function_exists("checkEntryToken")) {
  function checkEntryToken($token = null,$show_full = null,$auth_check = false,$log = true){
    // $user_id = headers("userid");
    $CI = get_instance();
    $local_db = $CI->load->database("local_db",true);

    $query = $local_db->query("SELECT
                                      *
                               FROM `".local_table_name("b4b_entry_tokens")."`
                               WHERE `deleted_at` IS NULL
                               AND `status` = '".special_codes("b4b_entries.statuses.reserved")."'
                               AND `token` = '{$token}'");
    if (!$query->num_rows()) {
      json_response(rest_response(
        $auth_check ? Status_codes::HTTP_UNAUTHORIZED : Status_codes::HTTP_NO_CONTENT,
        lang("Entry not found")
      ),null,$log);
      die;
    }

    $entry_token_row = $query->row_array();

    return $show_full ? $entry_token_row : true;
  }
}
