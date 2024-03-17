<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  public function transferOrder($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $check_order_details_sql = "SELECT
                                  b4b_order_details.`orlist_id` as `id`,
                                  b4b_order_details.`status`,
                                  b4b_order_details.`orlist_quantity` as `quantity`,
                                  b4b_order_details.`orlist_price` as `price`,
                                  b4b_order_details.`orlist_currency` as `currency`,
                                  b4b_order_details.`orlist_from` as `from`,
                                  b4b_order_details.`orlist_name` as `name`,
                                  b4b_order_details.`orlist_brend` as `brand_name`,
                                  b4b_order_details.`orlist_brend_code` as `brand_code`,
                                  b4b_order_details.`orlist_brend_org_code` as `OEM`,
                                  b4b_order_details.`orlist_description` as `description`,
                                  b4b_order_details.`group_id` as `group_id`,
                                  b4b_order_details.`orlist_basket_id` as `cart_id`,
                                  b4b_orders.`status` as `order_status`,
                                  b4b_orders.`product_order_ins_date` as `product_order_ins_date`,
                                  b4b_orders.`product_order_id` as `order_id`,
                                  b4b_orders.`remote_customer_id` as `order_remote_customer_id`,
                                  b4b_orders.`product_order_company_id` as `order_product_order_company_id`,
                                  b4b_orders.`product_order_user_ip` as `order_product_order_user_ip`,
                                  b4b_orders.`product_order_currency` as `order_product_order_currency`,
                                  b4b_orders.`product_order_status_id` as `order_product_order_status_id`,
                                  b4b_orders.`product_order_store_status_id` as `order_product_order_store_status_id`,
                                  b4b_orders.`product_order_depo_id` as `order_product_order_depo_id`,
                                  b4b_orders.`product_order_comment` as `order_product_order_comment`,
                                  b4b_orders.`group_id` as `order_group_id`,
                                  groups.`name` as `order_group_name`
                                FROM `".local_table_name("b4b_order_details")."` b4b_order_details
                                LEFT JOIN `".local_table_name("b4b_orders")."` b4b_orders ON b4b_orders.`product_order_id` = b4b_order_details.`orlist_order_id`
                                  AND b4b_orders.`deleted_at` IS NULL
                                LEFT JOIN `".local_table_name("b4b_order_groups")."` groups ON groups.`id` = b4b_orders.`group_id`
                                  AND groups.`deleted_at` IS NULL
                                WHERE b4b_order_details.`deleted_at` IS NULL
                                AND MD5(b4b_order_details.`orlist_order_id`) = '{$params["id"]}'
                                ";
    $check_order_details_query = $this->local_db->query($check_order_details_sql);
    if(!$check_order_details_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Order not found")
      );
    }
    $check_order_details = $check_order_details_query->result_array();

    $order_group_id = NULL;
    $order_id = NULL;
    foreach ($check_order_details as $key => $item) {
      $order_group_id = $item["order_group_id"];
      $order_id = $item["order_id"];
    }
    checkAdminOrderGroup($order_group_id,$params["auth_user"]);

    $order_detail_list = [];
    foreach ($params["order_detail_list"] as $key => $item) {
      $order_detail_list[$item["id"]] = $item;
    }

    $order_detail_list_transfer_to_same_group = [];
    $order_detail_list_transfer_to_new_group  = [];

    $order_transfer_to_same = [];
    $order_transfer_to_new = [];

    $order_transfer_to_new_price_amount = 0;
    $order_transfer_to_same_price_amount = 0;

    foreach ($check_order_details as $key => $item) {
      if($item["order_status"] !== special_codes("b4b_orders.statuses.pending")) {
        return rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("Only orders with pending status could be transfer")
        );
      }

      if((int)$item["order_group_id"] === (int)$params["group_id"]) {
        return rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("Order could not be transfered to yourself")
        );
      }

      $order_group_id = $item["order_group_id"];

      if(isset($order_detail_list[$item["id"]])) {

        if((float)$order_detail_list[$item["id"]]["quantity"] > (float)$item["quantity"]) {
          return rest_response(
            Status_codes::HTTP_BAD_REQUEST,
            lang("You can't transfer more quantity than order quantity")
          );
        }

        if ((float)$order_detail_list[$item["id"]]["quantity"] <= (float)0) {
          return rest_response(
            Status_codes::HTTP_BAD_REQUEST,
            lang("You can't transfer zero or below quantity")
          );
        }

        if ((float)($item["quantity"] - $order_detail_list[$item["id"]]["quantity"]) < (float)0) {
          return rest_response(
            Status_codes::HTTP_BAD_REQUEST,
            lang("You can't transfer ended quantity product")
          );
        }

        $order_transfer_to_new_detail = [
          "group_id" => $params["group_id"],
          "status" => special_codes("b4b_orders.statuses.pending"),
          "orlist_quantity" => $order_detail_list[$item["id"]]["quantity"],
          "orlist_price" => $item["price"],
          "orlist_currency" => $item["currency"],
          "orlist_from" => $item["from"],
          "orlist_name" => $item["name"],
          "orlist_brend" => $item["brand_name"],
          "orlist_brend_code" => $item["brand_code"],
          "orlist_brend_org_code" => $item["OEM"],
          "orlist_description" => $item["description"],
        ];
        $order_detail_list_transfer_to_new_group[] = $order_transfer_to_new_detail;

        $order_transfer_to_new_price_amount += $item["price"] * $order_detail_list[$item["id"]]["quantity"];

        $order_transfer_to_new = [
          "parent_id" => $item["order_id"],
          "system_user_id" => $params["userid"],
          "group_id" => $params["group_id"],
          "status" => special_codes("b4b_orders.statuses.pending"),
          "remote_customer_id" => $item["order_remote_customer_id"],
          "product_order_company_id" => $item["order_product_order_company_id"],
          "product_order_user_ip" => $item["order_product_order_user_ip"],
          "product_order_currency" => $item["order_product_order_currency"],
          "product_order_depo_id" => $item["order_product_order_depo_id"],
          "product_order_comment" => $item["order_product_order_comment"],
          "system_user_comment" => $params["comment"],
          "product_order_product_count" => count($order_detail_list_transfer_to_new_group),
          "product_order_amount" => $order_transfer_to_new_price_amount,
          "product_order_approve" => STATUS_YES,
          "from_transfer" => STATUS_ACTIVE,
          "from_transfer_group_name" => $item["order_group_name"],
          "product_order_ins_date" => $item["product_order_ins_date"],
        ];

      }

      $same_order_details_quantity = isset($order_detail_list[$item["id"]]["quantity"]) ? $item["quantity"] - $order_detail_list[$item["id"]]["quantity"] : $item["quantity"];

      if((float)$same_order_details_quantity > (float)0) {
        $order_transfer_to_same_detail = [
          "group_id" => $item["group_id"],
          "status" => special_codes("b4b_orders.statuses.pending"),
          "orlist_quantity" => $same_order_details_quantity,
          "orlist_price" => $item["price"],
          "orlist_currency" => $item["currency"],
          "orlist_from" => $item["from"],
          "orlist_name" => $item["name"],
          "orlist_brend" => $item["brand_name"],
          "orlist_brend_code" => $item["brand_code"],
          "orlist_brend_org_code" => $item["OEM"],
          "orlist_description" => $item["description"],
        ];
        $order_detail_list_transfer_to_same_group[] = $order_transfer_to_same_detail;

        $order_transfer_to_same_price_amount += $item["price"] * $same_order_details_quantity;
        $order_transfer_to_same = [
          "parent_id" => $item["order_id"],
          "system_user_id" => $params["userid"],
          "group_id" => $item["group_id"],
          "status" => special_codes("b4b_orders.statuses.pending"),
          "remote_customer_id" => $item["order_remote_customer_id"],
          "product_order_company_id" => $item["order_product_order_company_id"],
          "product_order_user_ip" => $item["order_product_order_user_ip"],
          "product_order_currency" => $item["order_product_order_currency"],
          "product_order_depo_id" => $item["order_product_order_depo_id"],
          "product_order_comment" => $item["order_product_order_comment"],
          "product_order_product_count" => count($order_detail_list_transfer_to_same_group),
          "product_order_amount" => $order_transfer_to_same_price_amount,
          "product_order_approve" => STATUS_YES,
          "from_transfer" => STATUS_ACTIVE,
          "from_transfer_group_name" => $item["order_group_name"],
          "product_order_ins_date" => $item["product_order_ins_date"],
        ];
      }

    }

    if(!$order_detail_list_transfer_to_new_group) {
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Order transfer information not found")
      );
    }

    if($order_detail_list_transfer_to_new_group) {
      $this->local_db->insert(local_table_name("b4b_orders"), $order_transfer_to_new);
      $insert_id = $this->local_db->insert_id();

      foreach ($order_detail_list_transfer_to_new_group as $key => $item) {
        $order_detail_list_transfer_to_new_group[$key]["orlist_order_id"] = $insert_id;
      }

      $this->local_db->insert_batch(local_table_name("b4b_order_details"), $order_detail_list_transfer_to_new_group);

      $to_new_order_code = "AVH" . str_pad($insert_id, 10, "0", STR_PAD_LEFT);
      $order_update = $this->local_db
        ->where("product_order_id", $insert_id)
        ->update(local_table_name("b4b_orders"), [
          "code" => $to_new_order_code,
      ]);
    }

    if($order_detail_list_transfer_to_same_group) {
      $this->local_db->insert(local_table_name("b4b_orders"), $order_transfer_to_same);
      $insert_id = $this->local_db->insert_id();

      foreach ($order_detail_list_transfer_to_same_group as $key => $item) {
        $order_detail_list_transfer_to_same_group[$key]["orlist_order_id"] = $insert_id;
      }

      $this->local_db->insert_batch(local_table_name("b4b_order_details"), $order_detail_list_transfer_to_same_group);

      $to_same_order_code = "AVH" . str_pad($insert_id, 10, "0", STR_PAD_LEFT);
      $order_update = $this->local_db
        ->where("product_order_id", $insert_id)
        ->update(local_table_name("b4b_orders"), [
          "code" => $to_same_order_code,
      ]);
    }

    $this->local_db
      ->where("product_order_id", (int)$order_id)
      ->update(local_table_name("b4b_orders"),[
        "status" => special_codes("b4b_orders.statuses.transfered"),
        "deleted_at" => $params["now"],
      ]);
    $this->local_db
      ->where("orlist_order_id", (int)$order_id)
      ->update(local_table_name("b4b_order_details"),[
        "status" => special_codes("b4b_orders.statuses.transfered"),
        "deleted_at" => $params["now"],
      ]);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Transfer operation created successfuly")
    );
  }

  public function editStatus($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $new_status = special_codes("b4b_orders.statuses.".$params["status"]);

    $check_order_details_sql = "SELECT
                                  b4b_order_details.`orlist_id`,
                                  b4b_order_details.`status`,
                                  b4b_orders.`status` as `order_status`
                                FROM `".local_table_name("b4b_order_details")."` b4b_order_details
                                LEFT JOIN `".local_table_name("b4b_orders")."` b4b_orders ON b4b_orders.`product_order_id` = b4b_order_details.`orlist_order_id`
                                  AND b4b_orders.`deleted_at` IS NULL
                                WHERE b4b_order_details.`deleted_at` IS NULL
                                AND MD5(b4b_order_details.`orlist_order_id`) = '{$params["id"]}'
                                AND b4b_order_details.`group_id` = {$params["group_id"]}
                                ";
    $check_order_details_query = $this->local_db->query($check_order_details_sql);
    if(!$check_order_details_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Order not found")
      );
    }
    $check_order_details = $check_order_details_query->result_array();

    $check_order_details_ids = [];
    $order_status = null;
    foreach ($check_order_details as $key => $item) {
      if($item["orlist_id"]) {
        $check_order_details_ids[] = $item["orlist_id"];
      }
      $order_status = $item["order_status"];
    }

    if($new_status === $order_status){
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Order status is the same with changed status")
      );
    }

    if($new_status === special_codes("b4b_orders.statuses.pending")){

      if($order_status === special_codes("b4b_orders.statuses.canceled")){
        return rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("This order is canceleded")
        );
      }

      // return rest_response(
      //   Status_codes::HTTP_BAD_REQUEST,
      //   lang("Status could not be changed to pending")
      // );
    }

    if($new_status === special_codes("b4b_orders.statuses.confirmed")){

      if($order_status === special_codes("b4b_orders.statuses.canceled")){
        return rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("This order is canceleded")
        );
      }

      if(!in_array($order_status,[special_codes("b4b_orders.statuses.pending")])) {
        return rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("Only with pending status order could be confirmed")
        );
      }
    }

    if($new_status === special_codes("b4b_orders.statuses.finished")){

      if($order_status === special_codes("b4b_orders.statuses.canceled")){
        return rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("This order is canceleded")
        );
      }

      if($order_status === special_codes("b4b_orders.statuses.finished")) {
        return rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("This order already finished")
        );
      }
    }

    if($new_status === special_codes("b4b_orders.statuses.canceled")){

      if($order_status === special_codes("b4b_orders.statuses.canceled")){
        return rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("This order is canceleded")
        );
      }

      if(in_array($order_status,[special_codes("b4b_orders.statuses.finished")])) {
        return rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("This finish status could not be canceled")
        );
      }
    }

    $this->local_db->where_in("orlist_id",$check_order_details_ids)->update(local_table_name("b4b_order_details"),[
      "status" => $new_status,
    ]);

    $this->local_db->query("UPDATE `".local_table_name("b4b_orders")."` b4b_orders
                              SET b4b_orders.`status` = '".$new_status."'
                              WHERE MD5(b4b_orders.`product_order_id`) = '{$params["id"]}'");

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Order status updated")
    );
  }

  public function statusConfirm($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $check_order_details_sql = "SELECT
                                  b4b_order_details.`orlist_id`,
                                  b4b_order_details.`status`,
                                  b4b_orders.`status` as `order_status`
                                FROM `".local_table_name("b4b_order_details")."` b4b_order_details
                                LEFT JOIN `".local_table_name("b4b_orders")."` b4b_orders ON b4b_orders.`product_order_id` = b4b_order_details.`orlist_order_id`
                                  AND b4b_orders.`deleted_at` IS NULL
                                WHERE b4b_order_details.`deleted_at` IS NULL
                                AND MD5(b4b_order_details.`orlist_order_id`) = '{$params["id"]}'
                                AND b4b_order_details.`group_id` = {$params["group_id"]}
                                ";
    $check_order_details_query = $this->local_db->query($check_order_details_sql);
    if(!$check_order_details_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Order not found")
      );
    }
    $check_order_details = $check_order_details_query->result_array();

    $check_order_details_ids = [];
    $order_status = null;
    foreach ($check_order_details as $key => $item) {
      if($item["orlist_id"]) {
        $check_order_details_ids[] = $item["orlist_id"];
      }
      $order_status = $item["order_status"];
    }

    if(!in_array($order_status,[special_codes("b4b_orders.statuses.pending")])) {
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Only with pending status order could be confirmed")
      );
    }

    $this->local_db->where_in("orlist_id",$check_order_details_ids)->update(local_table_name("b4b_order_details"),[
      "status" => special_codes("b4b_orders.statuses.confirmed"),
    ]);

    $this->local_db->query("UPDATE `".local_table_name("b4b_orders")."` b4b_orders
                              SET b4b_orders.`status` = '".special_codes("b4b_orders.statuses.confirmed")."'
                              WHERE MD5(b4b_orders.`product_order_id`) = '{$params["id"]}'");

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Order status confirmed")
    );
  }

  public function statusFinish($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $check_order_details_sql = "SELECT
                                  b4b_order_details.`orlist_id`,
                                  b4b_order_details.`status`,
                                  b4b_orders.`status` as `order_status`
                                FROM `".local_table_name("b4b_order_details")."` b4b_order_details
                                LEFT JOIN `".local_table_name("b4b_orders")."` b4b_orders ON b4b_orders.`product_order_id` = b4b_order_details.`orlist_order_id`
                                  AND b4b_orders.`deleted_at` IS NULL
                                WHERE b4b_order_details.`deleted_at` IS NULL
                                AND MD5(b4b_order_details.`orlist_order_id`) = '{$params["id"]}'
                                AND b4b_order_details.`group_id` = {$params["group_id"]}
                                ";
    $check_order_details_query = $this->local_db->query($check_order_details_sql);
    if(!$check_order_details_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Order not found")
      );
    }

    $check_order_details = $check_order_details_query->result_array();

    $check_order_details_ids = [];
    $order_status = null;
    foreach ($check_order_details as $key => $item) {
      if($item["orlist_id"]) {
        $check_order_details_ids[] = $item["orlist_id"];
      }
      $order_status = $item["order_status"];
    }

    if($order_status === special_codes("b4b_orders.statuses.canceled")){
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("This order is canceled for finish")
      );
    }

    if($order_status === special_codes("b4b_orders.statuses.finished")) {
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("This order already finished")
      );
    }

    $this->local_db->where_in("orlist_id",$check_order_details_ids)->update(local_table_name("b4b_order_details"),[
      "status" => special_codes("b4b_orders.statuses.finished"),
    ]);

    $this->local_db->query("UPDATE `".local_table_name("b4b_orders")."` b4b_orders
                              SET b4b_orders.`status` = '".special_codes("b4b_orders.statuses.finished")."'
                              WHERE MD5(b4b_orders.`product_order_id`) = '{$params["id"]}'");

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Order status finished")
    );
  }

  public function statusCancel($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $check_order_details_sql = "SELECT
                                  b4b_order_details.`orlist_id`,
                                  b4b_order_details.`status`,
                                  b4b_orders.`status` as `order_status`
                                FROM `".local_table_name("b4b_order_details")."` b4b_order_details
                                LEFT JOIN `".local_table_name("b4b_orders")."` b4b_orders ON b4b_orders.`product_order_id` = b4b_order_details.`orlist_order_id`
                                  AND b4b_orders.`deleted_at` IS NULL
                                WHERE b4b_order_details.`deleted_at` IS NULL
                                AND MD5(b4b_order_details.`orlist_order_id`) = '{$params["id"]}'
                                AND b4b_order_details.`group_id` = {$params["group_id"]}
                                ";
    $check_order_details_query = $this->local_db->query($check_order_details_sql);
    if(!$check_order_details_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Order not found")
      );
    }
    $check_order_details = $check_order_details_query->result_array();

    $check_order_details_ids = [];
    $order_status = null;
    foreach ($check_order_details as $key => $item) {
      if($item["orlist_id"]) {
        $check_order_details_ids[] = $item["orlist_id"];
      }
      $order_status = $item["order_status"];
    }

    if($order_status === special_codes("b4b_orders.statuses.canceled")){
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("This order is canceleded")
      );
    }

    if(in_array($order_status,[special_codes("b4b_orders.statuses.finished")])) {
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("This finish status could not be canceled")
      );
    }

    $this->local_db->where_in("orlist_id",$check_order_details_ids)->update(local_table_name("b4b_order_details"),[
      "status" => special_codes("b4b_orders.statuses.canceled"),
    ]);

    $this->local_db->query("UPDATE `".local_table_name("b4b_orders")."` b4b_orders
                              SET b4b_orders.`status` = '".special_codes("b4b_orders.statuses.canceled")."'
                              WHERE MD5(b4b_orders.`product_order_id`) = '{$params["id"]}'");

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Order status canceled")
    );
  }
}
