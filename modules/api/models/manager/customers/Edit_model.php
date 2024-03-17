<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  public function editMaxOrderLimit($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $check_customer_sql = "SELECT
                                customers.`id`,
                                customers.`max_allowed_order_limit`
                              FROM `".local_table_name("cached_customers")."` customers
                              WHERE customers.`deleted_at` IS NULL
                              AND customers.`id` = {$params["id"]}
                              ";
    $check_customer_query = $this->local_db->query($check_customer_sql);
    if(!$check_customer_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Customer not found")
      );
    }
    $check_customer = $check_customer_query->row_array();

    if($params["max_order_limit"] > (float)$check_customer["max_allowed_order_limit"]) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Max order limit could not be more than allowed")
      );
    }

    $this->local_db->where("id",$params["id"]);
    $this->local_db->update(local_table_name("cached_customers"),[
      "max_order_limit" => $params["max_order_limit"]
    ]);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Success")
    );
  }

  public function editMaxAllowedOrderLimit($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $check_customer_sql = "SELECT
                                customers.`id`,
                                customers.`max_order_limit`
                              FROM `".local_table_name("cached_customers")."` customers
                              WHERE customers.`deleted_at` IS NULL
                              AND customers.`id` = {$params["id"]}
                              ";
    $check_customer_query = $this->local_db->query($check_customer_sql);
    if(!$check_customer_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Customer not found")
      );
    }
    $check_customer = $check_customer_query->row_array();

    if($params["max_allowed_order_limit"] < (float)$check_customer["max_order_limit"]) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Max order limit could not be less than allowed")
      );
    }

    $this->local_db->where("id",$params["id"]);
    $this->local_db->update(local_table_name("cached_customers"),[
      "max_allowed_order_limit" => $params["max_allowed_order_limit"]
    ]);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Success")
    );
  }

  public function editHasOrderLimit($params)
  {
    $check_customer_sql = "SELECT
                                customers.`id`
                              FROM `".local_table_name("cached_customers")."` customers
                              WHERE customers.`deleted_at` IS NULL
                              AND customers.`id` = {$params["id"]}
                              ";
    $check_customer_query = $this->local_db->query($check_customer_sql);
    if(!$check_customer_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Customer not found")
      );
    }
    $check_customer = $check_customer_query->row_array();


    $this->local_db->where("id",$params["id"]);
    $this->local_db->update(local_table_name("cached_customers"),[
      "has_order_limit" => $params["has_order_limit"]
    ]);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Success")
    );
  }

  public function editIsBlocked($params)
  {
    $check_customer_sql = "SELECT
                                customers.`id`
                              FROM `".local_table_name("cached_customers")."` customers
                              WHERE customers.`deleted_at` IS NULL
                              AND customers.`id` = {$params["id"]}
                              ";
    $check_customer_query = $this->local_db->query($check_customer_sql);
    if(!$check_customer_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Customer not found")
      );
    }
    $check_customer = $check_customer_query->row_array();


    $this->local_db->where("id",$params["id"]);
    $this->local_db->update(local_table_name("cached_customers"),[
      "is_blocked" => $params["is_blocked"]
    ]);

    $message = (string)$params["is_blocked"] === STATUS_ACTIVE ? lang("Customer blocked") : lang("Customer unblocked");

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      $message
    );
  }

}
