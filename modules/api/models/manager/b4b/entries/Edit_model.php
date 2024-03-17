<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  function index($params){
    if ($params["avatar"]) {
      $this->local_db->where("company_id",$params["company_id"])->update(local_table_name("b4b_companies"),["company_avatar" => $params["avatar"]]);
    }

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Entry updated"),
      [
        "new_password" => $params["password"] ?: null
      ]
    );

  }

  function entryLimit($params){
    escapeAllKeys($params,null,$this->local_db);
    $exist_entry_token_count = 0;
    $exist_entry_token_arr = [];
    $exist_entry_token = $this->local_db->query("SELECT
                                          `id`
                                       FROM `".local_table_name("b4b_entry_tokens")."`
                                       WHERE `deleted_at` IS NULL
                                       AND `entry_id` = '{$params["company_id"]}'
                                       AND `status` != '".special_codes("entry_tokens.statuses.expired")."'");

    $exist_entry_token_count = $exist_entry_token->num_rows() ?: 0;

    if (!(int)$params["reset"]) {
      if ($exist_entry_token_count < $params["entry_count"]) {
        $params["entry_count"] = $params["entry_count"] - $exist_entry_token_count;
      } else if ((int)$exist_entry_token_count === (int)$params["entry_count"]) {
        return rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("_ Im used text", ["count" => $params["entry_count"]])
        );
      } else if($exist_entry_token_count > $params["entry_count"]){
        $this->local_db->where("entry_id",$params["company_id"])->update(local_table_name("b4b_entry_tokens"),["deleted_at" => now()]);
      }
    }else{
      $this->local_db->where("entry_id",$params["company_id"])->update(local_table_name("b4b_entry_tokens"),["deleted_at" => now()]);
    }

    $entry_tokens = [];
    if ($params["entry_count"]) {
      for ($i=0; $i < $params["entry_count"] ; $i++) {
        $entry_tokens[] = [
          "entry_id" => $params["company_id"],
          "token" => bin2hex(openssl_random_pseudo_bytes(40)),
          "status" => ETS_IDLE,
        ];
      }
    }

    $entry_tokens ? $this->local_db->insert_batch(local_table_name("b4b_entry_tokens"),$entry_tokens) : "";


    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Success")
    );
  }

  function delete($params){
    $this->local_db->where("company_id",$params["company_id"])->update(local_table_name("b4b_companies"),["deleted_at" => now()]);

    $this->local_db->where("entry_id",$params["company_id"])->update(local_table_name("b4b_entry_customers"),["deleted_at" => now()]);


    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Company deleted")
    );
  }

  function stockShow($params){
    $this->local_db->where("company_id",$params["company_id"])->update(local_table_name("b4b_companies"),["stock_show" => $params["stock_show"]]);
    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Stock show status updated")
    );
  }

  function storeActive($params){
    $this->local_db->where("company_id",$params["company_id"])->update(local_table_name("b4b_companies"),["is_store_active" => $params["is_store_active"]]);
    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("60-90 day products display status updated")
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
      $params["password"] = password_hash($params["password"],PASSWORD_DEFAULT);
      $this->local_db->where("company_id",$params["company_id"])->update(local_table_name("b4b_companies"),["company_password" => $params["password"]]);
    }
    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Password updated"),
      [
        "password" => $password,
        "email" => $params["email"]
      ]
    );
  }

  function detail($params){
    $this->local_db->where("company_id",$params["company_id"])->update(local_table_name("b4b_companies"),["company_{$params["key"]}" => $params["value"]]);
    $key = ucfirst($params["key"]);
    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("{$key} updated")
    );
  }

  function addCustomer($params){
    $exist_cust_sql = "SELECT
                            `id`,
                            `customer_id`
                       FROM  `".local_table_name("b4b_entry_customers")."`
                       WHERE `deleted_at` IS NULL
                       AND `entry_id` = {$params["id"]}";
    $exist_cust_query = $this->local_db->query($exist_cust_sql);

    $entry_customer_insert_list = [];
    $entry_customer_update_list = [];

    if (!$exist_cust_query->num_rows()) {
      foreach ($params["customers"] as $key => $item) {
        $entry_customer_insert_list[] = [
          "entry_id" => $params["id"],
          "customer_id" => $item,
          "status" => special_codes("b4b_customers.statuses.allowed")
        ];
      }
    }else{
      $exist_customers = $exist_cust_query->result_array();

      $exist_cust_ids = array_map(function($i) { return (int)$i["customer_id"]; },$exist_customers);
      $custs_key_val = [];

      foreach ($exist_customers as $key => $item) {
        $custs_key_val[(int)$item["customer_id"]] = $item["id"];
      }

      $all_customer_ids = array_values(array_unique(array_merge($params["customers"],$exist_cust_ids)));

      foreach ($all_customer_ids as $key => $cust_id) {
        if (!in_array($cust_id,$params["customers"]) && isset($custs_key_val[$cust_id])) {
          $entry_customer_update_list[] = [
            "id" => $custs_key_val[$cust_id],
            "deleted_at" => now()
          ];
        }else if(!in_array($cust_id,$exist_cust_ids)){
          $entry_customer_insert_list[] = [
            "entry_id" => $params["id"],
            "customer_id" => $cust_id,
            "status" => special_codes("b4b_customers.statuses.allowed")
          ];
        }
      }

    }

    $entry_customer_insert_list ? $this->local_db->insert_batch(local_table_name("b4b_entry_customers"),$entry_customer_insert_list) : "";
    $entry_customer_update_list ? $this->local_db->update_batch(local_table_name("b4b_entry_customers"),$entry_customer_update_list,"id") : "";

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Success")
    );
  }


  function editProperties($params){
    $id = $params["id"];
    unset($params["id"]);
    $this->local_db->where("company_id",$id)->update(local_table_name("b4b_companies"),$params);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Entry updated")
    );
  }

  public function editIsBlocked($params)
  {
    $this->local_db->where("company_id", $params["id"])
                   ->update(local_table_name("b4b_companies"),[
                     "is_blocked" => $params["is_blocked"],
                   ]);

    $message = $params["is_blocked"] === STATUS_ACTIVE ? lang("User blocked") : lang("User unblocked");

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      $message
    );
  }
}
