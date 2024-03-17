<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  public function index($params){
    escapeAllKeys($params,null,$this->local_db);

    $check_customer_sql = "SELECT
                                customers.`id`,
                                customers.`currency_id`
                              FROM `".local_table_name("cached_customers")."` customers
                              WHERE customers.`deleted_at` IS NULL
                              AND customers.`id` = {$params["customer_id"]}
                              ";
    $check_customer_query = $this->local_db->query($check_customer_sql);
    if(!$check_customer_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Customer not found")
      );
    }
    $check_customer = $check_customer_query->row_array();
    $res_currencies = get_currency();
    $currencies_list = [];
    foreach ($res_currencies as $key => $item) {
      $currencies_list[$item["id"]] = $item;
    }

    $is_converted_to_azn = false;
    if(isset($currencies_list[$check_customer["currency_id"]]) && $currencies_list[$check_customer["currency_id"]]["main_name"] === CURRENCY_AZN){
      $is_converted_to_azn = true;
    }

    $page_load_limit = $this->config->item("customer_accounts_page_load_limit") ?: 200;
    $params["end_date"] = $params["end_date"] ? date("Y-m-d",strtotime("+1 day", strtotime($params["end_date"]))) : NULL;

    $start_date_query = "";
    $sub_start_date_query = "";
    $end_date_query = "";
    $sub_end_date_query = "";
    $debt_end_query = "";
    $offset_query = "";
    $brand_code_query = "";
    $oem_query = "";

    if ($params["start_date"]) {
      $start_date_query = " AND account.`operation_date` >= '{$params["start_date"]}' ";
      $sub_start_date_query = " AND sub_account.`operation_date` >= '{$params["start_date"]}' ";
    }
    if ($params["end_date"]) {
      $end_date_query = " AND account.`operation_date` < '{$params["end_date"]}' ";
      $sub_end_date_query = " AND sub_account.`operation_date` < '{$params["end_date"]}' ";
      $debt_end_query = " AND `operation_date` < '{$params["end_date"]}'";
    }


    if ($params["brand_code"]) {
      $brand_code = cleaned_text($params["brand_code"]);
      $brand_code_query = " AND `cleaned_brand_code` LIKE '%{$brand_code}%' ";
    }


    $offset_query = $params["offset"] && is_numeric($params["offset"]) ? "OFFSET {$params["offset"]}" : "";
    $brand_query = $params["brand"] ? "AND `brand` = '{$params["brand"]}' " : "";
    $oem_query = $params["oem_code"] ? " AND `cleaned_oem_code` LIKE '%{$params["oem_code"]}%' " : "";

    $details_query = "";
    if ($brand_query || $brand_code_query || $oem_query) {
      $details_query = " AND account.`invoice_id` IN (SELECT remote_invoice_id
                                                      FROM ".local_table_name("cached_invoices")."
                                                      WHERE deleted_at IS NULL
                                                      $brand_query
                                                      $brand_code_query
                                                      $oem_query) ";
    }


    $main_body_query = "FROM ".local_table_name("cached_customer_accounts")." `account`
                          LEFT JOIN ".local_table_name("cached_cashboxes")." cash ON cash.id = account.cashbox_id
                          WHERE account.`deleted_at` IS NULL
                          AND account.`customer_id` = {$params["customer_id"]}
                        $start_date_query
                        $end_date_query
                        $details_query
                        ";

    $customer_account_querysql = "SELECT
                                      SQL_CALC_FOUND_ROWS account.`id`,
                                      account.`remote_id`,
                                      account.`invoice_id`,
                                      account.`company_id`,
                                      account.`invoice_code`,
                                      account.`warehouse`,
                                      account.`description`,
                                      account.`type`,
                                      ". ($is_converted_to_azn ? "account.`converted_entry_amount` as `entry_amount`," : "account.`entry_amount`,") ."
                                      ". ($is_converted_to_azn ? "account.`converted_exit_amount` as `exit_amount`," : "account.`exit_amount`,") ."
                                      account.`currency_rate`,
                                      (SELECT
                                          CONCAT(
                                                SUM(".($is_converted_to_azn ? "`converted_entry_amount`" : "`entry_amount`") ."),'|||',
                                                SUM(".($is_converted_to_azn ? "`converted_exit_amount`" : "`exit_amount`") ."),'|||',SUM(CASE WHEN CONCAT(UNIX_TIMESTAMP(sub_account.`operation_date`),'.',sub_account.`remote_id`) <= CONCAT(UNIX_TIMESTAMP(account.`operation_date`),'.',account.`remote_id`) THEN sub_account.".($is_converted_to_azn ? "`converted_entry_amount`" : "`entry_amount`")." - sub_account.".($is_converted_to_azn ? "`converted_exit_amount`" : "`exit_amount`") ." ELSE 0 END))
                                       FROM `".local_table_name("cached_customer_accounts")."` sub_account
                                       WHERE sub_account.`deleted_at` IS NULL

                                       $sub_end_date_query
                                       AND sub_account.`customer_id` = {$params["customer_id"]}) as left_amount,
                                      account.`operation_date`,
                                      account.`report_tr_code` as tr_code,
                                      IFNULL(cash.`custom_name`,cash.`name`) as payment_type

                                  $main_body_query

                                  ORDER BY account.`operation_date` ASC, account.`remote_id` ASC
                                  LIMIT $page_load_limit
                                  $offset_query ";

    $customer_account_query = $this->local_db->query($customer_account_querysql);


    if (!$customer_account_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result"),
        [
          "count" => 0,
          "totals" => [
            "entry" => 0,
            "balance" => 0,
            "exit" => 0,
          ],
          "whole_totals" => [
            "left_amount" => 0,
            "entry" => 0,
            "exit" => 0,
          ],
          "list" => []
        ]
      );
    }

    $main_total_query = $this->local_db->query("SELECT
                            FOUND_ROWS() as count,
                            SUM(IFNULL(". ($is_converted_to_azn ? "account.`converted_entry_amount`" : "account.`entry_amount`") .",0)) as `filter_total_entry`,
                            SUM(IFNULL(". ($is_converted_to_azn ? "account.`converted_exit_amount`" : "account.`exit_amount`") .",0)) as `filter_total_exit`
                          $main_body_query
                          ");

    $main_total = $main_total_query->row_array();

    $total_entry      = 0;
    $total_exit       = 0;

    $customer_account = $customer_account_query->result_array();

    $baku = special_codes("cached_customer_accounts.warehouses.baku");
    $ganja = special_codes("cached_customer_accounts.warehouses.ganja");

    // $filter_total_entry = 0;
    // $filter_total_exit = 0;
    foreach ($customer_account as $key => $item) {
      $type                                 = $item["type"] ? special_codes("cached_customer_accounts.types",$item["type"]) : NULL;
      $customer_account[$key]["warehouse"]  = $item["warehouse"] && $type === "sale_invoice" ? ($item["warehouse"] === $baku ? lang("Baku") : lang("Ganja")) : NULL;
      $type                                 = $type && is_string($type) ? lang(ucfirst($type)) : NULL;

      $amounts                              = explode("|||",$item["left_amount"]);
      $amounts                              = array_filter($amounts);


      if (!$key && isset($amounts[1])) {
        $total_entry              = $amounts[0];
        $total_exit               = $amounts[1];
      }
      $customer_account[$key]["entry_amount"]  = (float)$item["entry_amount"] > 0 ? $item["entry_amount"] : null;
      // $filter_total_entry += $customer_account[$key]["entry_amount"];

      $customer_account[$key]["exit_amount"]   = (float)$item["exit_amount"] > 0 ? $item["exit_amount"] : null;
      // $filter_total_exit += $customer_account[$key]["exit_amount"];

      // $customer_account[$key]["balance"] = $customer_account[$key]["entry_amount"] - $customer_account[$key]["exit_amount"];

      $customer_account[$key]["left_amount"]   = isset($amounts[2]) ? $amounts[2] : 0;

      $customer_account[$key]["type"] = $type;
      $customer_account[$key]["is_invoice"] = in_array((int)$item["tr_code"],[33,38]);
      unset($customer_account[$key]["tr_code"]);
    }

    $total_sum  = $total_entry - $total_exit;

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => $main_total["count"],
        "totals" => [
          "entry" => $main_total["filter_total_entry"],
          "exit" => $main_total["filter_total_exit"],
          "balance" => $main_total["filter_total_entry"] - $main_total["filter_total_exit"],
        ],
        "whole_totals" => [
          "left_amount" => $total_sum,
          "entry" => $total_entry,
          "exit" => $total_exit,
        ],
        "list" => $customer_account
      ]
    );
  }

  public function details($params){
    escapeAllKeys($params,null,$this->local_db);

    $check_customer_sql = "SELECT
                                customers.`id`,
                                customers.`currency_id`
                              FROM `".local_table_name("cached_customers")."` customers
                              WHERE customers.`deleted_at` IS NULL
                              AND customers.`id` = {$params["customer_id"]}
                              ";
    $check_customer_query = $this->local_db->query($check_customer_sql);
    if(!$check_customer_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Customer not found")
      );
    }
    $check_customer = $check_customer_query->row_array();
    $res_currencies = get_currency();
    $currencies_list = [];
    foreach ($res_currencies as $key => $item) {
      $currencies_list[$item["id"]] = $item;
    }

    $is_converted_to_azn = false;
    if(isset($currencies_list[$check_customer["currency_id"]]) && $currencies_list[$check_customer["currency_id"]]["main_name"] === CURRENCY_AZN){
      $is_converted_to_azn = true;
    }


    $sql = "SELECT
                detail.`remote_id` as `id`,
                detail.`brand`,
                detail.`brand_code`,
                detail.`OEM`,
                detail.`product_name`,
                detail.`description`,
                detail.`quantity`,
                (CASE
                  WHEN account.`total_discounts` > 0.1
                    THEN detail.".($is_converted_to_azn ? "`converted_discount_amount`" : "`discount_amount`")."
                  ELSE detail.".($is_converted_to_azn ? "`converted_amount`" : "`amount`") ." END ) as `amount`,
                (CASE
                  WHEN account.`total_discounts` > 0.1
                    THEN detail.".($is_converted_to_azn ? "`converted_total_discount_amount`" : "`total_discount_amount`")."
                  ELSE detail.".($is_converted_to_azn ? "`converted_total_amount`" : "`total_amount`") ." END ) as `total_amount`,
                detail.`currency_rate`
            FROM ".local_table_name("cached_customer_accounts")." account
            LEFT JOIN ".local_table_name("cached_invoices")." detail ON account.`invoice_id` = detail.`remote_invoice_id`
            WHERE detail.`deleted_at` IS NULL
            AND account.`invoice_code` = '{$params["code"]}'
            AND account.`customer_id` = {$params["customer_id"]}
            AND detail.`quantity` != 0
            ";

    $query = $this->local_db->query($sql);

    if (!$query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $res = $query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $res
    );
  }

  public function brandReportsInDetails($params) {
    escapeAllKeys($params,null,$this->local_db);

    $page_load_limit = $this->config->item("customer_accounts_page_load_limit") ?: 200;

    $customer_query = "";
    if ($params["customer_id"]) {
      $customer_query = "AND detail.`company_id` = '{$params["customer_id"]}' ";
    }
    $start_date_query = "";
    if ($params["start_date"]) {
      $start_date_query = "AND account.`operation_date` >= '{$params["start_date"]}' ";
    }
    $end_date_query = "";
    if ($params["end_date"]) {
      $end_date = date("Y-m-d",strtotime("+1 day", strtotime($params["end_date"])));
      $end_date_query = "AND account.`operation_date` < '{$end_date}' ";
    }
    $offset_query = "";
    if ($params["offset"] && is_numeric($params["offset"])) {
      $offset_query = "OFFSET {$params["offset"]}";
    }
    $brand_query = "";
    if ($params["brand"]) {
      $brand_query = "AND detail.`brand` = '{$params["brand"]}' ";
    }
    $brand_code_query = "";
    if ($params["brand_code"]) {
      $brand_code = cleaned_text($params["brand_code"]);
      $brand_code_query = "AND detail.`cleaned_brand_code` LIKE '%{$brand_code}%' ";
    }


    $body_query = " FROM ".local_table_name("cached_invoices")." detail
                  LEFT JOIN ".local_table_name("cached_customer_accounts")." account ON account.`invoice_id` = detail.`remote_invoice_id`
                  LEFT JOIN ".local_table_name("cached_cashboxes")." cash ON cash.id = account.cashbox_id
                  LEFT JOIN ".local_table_name("cached_customers")." customer ON customer.remote_id = detail.company_id
                  LEFT JOIN ".local_table_name("cached_products")." product ON product.remote_id = detail.product_id
                  WHERE account.`deleted_at` IS NULL
                  AND account.`type` IN ('".special_codes("cached_customer_accounts.types.sale_invoice")."')
                  AND detail.`quantity` != 0
                  AND detail.`company_id` IS NOT NULL
                  AND detail.`remote_invoice_id` IS NOT NULL
                  $customer_query
                  $start_date_query
                  $end_date_query
                  $brand_query
                  $brand_code_query ";

    $sql = "SELECT
                detail.`remote_id` as `id`,
                detail.`brand`,
                detail.`brand_code`,
                detail.`OEM`,
                detail.`product_name`,
                detail.`description`,
                detail.`quantity`,
                detail.`amount`,
                detail.`total_amount`,
                account.`invoice_code`,
                account.`warehouse`,
                account.`operation_date`,
                IFNULL(cash.`custom_name`,cash.`name`) as cashbox_name,
                customer.`name` as customer,
                product.`buying_price`
            $body_query
            ORDER BY account.`operation_date` ASC
            LIMIT $page_load_limit
            $offset_query ";

    $query = $this->local_db->query($sql);

    $count_query = $this->local_db->query("SELECT COUNT(1) as count, SUM(IFNULL(detail.`total_amount`,0)) as total_sale_amount $body_query");

    if (!$query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result"),
        [
          "count" => 0
        ]
      );
    }

    $res = $query->result_array();
    $count = $count_query->row_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => $count["count"],
        "total_sale_amount" => $count["total_sale_amount"],
        "list" => $res
      ]
    );
  }


  /***/
  public function brandReports($params) {
    escapeAllKeys($params,null,$this->local_db);

    // $page_load_limit = $this->config->item("customer_accounts_page_load_limit") ?: 200;

    $customer_query = "";
    if ($params["customer_id"]) {
      $customer_query = "AND detail.`company_id` = '{$params["customer_id"]}' ";
    }
    $start_date_query = "";
    if ($params["start_date"]) {
      $start_date_query = "AND account.`operation_date` >= '{$params["start_date"]}' ";
    }
    $end_date_query = "";
    if ($params["end_date"]) {
      $end_date = date("Y-m-d",strtotime("+1 day", strtotime($params["end_date"])));
      $end_date_query = "AND account.`operation_date` < '{$end_date}' ";
    }
    $offset_query = "";
    if ($params["offset"] && is_numeric($params["offset"])) {
      $offset_query = "OFFSET {$params["offset"]}";
    }
    $brand_query = "";
    if ($params["brand"]) {
      $brand_query = "AND detail.`brand` = '{$params["brand"]}' ";
    }
    $brand_code_query = "";
    if ($params["brand_code"]) {
      $brand_code = cleaned_text($params["brand_code"]);
      $brand_code_query = "AND detail.`cleaned_brand_code` LIKE '%{$brand_code}%' ";
    }



    $sql = "SELECT
                parent_detail.`brand`,
                SUM(parent_detail.`quantity`) as `quantity`,
                SUM(parent_detail.`amount`) as `amount`,
                SUM(parent_detail.`total_amount`) as `total_amount`
                -- ,SUM(product.`buying_price`) as `buying_price`
            FROM ".local_table_name("cached_invoices")." parent_detail
            WHERE parent_detail.`id` IN (SELECT
                      detail.`id`
                   FROM ".local_table_name("cached_invoices")." detail
                   LEFT JOIN ".local_table_name("cached_customer_accounts")." account ON account.`invoice_id` = detail.`remote_invoice_id`
                   LEFT JOIN ".local_table_name("cached_cashboxes")." cash ON cash.id = account.cashbox_id
                   LEFT JOIN ".local_table_name("cached_customers")." customer ON customer.remote_id = detail.company_id
                   LEFT JOIN ".local_table_name("cached_products")." product ON product.remote_id = detail.product_id
                   WHERE account.`deleted_at` IS NULL
                   AND detail.`quantity` != 0
                   AND detail.`company_id` IS NOT NULL
                   AND detail.`remote_invoice_id` IS NOT NULL
                   AND account.`type` IN ('".special_codes("cached_customer_accounts.types.sale_invoice")."')
                   -- AND detail.`brand` IS NOT NULL AND detail.`brand` != ''
                   $customer_query
                   $start_date_query
                   $end_date_query
                   $brand_query
                   $brand_code_query)
            GROUP BY parent_detail.`brand`
            ORDER BY SUM(parent_detail.`total_amount`) DESC ";

            // LIMIT $page_load_limit
            // $offset_query

    $invoice_query = $this->local_db->query($sql);

    // $count_query = $this->local_db->query("SELECT COUNT(1) as count, SUM(IFNULL(detail.`total_amount`,0)) as total_sale_amount $body_query");

    if (!$invoice_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result"),
        [
          "count" => 0
        ]
      );
    }

    $res = $invoice_query->result_array();
    // $count = $count_query->row_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => count($res),//$count["count"],
        "total_sale_amount" => array_sum(array_column($res, 'total_amount')),
        "list" => $res
      ]
    );
  }

}
