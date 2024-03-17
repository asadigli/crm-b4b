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

    $page_load_limit = $this->config->item("manager_orders_page_load_limit") ?: 200;

    $end_date_query = "";
    $start_date_query = "";
    $sub_end_date_query = "";
    $sub_start_date_query = "";
    $restricted_orders_query = "";

    if(!in_array($params["auth_user"]["role"],[ROLE_DEVELOPER,ROLE_MAIN_ADMIN])) {
      $restricted_orders_query = " AND b4b_orders.`product_order_comment` != '".RESTRICTED_ORDER_CODE."' ";
    }

    $folder_id_query = "";
    $folder_sub_id_query = "";
    $folder_name = "";
    if($params["folder_id"]){
      $folder_orders_sql = "SELECT
                        order_folder_joints.`order_id`,
                        b4b_order_folders.`name`
                      FROM `".local_table_name("b4b_order_folder_joints")."` order_folder_joints
                      LEFT JOIN `".local_table_name("b4b_order_folders")."` b4b_order_folders ON b4b_order_folders.`id` = order_folder_joints.`folder_id`
                        AND b4b_order_folders.`deleted_at` IS NULL
                      WHERE order_folder_joints.`deleted_at` IS NULL
                      AND b4b_order_folders.`system_user_id` = {$params["userid"]}
                      AND order_folder_joints.`folder_id` = {$params["folder_id"]}
                    ";
      $folder_orders_query = $this->local_db->query($folder_orders_sql);
      $folder_orders = $folder_orders_query->result_array();

      $folder_orders_list = [];
      foreach ($folder_orders as $key => $item) {
        $folder_orders_list[] = $item["order_id"];
        $folder_name = $item["name"];
      }
      if($folder_orders_list){
        $folder_id_query = " AND b4b_orders.`product_order_id` IN (".implode(",", $folder_orders_list).") ";
        $folder_sub_id_query = " AND sub_b4b_orders.`product_order_id` IN (".implode(",", $folder_orders_list).") ";
      }
    }


    if (isset($params["end_date"]) && $params["end_date"]) {
      $params["end_date"] = date('Y-m-d H:i:s', strtotime($params["end_date"] . ' +1 day'));
      $end_date_query = " AND b4b_orders.`product_order_ins_date` <= '{$params['end_date']}'";
      $sub_end_date_query = " AND sub_b4b_orders.`product_order_ins_date` <= '{$params['end_date']}'";
    }

    if (isset($params["start_date"]) && $params["start_date"]) {
      $start_date_query = " AND b4b_orders.`product_order_ins_date` > '{$params['start_date']}'";
      $sub_start_date_query = " AND sub_b4b_orders.`product_order_ins_date` > '{$params['start_date']}'";
    }


    if($params["no_date_filter"]) {
      $start_date_query = "";
      $end_date_query = "";
      $sub_end_date_query = "";
      $sub_start_date_query = "";
    }

    $offset_query = "";
    if ($params["offset"] && is_numeric($params["offset"])) {
      $offset_query = "OFFSET {$params["offset"]}";
    }

    $status_query = "";
    if($params["status"]){
      if($params["status"] === STATUS_NEW_ORDERS) {
        $status_query = " AND (b4b_orders.`status` = '".special_codes("b4b_orders.statuses.pending")."'
                                OR b4b_orders.`status` = '".special_codes("b4b_orders.statuses.confirmed")."')";
      } else {
        $status = special_codes("b4b_orders.statuses.".$params["status"]);
        $status_query = $params["status"] ? " AND b4b_orders.`status` = '$status'" : "";
      }
    }

    $group_id_query = $params["group_id"] ? " AND b4b_orders.`group_id` = {$params["group_id"]}" : "";

    $keyword_query = $params["keyword"] ? " AND (b4b_orders.`code` LIKE '%{$params["keyword"]}%'
                                                  OR customer.`name` LIKE '%{$params["keyword"]}%')" : "";

    $order_id_list = [];
    if($params["keyword"]) {
      $cleaned_keyword = cleaned_text($params["keyword"], true);

      $order_ids_query = $this->local_db->query("SELECT
                                                    b4b_orders.`product_order_id` as `id`
                                                  FROM `".local_table_name("b4b_order_details")."` b4b_order_details
                                                  LEFT JOIN `".local_table_name("b4b_orders")."` b4b_orders ON b4b_orders.`product_order_id` = b4b_order_details.`orlist_order_id`
                                                    AND b4b_orders.`deleted_at` IS NULL
                                                  LEFT JOIN `".local_table_name("b4b_companies")."` entries ON entries.`company_id` = b4b_orders.`product_order_company_id`
                                                  LEFT JOIN `".local_table_name("cached_customers")."` customer ON customer.`remote_id` = b4b_orders.`remote_customer_id`
                                                  WHERE b4b_order_details.`deleted_at` IS NULL
                                                  AND ( b4b_order_details.`orlist_brend_code` LIKE '%{$params["keyword"]}%'
                                                        OR b4b_order_details.`orlist_brend` LIKE '%{$params["keyword"]}%'
                                                        OR b4b_order_details.`orlist_brend_org_code` LIKE '%{$cleaned_keyword}%'
                                                        OR b4b_orders.`code` LIKE '{$params["keyword"]}'
                                                        OR customer.`name` LIKE '%{$params["keyword"]}%')
                                                  $group_id_query
                                                  $status_query
                                                  $start_date_query
                                                  $end_date_query
                                                  ");
      $order_ids = $order_ids_query->result_array();
      $order_id_list = array_map(function($i){ return $i["id"]; }, $order_ids);
      $order_id_list = array_values(array_filter(array_unique($order_id_list)));
    }

    $keyword_query = $order_id_list ? " AND b4b_orders.`product_order_id` IN (".implode(",", $order_id_list).")" : $keyword_query;


    $body_filters = "
                    $group_id_query
                    $keyword_query
                    $status_query
                    $start_date_query
                    $end_date_query
                    ";
    $body_filters = $folder_id_query ?: $body_filters;

    $body_query = "FROM `".local_table_name("b4b_orders")."` b4b_orders
                  LEFT JOIN `".local_table_name("b4b_companies")."` entries ON entries.`company_id` = b4b_orders.`product_order_company_id`
                  LEFT JOIN `".local_table_name("cached_customers")."` customer ON customer.`remote_id` = b4b_orders.`remote_customer_id`
                  LEFT JOIN `".local_table_name("warehouses")."` warehouses ON warehouses.`depo_id` = b4b_orders.`product_order_depo_id`
                  LEFT JOIN `".local_table_name("b4b_orders")."` parent_b4b_orders ON parent_b4b_orders.`product_order_id` = b4b_orders.`parent_id`
                    AND b4b_orders.`parent_id` IS NOT NULL
                    AND b4b_orders.`parent_id` != 0
                  WHERE b4b_orders.`deleted_at` IS NULL
                  AND b4b_orders.`product_order_delete` = '".STATUS_NO."'
                  AND b4b_orders.`product_order_approve` = '".STATUS_YES."'
                  $restricted_orders_query
                  $body_filters
                  ";

    $b4b_orders_sql = "SELECT
                          b4b_orders.`product_order_id`,
                          MD5(b4b_orders.`product_order_id`) as `product_order_id_md5`,
                          b4b_orders.`product_order_delete`,
                          b4b_orders.`product_order_approve`,
                          b4b_orders.`product_order_company_id`,
                          b4b_orders.`product_order_user_id`,
                          b4b_orders.`code`,
                          b4b_orders.`from_transfer_group_name`,
                          b4b_orders.`from_transfer`,
                          b4b_orders.`remote_customer_id`,
                          parent_b4b_orders.`code` as `parent_order_code`,
                          b4b_orders.`status` as `order_status`,
                          (SELECT SUM(IFNULL(`product_order_amount`,0))
                            FROM `".local_table_name("b4b_orders")."` sub_b4b_orders
                            WHERE sub_b4b_orders.`deleted_at` IS NULL
                            -- AND sub_b4b_orders.`group_id` = {$params["group_id"]}
                            ".
                            ($folder_sub_id_query ?: $sub_start_date_query . " " .$sub_end_date_query)
                            ."
                          ) as `total_order_amount`,
                          b4b_orders.`product_order_store_status_id`,
                          b4b_orders.`product_order_depo_id`,
                          b4b_orders.`product_order_user_ip`,
                          -- b4b_orders.`product_order_amount`,
                          (SELECT
                            SUM(IFNULL(`orlist_quantity`,0) * IFNULL(`orlist_price`,0))
                            FROM `".local_table_name("b4b_order_details")."`
                            WHERE `orlist_order_id` = b4b_orders.`product_order_id`
                            -- AND `group_id` = {$params["group_id"]}
                            AND `deleted_at` IS NULL
                          ) as `product_order_amount`,
                          (SELECT
                            COUNT(`orlist_id`)
                            FROM `".local_table_name("b4b_order_details")."`
                            WHERE `orlist_order_id` = b4b_orders.`product_order_id`
                            -- AND `group_id` = {$params["group_id"]}
                            AND `deleted_at` IS NULL
                          ) as `product_order_product_count`,
                          b4b_orders.`product_order_currency`,
                          b4b_orders.`product_order_product_count` as `original_product_order_product_count`,
                          b4b_orders.`product_order_store_comment`,
                          b4b_orders.`product_order_ins_date`,
                          b4b_orders.`product_order_comment` as `entry_comment`,
                          customer.`name` as `entry_name`,
                          entries.`company_phone` as `entry_phone`,
                          entries.`company_email` as `entry_email`
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
    $query_count = $b4b_orders_query->num_rows();
    if($page_load_limit <= $query_count) {
      $query_counts_query = $this->local_db->query("SELECT COUNT(1) as count $body_query");
      $query_counts = $query_counts_query->row_array();
      $query_count = (int)$query_counts["count"];
    }

    $b4b_orders = $b4b_orders_query->result_array();
    // return $b4b_orders;
    $b4b_orders_list = [];
    $order_statuses  = special_codes("b4b_orders.statuses");

    $finished_order_count = 0;
    $confirmed_order_count = 0;
    $order_status = null;
    $order_total_amount = 0;
    foreach ($b4b_orders as $key => $item) {
      // $status = (int)$item["finished_status_count"] === (int)$item["product_order_product_count"] ? STATUS_FINISHED : STATUS_PENDING;

      // $finished_order_count = $item["finished_status_count"];

      if($item["code"]) {

        if($item["order_status"] === special_codes("b4b_orders.statuses.confirmed")) {
          $confirmed_order_count++;
        }
        $order_total_amount = $item["total_order_amount"];

        $order_status = array_search($item["order_status"],special_codes("b4b_orders.statuses"));
        $b4b_orders_list[$item["code"]] = [
          "id" => $item["product_order_id_md5"],
          "code" => $item["code"],
          "parent_order_code" => $item["parent_order_code"],
          "remote_customer_id" => $item["remote_customer_id"],
          "from_transfer" => $item["from_transfer"] === STATUS_ACTIVE,
          "from_transfer_group_name" => $item["from_transfer_group_name"],
          // "status" => $status,
          // "depo_name" => $item["depo_name"],
          "status" => $order_status,
          "currency" => $item["product_order_currency"],
          "product_count" 	=> $item["product_order_product_count"],
          "amount" => $item["product_order_amount"],
          "operation_date" => $item["product_order_ins_date"],
          // "status" => array_search($item["order_status"],$order_statuses),
          "entry" => [
            "name" => $item["entry_name"],
            "phone" => $item["entry_phone"],
            "comment" => $item["entry_comment"],
          ]
        ];
      }
    }
    $b4b_orders_list = array_values($b4b_orders_list);

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => $page_load_limit === $b4b_orders_query->num_rows() ? $query_count : count($b4b_orders_list),
        "confirmed_order_count" => $confirmed_order_count,
        "totals" => [
          "order_amount" => $order_total_amount,
        ],
        "folder_name" => $folder_name,
        // "finished_order_count" => $finished_order_count,
        "list" => $b4b_orders_list
      ]
    );
  }

  public function details($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $group_id_query = $params["group_id"] ? " AND b4b_order_details.`group_id` = {$params["group_id"]}" : "";

    $folder_id_query = "";
    // $folder_sub_id_query = "";
    $folder_name = "";
    if($params["folder_id"]){
      $folder_orders_sql = "SELECT
                        order_folder_joints.`order_id`,
                        b4b_order_folders.`name`
                      FROM `".local_table_name("b4b_order_folder_joints")."` order_folder_joints
                      LEFT JOIN `".local_table_name("b4b_order_folders")."` b4b_order_folders ON b4b_order_folders.`id` = order_folder_joints.`folder_id`
                        AND b4b_order_folders.`deleted_at` IS NULL
                      WHERE order_folder_joints.`deleted_at` IS NULL
                      AND b4b_order_folders.`system_user_id` = {$params["userid"]}
                      AND order_folder_joints.`folder_id` = {$params["folder_id"]}
                    ";
      $folder_orders_query = $this->local_db->query($folder_orders_sql);
      $folder_orders = $folder_orders_query->result_array();
      $folder_orders_list = array_map(function($i){ return $i["order_id"];},$folder_orders);
      if($folder_orders_list){
        $folder_id_query = " AND b4b_orders.`product_order_id` IN (".implode(",", $folder_orders_list).") ";
      }
      // $folder_sub_id_query = " AND sub_b4b_orders.`product_order_id` IN (".implode(",", $folder_orders_list).") ";
    }

    $group_id_query =  $folder_id_query ?: $group_id_query;

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
                          b4b_order_details.`orlist_ins_date` as `operation_date`,
                          b4b_order_details.`orlist_id` as `id`,

                          b4b_order_details.`has_discount`,

                          CASE
                            WHEN (b4b_order_details.`orlist_from` = '".PRODUCT_RESOURCE_AVA."') THEN products.`stock_baku`
                            WHEN (b4b_order_details.`orlist_from` = '".PRODUCT_RESOURCE_STORE."') THEN products.`stock_baku`
                            ELSE null
                          END AS `stock_baku`,

                          CASE
                            WHEN (b4b_order_details.`orlist_from` = '".PRODUCT_RESOURCE_AVA."') THEN products.`stock_ganja`
                            WHEN (b4b_order_details.`orlist_from` = '".PRODUCT_RESOURCE_STORE."') THEN products.`stock_ganja`
                            ELSE null
                          END AS `stock_ganja`,

                          CASE
                            WHEN (b4b_order_details.`orlist_from` = '".PRODUCT_RESOURCE_AVA."') THEN products.`stock_baku_2`
                            WHEN (b4b_order_details.`orlist_from` = '".PRODUCT_RESOURCE_STORE."') THEN products.`stock_baku_2`
                            ELSE null
                          END AS `stock_baku_2`,

                          CASE
                            WHEN (b4b_order_details.`orlist_from` = '".PRODUCT_RESOURCE_AVA."') THEN products.`model`
                            WHEN (b4b_order_details.`orlist_from` = '".PRODUCT_RESOURCE_STORE."') THEN products.`model`
                            ELSE null
                          END AS `model`,

                          CASE
                            WHEN (b4b_order_details.`orlist_from` = '".PRODUCT_RESOURCE_AVA."') THEN products.`cleaned_brand_code`
                            WHEN (b4b_order_details.`orlist_from` = '".PRODUCT_RESOURCE_STORE."') THEN products.`cleaned_brand_code`
                            ELSE null
                          END AS `cleaned_brand_code`,

                          b4b_orders.`product_order_id`,
                          MD5(b4b_orders.`product_order_id`) as `md5_product_order_id`,
                          b4b_orders.`product_order_amount`,
                          b4b_orders.`product_order_currency`,
                          b4b_orders.`product_order_product_count`,
                          b4b_orders.`product_order_store_comment`,
                          b4b_orders.`product_order_ins_date`,
                          b4b_orders.`product_order_comment` as `entry_comment`,
                          b4b_orders.`status` as `order_status`,
                          b4b_orders.`code` as `order_code`,
                          b4b_orders.`remote_customer_id`,
                          -- b4b_order_statuses.`order_status`,
                          -- b4b_order_statuses.`order_status_id`,
                          warehouses.`depo_name`,
                          customer.`name` as `entry_name`,
                          entries.`company_id` as `entry_id`,
                          entries.`company_phone` as `entry_phone`,
                          entries.`company_email` as `entry_email`
                          FROM `".local_table_name("b4b_order_details")."` b4b_order_details
                          LEFT JOIN `".local_table_name("b4b_orders")."` b4b_orders ON b4b_orders.`product_order_id` = b4b_order_details.`orlist_order_id`
                            AND b4b_orders.`product_order_delete` = '".STATUS_NO."'
                          LEFT JOIN `".local_table_name("b4b_companies")."` entries ON entries.`company_id` = b4b_orders.`product_order_company_id`
                          LEFT JOIN `".local_table_name("cached_customers")."` customer ON customer.`remote_id` = b4b_orders.`remote_customer_id`
                          LEFT JOIN `".local_table_name("warehouses")."` warehouses ON warehouses.`depo_id` = b4b_orders.`product_order_depo_id`
                          LEFT JOIN `".local_table_name("b4b_configs")."` configs ON configs.`key` = b4b_order_details.`orlist_from`
                            AND configs.`group` = '".special_codes("configs.groups.product_resource")."'
                          LEFT JOIN `".local_table_name("cached_products")."` products ON b4b_order_details.`orlist_brend_code` = products.`brand_code`
                                                                                      AND b4b_order_details.`orlist_brend` = products.`brand_name`
                                                                                      AND configs.`value` = products.`resource`
                          WHERE b4b_order_details.`orlist_delete` = '".STATUS_NO."'
                          AND b4b_orders.`product_order_approve` = '".STATUS_YES."'
                          AND b4b_orders.`deleted_at` IS NULL
                          AND MD5(b4b_orders.`product_order_id`) = '{$params["id"]}'
                          $group_id_query
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
    $entry_id = null;
    $order_id = null;
    $products_brand_codes = [];
    $total_sale_price = 0;

    $remote_customer_id = null;
    $order_status = null;
    foreach ($order_details as $key => $item) {
      $entry_id = $item["entry_id"];
      $order_id = $item["product_order_id"];
      $products_brand_codes[] = cleaned_text($item["brand_code"], true);

      $remote_customer_id = $item["remote_customer_id"];
      $order_status = array_search($item["order_status"], special_codes("b4b_orders.statuses"));
      $client = [
        "id" => $item["entry_id"],
        "remote_customer_id" => $item["remote_customer_id"],
        "name" => $item["entry_name"],
        "phone" => $item["entry_phone"],
        "email" => $item["entry_email"],
        "comment" => $item["entry_comment"],
        "account" => null,
        "last_payments" => null,
      ];
      $invoice_details = [
        "id" => $item["md5_product_order_id"],
        "total_sale_price_currency" => $item["product_order_currency"],
        "depo_name" => $item["depo_name"],
        "status" => $order_status,
        "code" => $item["order_code"],
        "currency" => $item["product_order_currency"],
        "product_count" 	=> $item["product_order_product_count"],
        "amount" => $item["product_order_amount"],
        "operation_date" => $item["product_order_ins_date"],
        "comment" => $item["entry_comment"],
        "store_comment" => $item["product_order_store_comment"],
      ];

      $delivery_time = null;

      if($item["orlist_from"] === PRODUCT_RESOURCE_AVA) {
        $delivery_time = AVA_PRODUCT_DELIVERY_TIME;
      } else if($item["orlist_from"] === PRODUCT_RESOURCE_RONAX) {
        $delivery_time = RONAX_PRODUCT_DELIVERY_TIME;
      } else if($item["orlist_from"] === PRODUCT_RESOURCE_STORE) {
        $delivery_time = STORE_PRODUCT_DELIVERY_TIME;
      }

      $total_sale_price += $item["quantity"] * $item["price"];

      $invoice_list[] = [
        "id" => $item["id"],
        "brand" => [
          "name" => $item["brand_name"],
          "code" => $item["brand_code"],
          "org_code" => $item["brand_org_code"],
        ],
        "delivery_time" => $delivery_time,
        "quantity" => $item["quantity"],
        "has_discount" => $item["has_discount"] === STATUS_ACTIVE,
        "price" => $item["price"],
        "currency" => $item["currency"],
        "name" => $item["name"],
        "description" => $item["description"],
        "operation_date" => $item["operation_date"],
        "total_price" => $item["quantity"] * $item["price"],
        "product" => [
          "stock_baku" => $item["stock_baku"],
          "stock_baku_2" => $item["stock_baku_2"],
          "stock_ganja" => $item["stock_ganja"],
          "model" => $item["model"],
          "cleaned_brand_code" => $item["cleaned_brand_code"],
        ],
      ];
    }
    $invoice_details["total_sale_price"] = $total_sale_price;

    if($remote_customer_id) {
      $company_customer_account_info_sql = "SELECT
                                              customer_accounts.`exit_amount` as `last_payment_amount`,
                                              customer_accounts.`remote_created_at` as `last_payment_date`,
                                              (SELECT
                                                (SUM(IFNULL(`entry_amount`,0)) -
                                                SUM(IFNULL(`exit_amount`,0)))
                                                FROM `".local_table_name("cached_customer_accounts")."`
                                                WHERE `deleted_at` IS NULL
                                                AND `company_id` = customer_accounts.`company_id`
                                              ) as `debt_amount`
                                          FROM `".local_table_name("cached_customer_accounts")."` customer_accounts
                                          WHERE customer_accounts.`deleted_at` IS NULL
                                          AND customer_accounts.`company_id` = {$remote_customer_id}
                                          AND customer_accounts.`exit_amount` != '0.00'
                                          AND customer_accounts.`exit_amount` IS NOT NULL
                                          ORDER BY customer_accounts.`remote_created_at` desc
                                          LIMIT 1
                                            ";
      $company_customer_account_info_query = $this->local_db->query($company_customer_account_info_sql);
      $result_ava_customer = $company_customer_account_info_query->row_array();

      $customer_payments_sql = "SELECT
                                  customer_accounts.`exit_amount` as `payment_amount`,
                                  customer_accounts.`invoice_code`,
                                  customer_accounts.`report_tr_code`,
                                  customer_accounts.`remote_created_at` as `payment_date`
                              FROM `".local_table_name("cached_customer_accounts")."` customer_accounts
                              WHERE customer_accounts.`deleted_at` IS NULL
                              AND customer_accounts.`company_id` = {$remote_customer_id}
                              AND customer_accounts.`exit_amount` != '0.00'
                              AND customer_accounts.`exit_amount` IS NOT NULL
                              ORDER BY customer_accounts.`remote_created_at` desc
                              LIMIT 12
                            ";
      $customer_payment_query = $this->local_db->query($customer_payments_sql);
      $customer_payments = $customer_payment_query->result_array();

      $client["account"] = $result_ava_customer;
      $client["last_payments"] = $customer_payments;
    }

    $product_list = [];
    // if($products_brand_codes) {
    //   $products_query = $this->local_db->query("SELECT
    //                                               products.`cleaned_brand_code`,
    //                                               products.`stock_baku`,
    //                                               products.`model`,
    //                                               products.`stock_ganja`
    //                                             FROM `".local_table_name("cached_products")."` products
    //                                             WHERE products.`deleted_at` IS NULL
    //                                             AND products.`cleaned_brand_code` IN ('".implode("','",$products_brand_codes)."')");
    //   $b4b_order_products = $products_query->result_array();
    //   foreach ($b4b_order_products as $key => $item) {
    //     $product_list[$item["cleaned_brand_code"]] = $item;
    //   }
    // }
    //
    // if($product_list) {
    //   foreach ($invoice_list as $key => $item) {
    //     $code = cleaned_text($item["brand"]["code"], true);
    //     $invoice_list[$key]["product"] = isset($product_list[$code]) ? $product_list[$code] : NULL;
    //   }
    // }


    $order_edit_statuses = $this->config->item("temprorary_manager_orders_edit_statuses");
    $order_edit_statuses_list = [];
    foreach ($order_edit_statuses as $key => $item) {
      $order_edit_statuses_list[$item] = $order_status === $item ? true : false;
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "client" => $client,
        "invoice" => array_merge($invoice_details,[
          "list" => $invoice_list,
          "order_edit_statuses" => $order_edit_statuses_list
        ]),
      ]
    );
  }
}
