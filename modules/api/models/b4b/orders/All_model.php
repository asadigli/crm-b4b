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

    $page_load_limit = $this->config->item("orders_page_load_limit") ?: 200;

    $end_date_query = "";
    $start_date_query = "";

    if (isset($params["end_date"]) && $params["end_date"]) {
      $params["end_date"] = date('Y-m-d H:i:s', strtotime($params["end_date"] . ' +1 day'));
      $end_date_query = " AND b4b_orders.`product_order_ins_date` <= '{$params['end_date']}'";
    }

    if (isset($params["start_date"]) && $params["start_date"]) {
      $start_date_query = " AND b4b_orders.`product_order_ins_date` > '{$params['start_date']}'";
    }

    $offset_query = "";
    if ($params["offset"] && is_numeric($params["offset"])) {
      $offset_query = "OFFSET {$params["offset"]}";
    }

    $remote_customer_query = $params["remoteb4buserid"] ? " AND b4b_orders.`remote_customer_id` = {$params["remoteb4buserid"]}" : "";

    $body_query = "FROM `".local_table_name("b4b_orders")."` b4b_orders
                  WHERE b4b_orders.`product_order_delete` = '".STATUS_NO."'
                  AND b4b_orders.`deleted_at` IS NULl
                  AND b4b_orders.`product_order_approve` = '".STATUS_YES."'
                  AND b4b_orders.`product_order_company_id` = {$params["b4buserid"]}
                  $remote_customer_query
                  $start_date_query
                  $end_date_query";

    $b4b_orders_sql = "SELECT
                          b4b_orders.`product_order_id`,
                          MD5(b4b_orders.`product_order_id`) as `product_order_id_md5`,
                          b4b_orders.`product_order_delete`,
                          b4b_orders.`product_order_approve`,
                          b4b_orders.`product_order_company_id`,
                          b4b_orders.`product_order_user_id`,
                          b4b_orders.`product_order_status_id`,
                          b4b_orders.`product_order_store_status_id`,
                          b4b_orders.`product_order_depo_id`,
                          b4b_orders.`product_order_user_ip`,
                          b4b_orders.`product_order_amount`,
                          b4b_orders.`product_order_currency`,
                          b4b_orders.`product_order_product_count`,
                          b4b_orders.`product_order_comment`,
                          b4b_orders.`product_order_store_comment`,
                          b4b_orders.`product_order_ins_date`,
                          b4b_orders.`product_order_product_count` as `original_product_order_product_count`,
                          -- b4b_orders.`status`,
                          (SELECT
                            SUM(CASE WHEN `status` = '".special_codes("b4b_orders.statuses.finished")."'
                                  THEN 1 ELSE 0 END)
                            FROM `".local_table_name("b4b_order_details")."`
                            WHERE `orlist_order_id` = b4b_orders.`product_order_id`
                            AND `deleted_at` IS NULL
                          ) as `finished_status_count`,
                          b4b_orders.`code`,
                          b4b_orders.`status` as `order_status`,
                          (SELECT warehouses.`depo_name`
                            FROM `".local_table_name("warehouses")."` warehouses
                            WHERE warehouses.`depo_id` = b4b_orders.`product_order_depo_id`
                            LIMIT 1
                          ) as `depo_name`,
                          (SELECT
                              GROUP_CONCAT(CONCAT(`group_id`,'||',order_group.`is_remote`,'||',
                                (SELECT CONCAT(SUM(third_sub_b4b_order_details.`orlist_price` * third_sub_b4b_order_details.`orlist_quantity`),'||',COUNT(`orlist_id`))
                                  FROM `".local_table_name("b4b_order_details")."` third_sub_b4b_order_details
                                WHERE sub_b4b_order_details.`orlist_order_id` = third_sub_b4b_order_details.`orlist_order_id`
                                AND sub_b4b_order_details.`group_id` = third_sub_b4b_order_details.`group_id`)
                              ) SEPARATOR '-AVHSPRT-')
                          FROM `".local_table_name("b4b_order_details")."` sub_b4b_order_details
                          LEFT JOIN `".local_table_name("b4b_order_groups")."` order_group ON order_group.`id` = sub_b4b_order_details.`group_id`
                          WHERE sub_b4b_order_details.`deleted_at` IS NULL
                          AND sub_b4b_order_details.`orlist_order_id` = b4b_orders.`product_order_id`
                          GROUP BY sub_b4b_order_details.`orlist_order_id`) as `test`
                        $body_query
                        ORDER BY b4b_orders.`product_order_ins_date` DESC
                        -- ORDER BY b4b_orders.`product_order_id`
                        LIMIT $page_load_limit
                        $offset_query
                        ";
                        // var_dump($b4b_orders_sql);die;
    $b4b_orders_query = $this->local_db->query($b4b_orders_sql);
    if(!$b4b_orders_query->row_array()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No information")
      );
    }

    $b4b_orders = $b4b_orders_query->result_array();

    $count_query = $this->local_db->query("SELECT COUNT(1) as count $body_query");
    $count = $count_query->row_array();

    $b4b_orders_list = [];
    foreach ($b4b_orders as $key => $item) {
      // $status = (int)$item["finished_status_count"] === (int)$item["original_product_order_product_count"] ? STATUS_FINISHED : STATUS_PENDING;

      $group_ids = explode("-AVHSPRT-",$item["test"]);
      $group_ids = array_unique(array_filter($group_ids));

      $b4b_orders_list[] = [
        "id" => $item["product_order_id_md5"],
        "code" => $item["code"],
        "depo_name" => $item["depo_name"],
        // "old_status" => $item["order_status"],
        "currency" => $item["product_order_currency"],
        "product_count" 	=> $item["product_order_product_count"],
        "amount" => $item["product_order_amount"],
        "opeartion_date" => $item["product_order_ins_date"],
        // "group_id" => $group_id,
        // "is_remote" => $is_remote,
        // "status" => $status,
        "status" =>array_search($item["order_status"],special_codes("b4b_orders.statuses")),
      ];
      //
      // foreach ($group_ids as $sub_key => $sub_item) {
      //   // $group_info = explode("||",$sub_item);
      //
      //   // $group_id = isset($group_info[3]) ? $group_info[0] : NULL;
      //   // $is_remote = isset($group_info[3]) ? $group_info[1] === STATUS_ACTIVE : false;
      //   // $order_amount = isset($group_info[3]) ? $group_info[2] : NULL;
      //   // $order_product_count = isset($group_info[3]) ? $group_info[3] : NULL;
      //
      //   // $status = (int)$item["finished_status_count"] === (int)$order_product_count ? STATUS_FINISHED : STATUS_PENDING;
      //
      //
      // }

    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => $count["count"],
        "list" => $b4b_orders_list
      ]
    );
  }

  public function details($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $remote_customer_query = $params["remoteb4buserid"] ? " AND b4b_orders.`remote_customer_id` = {$params["remoteb4buserid"]}" : "";

    $order_detail_sql = "SELECT
                          b4b_order_details.`orlist_quantity` as `quantity`,
                          b4b_order_details.`orlist_price` as `price`,
                          b4b_order_details.`orlist_currency` as `currency`,
                          b4b_order_details.`orlist_from`,
                          b4b_order_details.`orlist_name` as `name`,
                          b4b_order_details.`orlist_brend` as `brand_name`,
                          b4b_order_details.`orlist_brend_code` as `brand_code`,
                          b4b_order_details.`orlist_brend_org_code` as `brand_org_code`,
                          b4b_order_details.`orlist_description` as `description`,
                          b4b_order_details.`orlist_ins_date` as `opeartion_date`,

                          b4b_orders.`product_order_id`,
                          b4b_orders.`product_order_amount`,
                          b4b_orders.`product_order_currency`,
                          b4b_orders.`product_order_product_count`,
                          b4b_orders.`product_order_comment`,
                          b4b_orders.`product_order_store_comment`,
                          b4b_orders.`product_order_ins_date`,
                          b4b_orders.`status` as `order_status`,
                          b4b_orders.`code` as `order_code`,
                          (SELECT
                            SUM(CASE WHEN `status` = '".special_codes("b4b_orders.statuses.finished")."'
                                  THEN 1 ELSE 0 END)
                            FROM `".local_table_name("b4b_order_details")."`
                            WHERE `orlist_order_id` = b4b_orders.`product_order_id`
                            AND `deleted_at` IS NULL
                          ) as `finished_status_count`,

                          -- b4b_order_statuses.`order_status`,
                          -- b4b_order_statuses.`order_status_id`,

                          warehouses.`depo_name`

                          FROM `".local_table_name("b4b_order_details")."` b4b_order_details
                          LEFT JOIN `".local_table_name("b4b_orders")."` b4b_orders ON b4b_orders.`product_order_id` = b4b_order_details.`orlist_order_id`
                            AND b4b_orders.`product_order_delete` = '".STATUS_NO."'
                          LEFT JOIN `".local_table_name("b4b_order_statuses")."` b4b_order_statuses ON b4b_order_statuses.`order_status_id` = b4b_orders.`product_order_status_id`
                          LEFT JOIN `".local_table_name("warehouses")."` warehouses ON warehouses.`depo_id` = b4b_orders.`product_order_depo_id`
                          WHERE b4b_order_details.`orlist_delete` = '".STATUS_NO."'
                          AND b4b_orders.`product_order_approve` = '".STATUS_YES."'
                          AND b4b_orders.`product_order_company_id` = {$params["b4buserid"]}
                          $remote_customer_query
                          AND MD5(b4b_orders.`product_order_id`) = '{$params["id"]}'
                          ORDER BY b4b_order_details.`orlist_id`
                          ";
    $order_detail_query = $this->local_db->query($order_detail_sql);
    if(!$order_detail_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Order information not found")
      );
    }

    $order_details = $order_detail_query->result_array();

    $client = [];
    $invoice_details = [];
    $invoice_list = [];

    $total_sale_price = 0;
    $product_count = 0;
    foreach ($order_details as $key => $item) {
      $product_count++;
      $total_sale_price += $item["quantity"] * $item["price"];

      $delivery_time = AVA_PRODUCT_DELIVERY_TIME;
      if($item["orlist_from"] === PRODUCT_RESOURCE_RONAX) {
        $delivery_time = RONAX_PRODUCT_DELIVERY_TIME;
      }
      if($item["orlist_from"] === PRODUCT_RESOURCE_STORE) {
        $delivery_time = STORE_PRODUCT_DELIVERY_TIME;
      }

      $client = [

      ];
      $invoice_details = [
        "code" => $item["order_code"],
        "depo_name" => $item["depo_name"],
        "status" => $item["order_status"],
        "currency" => $item["product_order_currency"],
        // "product_count" 	=> $item["product_order_product_count"],
        // "amount" => $item["product_order_amount"],
        "operation_date" => $item["product_order_ins_date"],
        "comment" => $item["product_order_comment"],
        "store_comment" => $item["product_order_store_comment"],
        "status" => array_search($item["order_status"],special_codes("b4b_orders.statuses")),
      ];

      $invoice_list[] = [
        "quantity" => $item["quantity"],
        "price" => $item["price"],
        "currency" => $item["currency"],
        "name" => $item["name"],
        "delivery_time" => $delivery_time,
        "brand" => [
          "name" => $item["brand_name"],
          "code" => $item["brand_code"],
          "org_code" => $item["brand_org_code"],
        ],
        "description" => $item["description"],
        "opeartion_date" => $item["opeartion_date"],
        "total_price" => $item["quantity"] * $item["price"],
      ];
    }
    $invoice_details["product_count"] = $product_count;
    // $status = (int)$item["finished_status_count"] === (int)$product_count ? STATUS_FINISHED : STATUS_PENDING;
    // $invoice_details["status"] = $status;
    $invoice_details["amount"] = $total_sale_price;

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
