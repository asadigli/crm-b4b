<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  function index($params){
    escapeAllKeys($params,null,$this->local_db);
    $entry_insert_list = [
      "creator_id" => $params["creator_id"],
      "creator_name" => $params["creator_name"],
      "admin_firstname" => $params["name"],
      "admin_lastname" => $params["surname"],
      "admin_email" => $params["email"],
      "admin_mobile" => $params["phone"],
      // "admin_group_id" => $params["group"],
      "role" => $params["role"],
      "admin_dashboard" => $params["dashboard"],
      "new_password" => password_hash($params["password"],PASSWORD_DEFAULT),
    ];

    $exist_email_sql = "SELECT `admin_id` FROM `".local_table_name("system_users")."` WHERE `deleted_at` IS NULL AND `admin_email` = '{$entry_insert_list["admin_email"]}'";
    $exist_email_query = $this->local_db->query($exist_email_sql);

    if ($exist_email_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_IM_USED,
        lang("User with this email already exists")
      );
    }

    $this->local_db->insert(local_table_name("system_users"),$entry_insert_list);
    $entry_id = $this->local_db->insert_id();

    return rest_response(
      Status_codes::HTTP_CREATED,
      lang("Success"),
      [
        "password" => $params["password"],
        "email" => $params["email"]
      ]
    );

  }
}
