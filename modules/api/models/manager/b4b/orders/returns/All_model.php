<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct() {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  public function index($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $end_date_query = "";
    $start_date_query = "";

    if (isset($params["end_date"]) && $params["end_date"]) {
      $params["end_date"] = date('Y-m-d H:i:s', strtotime($params["end_date"] . ' +1 day'));
      $end_date_query = " AND b4b_return_orders.`product_reverce_ins_date` <= '{$params['end_date']}'";
    }

    if (isset($params["start_date"]) && $params["start_date"]) {
      $start_date_query = " AND b4b_return_orders.`product_reverce_ins_date` > '{$params['start_date']}'";
    }

    $b4b_return_orders_sql = "SELECT
                          b4b_return_orders.`product_reverce_id`,
                          MD5(b4b_return_orders.`product_reverce_id`) as `product_reverce_id_md5`,
                          b4b_return_orders.`product_reverce_delete`,
                          b4b_return_orders.`product_reverce_approve`,
                          b4b_return_orders.`product_reverce_company_id`,
                          b4b_return_orders.`product_reverce_user_id`,
                          b4b_return_orders.`product_reverce_status_id`,
                          b4b_return_orders.`product_reverce_depo_id`,
                          b4b_return_orders.`product_reverce_user_ip`,
                          b4b_return_orders.`product_reverce_amount`,
                          b4b_return_orders.`product_reverce_currency`,
                          b4b_return_orders.`product_reverce_product_count`,
                          b4b_return_orders.`product_reverce_comment`,
                          b4b_return_orders.`product_reverce_ins_date`,

                          b4b_order_statuses.`order_status`,
                          b4b_order_statuses.`order_status_id`,
                          warehouses.`depo_name`
                        FROM `".local_table_name("b4b_return_orders")."` b4b_return_orders
                        LEFT JOIN `".local_table_name("b4b_order_statuses")."` b4b_order_statuses ON b4b_order_statuses.`order_status_id` = b4b_return_orders.`product_reverce_status_id`
                        LEFT JOIN `".local_table_name("warehouses")."` warehouses ON warehouses.`depo_id` = b4b_return_orders.`product_reverce_depo_id`
                        WHERE b4b_return_orders.`product_reverce_delete` = '".STATUS_NO."'
                        AND b4b_return_orders.`product_reverce_approve` = '".STATUS_YES."'
                        AND b4b_return_orders.`product_reverce_company_id` = {$params["userid"]}
                        $start_date_query
                        $end_date_query
                        ORDER BY b4b_return_orders.`product_reverce_ins_date`
                        ";
                        // var_dump($b4b_return_orders_sql);die;
    $b4b_return_orders_query = $this->local_db->query($b4b_return_orders_sql);
    if(!$b4b_return_orders_query->row_array()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No information")
      );
    }

    $b4b_return_orders = $b4b_return_orders_query->result_array();

    $b4b_return_orders_list = [];
    foreach ($b4b_return_orders as $key => $item) {
      $info_order_no = 'R'.str_pad($item['product_reverce_id'], 6, '0', STR_PAD_LEFT);

      $b4b_return_orders_list[] = [
        "id" => $item["product_reverce_id_md5"],
				"code" => $info_order_no,
				"depo_name" => $item["depo_name"],
				"old_status" => $item["order_status"],
				"currency" => $item["product_reverce_currency"],
				"product_count" 	=> $item["product_reverce_product_count"],
				"amount" => $item["product_reverce_amount"],
				"opeartion_date" => $item["product_reverce_ins_date"],
        "status" => special_codes("b4b_return_orders.statuses.".($item["order_status_id"] ?: "0")),
      ];
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => count($b4b_return_orders_list),
        "list" => $b4b_return_orders_list
      ]
    );
  }

  public function details($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $order_return_detail_sql = "SELECT
                          b4b_return_order_details.`reverse_invoice` as `reverse_invoice`,
                          b4b_return_order_details.`reverse_code` as `reverse_code`,
                          b4b_return_order_details.`reverse_specode3` as `reverse_specode3`,
                          b4b_return_order_details.`reverse_specode2` as `reverse_specode2`,
                          b4b_return_order_details.`reverse_specode` as `reverse_specode`,
                          b4b_return_order_details.`reverse_quantity` as `quantity`,
                          b4b_return_order_details.`reverse_name` as `name`,
                          b4b_return_order_details.`reverse_price` as `price`,
                          b4b_return_order_details.`reverse_description` as `description`,
                          b4b_return_order_details.`reverse_ins_date` as `opeartion_date`,

                          b4b_return_orders.`product_reverce_id`,
                          b4b_return_orders.`product_reverce_amount` ,
                          b4b_return_orders.`product_reverce_product_count`,
                          b4b_return_orders.`product_reverce_comment`,
                          b4b_return_orders.`product_reverce_ins_date`,
                          b4b_return_orders.`product_reverce_currency`,

                          b4b_order_statuses.`order_status`,
                          b4b_order_statuses.`order_status_id`,
                          warehouses.`depo_name`

                          FROM `".local_table_name("b4b_return_order_details")."` b4b_return_order_details
                          LEFT JOIN `".local_table_name("b4b_return_orders")."` b4b_return_orders ON b4b_return_orders.`product_reverce_id` = b4b_return_order_details.`product_reverce_id`
                            AND b4b_return_orders.`product_reverce_delete` = '".STATUS_NO."'
                          LEFT JOIN `".local_table_name("b4b_order_statuses")."` b4b_order_statuses ON b4b_order_statuses.`order_status_id` = b4b_return_orders.`product_reverce_status_id`
                          LEFT JOIN `".local_table_name("warehouses")."` warehouses ON warehouses.`depo_id` = b4b_return_orders.`product_reverce_depo_id`
                          WHERE b4b_return_order_details.`reverse_delete` = '".STATUS_NO."'
                          AND b4b_return_orders.`product_reverce_approve` = '".STATUS_YES."'
                          AND b4b_return_orders.`product_reverce_company_id` = {$params["userid"]}
                          AND MD5(b4b_return_orders.`product_reverce_id`) = '{$params["id"]}'
                          ORDER BY b4b_return_order_details.`reverse_id`
                          ";

    $order_return_detail_query = $this->local_db->query($order_return_detail_sql);
    if(!$order_return_detail_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Order information not found")
      );
    }

    $order_return_details = $order_return_detail_query->result_array();

    $client = [];
    $invoice_details = [];
    $invoice_list = [];

    foreach ($order_return_details as $key => $item) {
      $info_order_no = 'R'.str_pad($item['product_reverce_id'], 6, '0', STR_PAD_LEFT);

      $client = [

      ];

      $invoice_details = [
        "code" => $info_order_no,
        "depo_name" => $item["depo_name"],
        "old_status" => $item["order_status"],
        "product_count" 	=> $item["product_reverce_product_count"],
        "currency" => $item["product_reverce_currency"],
        "amount" => $item["product_reverce_amount"],
        "operation_date" => $item["product_reverce_ins_date"],
        "comment" => $item["product_reverce_comment"],
        "reverse_invoice" => $item["reverse_invoice"],
        "status" => special_codes("b4b_return_orders.statuses.".($item["order_status_id"] ?: "0")),
      ];

      $invoice_list[] = [
        "quantity" => $item["quantity"],
        "price" => $item["price"],
        "name" => $item["name"],
        "reverse_code" => $item["reverse_code"],
        "reverse_specode" => $item["reverse_specode"],
        "reverse_specode2" => $item["reverse_specode2"],
        "reverse_specode3" => $item["reverse_specode3"],
        "description" => $item["description"],
        "opeartion_date" => $item["opeartion_date"],
        "total_price" => $item["quantity"] * $item["price"],
      ];
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "client" => $client,
        "invoice" => array_merge($invoice_details,[
          "list" => $invoice_list
        ]),
      ]
    );
  }
}
