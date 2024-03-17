<?php
defined('BASEPATH') or exit('No direct script access allowed');

class All_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db", true);
  }

  function index($params)
  {
    $keyword = "'%{$params["keyword"]}%'";
    $keyword_query = $params["keyword"] ? "AND (admin.`admin_firstname` LIKE $keyword
                                                 OR admin.`admin_lastname` LIKE $keyword
                                                   OR admin.`admin_email` LIKE $keyword
                                                      OR admin.`admin_mobile` LIKE $keyword
                                                        OR admin_group.`admin_group_name` LIKE $keyword) " : "";
    $admins_sql = "SELECT
                      admin.`admin_id` as id,
                      admin.`admin_firstname` as firstname,
                      admin.`admin_lastname` as lastname,
                      admin.`admin_email` as email,
                      admin.`is_developer`,
                      admin.`admin_block` as blocked,
                      admin.`admin_dashboard` as dashboard,
                      admin.`admin_mobile` as phone,
                      admin.`admin_photo_url` as photo,
                      admin.`admin_ava_manager` as ava_manager,
                      admin.`admin_ins_date` as created_at,
                      admin.`admin_group_id` as `group_id`,
                      admin.`role`,
                      admin_group.`admin_group_name` as `group`,
                      (SELECT
                          GROUP_CONCAT(CONCAT(order_group.`name`, '||', order_group.`id`) SEPARATOR '|ARRAY_DIVIDER|')
                        FROM `" . local_table_name("su_order_groups") . "` su_group
                        LEFT JOIN `" . local_table_name("b4b_order_groups") . "`order_group ON order_group.`id` = su_group.`order_group_id`
                        WHERE su_group.`system_user_id` = admin.`admin_id`
                        AND su_group.`deleted_at` IS NULL
                      ) as admin_groups
                      FROM `" . local_table_name("system_users") . "` admin
                      LEFT JOIN `" . local_table_name("su_groups") . "` admin_group ON admin_group.`admin_group_id` = admin.`admin_group_id`
                      WHERE admin.`deleted_at` IS NULL
                      $keyword_query
                      ORDER BY admin.`admin_id` DESC";

    $admins_query = $this->local_db->query($admins_sql);

    if (!$admins_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("System user not found")
      );
    }
    $admins = $admins_query->result_array();

    foreach ($admins as $key => $admin) {
      $admin_groups = [];
      if ($admin["admin_groups"]) {
        $admin_group_details = explode("|ARRAY_DIVIDER|", $admin["admin_groups"]);
        foreach ($admin_group_details as $sub_key => $group_detail) {
          $group_detail = explode("||", $group_detail);
          $admin_groups[$sub_key]["id"] = $group_detail[1];
          $admin_groups[$sub_key]["name"] = $group_detail[0];
        }
      }
      $admins[$key]["admin_groups"] = $admin_groups;
      if ($admin["role"]) {
        $admins[$key]["role"] = special_codes("system_users.roles", $admin["role"]);
      }
      $admins[$key]["blocked"] = $admin["blocked"] === STATUS_YES;
      $admins[$key]["dashboard"] = $admin["dashboard"] === STATUS_YES;
      $admins[$key]["is_developer"] = $admin["is_developer"] === STATUS_ACTIVE;
    }


    $groups_query = $this->local_db->query("SELECT
                                           `admin_group_id` as id,
                                           `admin_group_name` as name
                                      FROM `" . local_table_name("su_groups") . "`
                                      WHERE `deleted_at` IS NULL");
    $groups = [];
    if ($groups_query->num_rows()) {
      $groups = $groups_query->result_array();
    }


    $roles = new stdClass();
    foreach (special_codes("system_users.roles") as $key => $value) {
      $name = lang(ucfirst($key));
      $roles->$key = $name;
    }
    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "admins" => $admins,
        "groups" => $groups,
        "roles" => $roles
      ]
    );
  }

  function groups($params)
  {
    $sql = "SELECT
                  `admin_group_id` as id,
                  `admin_group_name` as name
            FROM " . local_table_name("su_groups") . "
            WHERE `deleted_at` IS NULL ";
    $query = $this->local_db->query($sql);

    if (!$query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $groups = $query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $groups
    );
  }

  function orderGroups($params)
  {
    $sql = "SELECT
                  `id`,
                  `name`
            FROM " . local_table_name("b4b_order_groups") . "
            WHERE `deleted_at` IS NULL ";
    $query = $this->local_db->query($sql);

    if (!$query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $groups = $query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $groups
    );
  }





}


