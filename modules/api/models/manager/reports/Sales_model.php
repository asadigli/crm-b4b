<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_model extends CI_Model{

  function __construct()
  {
    parent::__construct();
  }


  function index($params) {
    // $currency_sql = "SELECT
    //                   (SELECT
    //                     currency.`value`
    //                   FROM `".local_table_name("cached_currencies")."` currency
    //                   WHERE currency.`id` = customer.`currency_id`) as `value`
    //                 FROM `".local_table_name("cached_customer_accounts")."` account
    //                 LEFT JOIN `".local_table_name("cached_customers")."` customer ON customer.`remote_id` = `account`.`company_id`
    //                 WHERE `account`.`deleted_at` IS NULL
    //                 AND `account`.`type` = '".special_codes("cached_customer_accounts.types.sale_invoice")."'
    //                 AND YEAR(`account`.`operation_date`) = '{$params["year"]}'
    //                 AND MONTH(`account`.`operation_date`) = '{$params["month"]}'
    //                 -- AND customer.`code` LIKE '211.%' ";
    //
    // $currency_query = $params["local_db"]->query($currency_sql);
    //
    // if(!$currency_query->num_rows()){
    //   return rest_response(
    //     Status_codes::HTTP_NO_CONTENT,
    //     lang("Currency AZN is not found")
    //   );
    // }
    //
    // $currency = $currency_query->row_array();


    // AND `account`.`type` IN (
    //   '".special_codes("cached_customer_accounts.types.payment")."',
    //   '".special_codes("cached_customer_accounts.types.debt_first")."',
    //   '".special_codes("cached_customer_accounts.types.credit")."',
    //   '".special_codes("cached_customer_accounts.types.initial")."',
    //   '".special_codes("cached_customer_accounts.types.return")."',
    //   '".special_codes("cached_customer_accounts.types.sale_invoice")."'
    // )
    // SUM(CASE WHEN `account`.`type` = '".special_codes("cached_customer_accounts.types.return")."'
    //           AND YEAR(`account`.`operation_date`) = '{$params["year"]}'
    //           AND MONTH(`account`.`operation_date`) = '{$params["month"]}'
    //           AND DAY(`account`.`operation_date`) = '{$params["day"]}'
    //           THEN `account`.`exit_amount` - `account`.`entry_amount`
    //           ELSE 0 END) as daily_eur_returns,
    $sql_query = "SELECT
                      SUM(CASE WHEN `account`.`type` = '".special_codes("cached_customer_accounts.types.sale_invoice")."'
                                AND YEAR(`account`.`operation_date`) = '{$params["year"]}'
                                AND MONTH(`account`.`operation_date`) = '{$params["month"]}'
                                AND DAY(`account`.`operation_date`) = '{$params["day"]}'
                                THEN (`account`.`entry_amount` - `account`.`exit_amount`) ELSE 0 END) as daily_eur_sales,

                      SUM(CASE WHEN `account`.`type` = '".special_codes("cached_customer_accounts.types.sale_invoice")."'
                                AND YEAR(`account`.`operation_date`) = '{$params["year"]}'
                                AND MONTH(`account`.`operation_date`) = '{$params["month"]}'
                                THEN `account`.`entry_amount` - `account`.`exit_amount` ELSE 0 END) as monthly_eur_sales,

                      SUM(CASE WHEN `account`.`type` = '".special_codes("cached_customer_accounts.types.return")."'
                                AND YEAR(`account`.`operation_date`) = '{$params["year"]}'
                                AND MONTH(`account`.`operation_date`) = '{$params["month"]}'
                                THEN `account`.`entry_amount` - `account`.`exit_amount` ELSE 0 END) as monthly_eur_returns,

                      SUM(CASE WHEN customer.`remote_is_active` = '1' AND customer.`code` LIKE '211%'
                                -- AND YEAR(`operation_date`) <= {$params["year"]}
                                AND `operation_date` <= '".date("Y-m-d", strtotime("+1 month", strtotime(date($params["year"] . "-" . $params["month"] . "-01"))))."'-- (CASE WHEN YEAR(`operation_date`) = {$params["year"]} THEN MONTH(`operation_date`) <= ".(int)$params["month"]." AND 1 END)
                                THEN `account`.`entry_amount` - `account`.`exit_amount` ELSE 0 END) as customer_eur_debt,

                      SUM(CASE WHEN `account`.`report_tr_code` = 31
                                 AND `account`.`invoice_source_index` IN (0,1)
                                 AND YEAR(`operation_date`) = '{$params["year"]}'
                                 AND MONTH(`operation_date`) = '{$params["month"]}'
                                 THEN `account`.`exit_amount` - `account`.`entry_amount` ELSE 0 END) as monthly_eur_purchase


                  FROM `".local_table_name("cached_customer_accounts")."` `account`
                  LEFT JOIN `".local_table_name("cached_customers")."` customer ON `customer`.`remote_id` = `account`.`company_id`
                  LEFT JOIN `".local_table_name("cached_currencies")."` currencies ON  `currencies`.`id` = `customer`.`currency_id`
                  AND `currencies`.`deleted_at` IS NULL
                  WHERE `account`.`deleted_at` IS NULL";
                  // log_message("ERROR",$sql_query);
    $sales_query = $params["local_db"]->query($sql_query);
    $sales = $sales_query->row_array();
    // $sales["daily_eur_sales"]   = $sales["daily_eur_sales"] - $sales["daily_eur_returns"];
    $sales["monthly_eur_sales"] = $sales["monthly_eur_sales"] + $sales["monthly_eur_returns"];

    if (isset($sales["daily_sales"])) {
      $sales["daily_sales"]       = number_format($sales["daily_sales"],2,".",",");
      $sales["monthly_sales"]     = number_format($sales["monthly_sales"],2,".",",");
      $sales["customer_debt"]     = number_format($sales["customer_debt"],2,".",",");
      $sales["monthly_purchase"]  = number_format($sales["monthly_purchase"],2,".",",");
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $sales
    );
  }


  function annualReports($params) {

    $warehouse_query  = !is_null($params["warehouse"]) ? " AND `account`.`report_tr_code` = 31 AND `account`.`invoice_source_index` IN (0,1) " : ""; //" AND `account`.`invoice_source_index` = '{$params["warehouse"]}' " : " ";

    $sql_query = "SELECT
              			SUM(CASE WHEN `account`.`type` = '".special_codes("cached_customer_accounts.types.sale_invoice")."'
                             THEN `account`.`entry_amount`
                             ELSE 0 END) as total_sale,
                    SUM(CASE WHEN `account`.`type` = '".special_codes("cached_customer_accounts.types.payment")."'
                             AND (cached_customers.`code` LIKE '211%' OR cached_customers.`code` LIKE '311%')
                             THEN `account`.`exit_amount`
                             ELSE 0 END) as total_payment,
                    SUM(CASE WHEN `account`.`type` = '".special_codes("cached_customer_accounts.types.return")."'
                             THEN `account`.`exit_amount` - `account`.`entry_amount`
                             ELSE 0 END) as total_return,
                    SUM(CASE WHEN account.`type` = '".special_codes("cached_customer_accounts.types.purchase_invoice")."'
                             $warehouse_query
                             THEN `account`.`exit_amount` - `account`.`entry_amount`
                             ELSE 0 END) as total_purchase,
                    MONTH(`operation_date`) as month
              		FROM
              			`".local_table_name("cached_customer_accounts")."` `account`
                  LEFT JOIN ".local_table_name("cached_customers")." cached_customers ON cached_customers.`id` = account.`customer_id`
              		WHERE
              			YEAR(`operation_date`) = '{$params["year"]}'
                  AND `account`.`deleted_at` IS NULL
                  GROUP BY
                    MONTH(`operation_date`)
              		ORDER BY
              			MONTH(`operation_date`)
              		ASC";
                  // log_message("ERROR",$sql_query);
    $reports_query = $params["local_db"]->query($sql_query);
    $reports = $reports_query->result_array();

    $reports_list = [];

    $average_sales      = $average_purchases = $average_payments = 0;
    $end_date           = (int)$params["year"] !== (int)date("Y") ? date($params["year"] . "-12-31") : NULL;
    $average_by         = (int)date("m") - 1 ? getDaysBetween(date($params["year"] . "-01-01"),$end_date) : 0;

    foreach ($reports as $key => $report) {
      $reports_list["sales"][]      = [
        "month"   => (float)$report["month"],
        "amount"  => $report["total_sale"] - $report["total_return"]
      ];
      $reports_list["payments"][]   = [
        "month"   => (float)$report["month"],
        "amount"  => (float)$report["total_payment"]
      ];
      $reports_list["returns"][]    = [
        "month"   => (float)$report["month"],
        "amount"  => (float)$report["total_return"]
      ];
      $reports_list["purchases"][]  = [
        "month"   => (float)$report["month"],
        "amount"  => (float)$report["total_purchase"]
      ];

      $average_sales        += abs($report["total_sale"]) - $report["total_return"];
      $average_purchases    += $report["total_purchase"];
      $average_payments     += $report["total_payment"];
    }


    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "list"                => $reports_list,
        "average_sales"       => $average_by ? 30*($average_sales/$average_by) : $average_sales,
        "average_purchases"   => $average_by ? 30*($average_purchases/$average_by) : $average_purchases,
        "average_payments"    => $average_by ? 30*($average_payments/$average_by) : $average_payments,
      ]
    );
  }

}
