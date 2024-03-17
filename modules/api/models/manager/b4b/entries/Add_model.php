<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  function index($params){
    escapeAllKeys($params,null,$this->local_db);
    $entry_insert_list = [
      "company_name" => $params["entry_name"],
      "company_curator_id" => $params["supervisor"],
      "company_phone" => $params["phone"],
      "person_name" => $params["name"],
      "company_city_id" => $params["city_id"],
      "company_depo_id" => $params["depo_id"],
      "company_address" => $params["address"],
      "person_surname" => $params["surname"],
      "company_email" => $params["email"],
      "stock_show" => $params["stock_show"],
      "is_active" => $params["is_active"],
      "is_store_active" => $params["is_store_active"]
    ];

    if ($params["password"]) {
      $entry_insert_list["company_password"] = password_hash($params["password"],PASSWORD_DEFAULT);
    }

    $exist_email_sql = "SELECT `company_id` FROM `".local_table_name("b4b_companies")."` WHERE `deleted_at` IS NULL AND `company_email` = '{$entry_insert_list["company_email"]}'";
    $exist_email_query = $this->local_db->query($exist_email_sql);

    if ($exist_email_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_IM_USED,
        lang("User with this email already exists")
      );
    }

    $this->local_db->insert(local_table_name("b4b_companies"),$entry_insert_list);
    $entry_id = $this->local_db->insert_id();

    if ($params["limit"]) {
      for ($i=0; $i < $params["limit"] ; $i++) {
        $entry_token_insert_list[] = [
          "entry_id" => $entry_id,
          "token" => bin2hex(openssl_random_pseudo_bytes(40)),
          "status" => special_codes("entry_tokens.statuses.idle"),
        ];
      }
      $this->local_db->insert_batch(local_table_name("b4b_entry_tokens"),$entry_token_insert_list);
    }


    $entry_customer_insert_list = [];
    foreach ($params["customers"] as $key => $item) {
      $entry_customer_insert_list[] = [
        "entry_id" => $entry_id,
        "customer_id" => $item,
        "status" => special_codes("b4b_customers.statuses.allowed")
      ];
    }


    $this->local_db->insert_batch(local_table_name("b4b_entry_customers"),$entry_customer_insert_list);


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
