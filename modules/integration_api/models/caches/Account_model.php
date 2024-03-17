<?php
defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('memory_limit','1200006M');
ini_set('max_execution_time', 30); //300 seconds = 5 minutes

class Account_model extends CI_Model {

  public function __construct() {
    parent::__construct();
    $this->remote_db  = $this->load->database("remote_db",true);
    $this->local_db   = $this->load->database("local_db",true);
  }

  function index($params) {
    $cache_types = $this->config->item("cache_types");
    $limit_hour = isset($params["limit_hour"]) ? $params["limit_hour"] : NULL;

    $date_sql_query = "";
    $id_query = "";
    if($limit_hour && is_numeric($limit_hour)) {
      $limit_hour = $limit_hour * 60;
      $date_sql_query = " AND (customer_accounts.[CAPIBLOCK_EXTCREATEDDATE] > DATEADD(MINUTE, -{$limit_hour}, GETDATE()) OR
                                    customer_accounts.[CAPIBLOCK_EXTMODIFIEDDATE] > DATEADD(MINUTE, -{$limit_hour}, GETDATE())) ";
    }

    $date_interval_sql_query = "";
    if (isset($params["start_date"]) && isset($params["end_date"]) && $params["start_date"] && $params["end_date"]) {
      $date_sql_query = "";
      $end_date = date("Y-m-d",strtotime("+1 day",strtotime($params["end_date"])));
      $date_interval_sql_query = " AND ((customer_accounts.[CAPIBLOCK_EXTCREATEDDATE] >= '{$params["start_date"]}'
                                AND customer_accounts.[CAPIBLOCK_EXTCREATEDDATE] <= '{$end_date}') OR (customer_accounts.[CAPIBLOCK_EXTMODIFIEDDATE] >= '{$params["start_date"]}'
                                                          AND customer_accounts.[CAPIBLOCK_EXTMODIFIEDDATE] <= '{$end_date}')) ";
    }

    if (isset($params["local_not_exist_ids"]) && $params["local_not_exist_ids"]) {
      $date_sql_query = "";
      $date_interval_sql_query = "";
      $id_query = " AND LOGICALREF IN (".implode(",",$params["local_not_exist_ids"]).") ";
    }


    $sql = "SELECT
                CONVERT(VARCHAR(1000), customer_accounts.[LOGICALREF], 1) as LOGICALREF,
                CONVERT(VARCHAR(1000), customer_accounts.[SOURCEFREF], 1) as SOURCEFREF,
                CONVERT(VARCHAR, DATEADD(year, +0, customer_accounts.[DATE_]), 20) as DATE_,
                CONVERT(VARCHAR, DATEADD(year, +0, customer_accounts.[CAPIBLOCK_EXTMODIFIEDDATE]), 20) as CAPIBLOCK_EXTMODIFIEDDATE,
                CONVERT(VARCHAR, DATEADD(year, +0, customer_accounts.[CAPIBLOCK_EXTCREATEDDATE]), 20) as CAPIBLOCK_EXTCREATEDDATE,
                payments.[cashbox_id] as cashbox_id,
                CONVERT(VARCHAR(1000), customer_accounts.[SOURCEFREF], 1) as SOURCEFREF,
        				customer_accounts.[TRCODE],
                -- customer_accounts.[TRNET],
                customer_accounts.[AMOUNT],
        				customer_accounts.[REPORTRATE] as currency_rate,
        				customer_accounts.[SIGN],
                invoices.[ADDDISCOUNTS] as add_discounts,
                invoices.[TOTALDISCOUNTS] as total_discounts,
                CONVERT(VARCHAR(1000), customer_accounts.[CLIENTREF], 1) as CLIENTREF,
                CASE
                  WHEN customer_accounts.[TRCODE] = '1' THEN payments.[code]
                  WHEN customer_accounts.[TRCODE] = '4' THEN customer_debts.[code]
                  WHEN customer_accounts.[TRCODE] = '2' THEN (SELECT
          						TOP 1
          						FICHENO
          					FROM
          						LG_001_02_KSLINES
          					WHERE
          						LG_001_02_KSLINES.LOGICALREF = customer_accounts.SOURCEFREF
                    )
                  ELSE (SELECT
          						TOP 1
          						INVNO
          					FROM
          						LG_001_02_STFICHE
          					WHERE
          						LG_001_02_STFICHE.INVOICEREF = customer_accounts.SOURCEFREF)
                END as invoice_number,
                invoices.[DOCTRACKINGNR] as order_number,
                invoices.[TRCODE] as invoice_tr_code,
                invoices.[SOURCEINDEX] as invoice_source_index,
                CASE
                -- WHEN customer_accounts.[SIGN] = 0 THEN customer_accounts.[TRNET]
                  WHEN customer_accounts.[SIGN] = 0 THEN customer_accounts.[AMOUNT]
                  ELSE 0
                END as entry_amount,
                CASE
                -- WHEN customer_accounts.[SIGN] = 1 THEN customer_accounts.[TRNET]
                  WHEN customer_accounts.[SIGN] = 1 THEN customer_accounts.[AMOUNT]
                  ELSE 0
                END as exit_amount,
                invoices.[SOURCEINDEX] as warehouse,
                invoices.[REPORTRATE] as currency_value,
                CASE
                -- WHEN customer_accounts.[SIGN] = 0 THEN customer_accounts.[TRNET]
                  WHEN customer_accounts.[SIGN] = 0 THEN customer_accounts.[REPORTNET]
                  ELSE 0
                END as converted_entry_amount,
                CASE
                -- WHEN invoices.[SIGN] = 1 THEN invoices.[TRNET]
                  WHEN customer_accounts.[SIGN] = 1 THEN customer_accounts.[REPORTNET]
                  ELSE 0
                END as converted_exit_amount
              FROM ".remote_table_name("customer_accounts")." customer_accounts
              LEFT JOIN ".remote_table_name("invoices")." invoices ON invoices.LOGICALREF = customer_accounts.SOURCEFREF
              OUTER APPLY (SELECT
                  TOP 1 FICHENO as code, CARDREF as cashbox_id
                FROM
                  ".remote_table_name("payments")." payments
                WHERE
                  payments.LOGICALREF = customer_accounts.SOURCEFREF
              ) as payments
              OUTER APPLY (SELECT
                  TOP 1 [FICHENO] as code
                FROM
                  ".remote_table_name("customer_debts")." customer_debts
                WHERE
                  customer_debts.LOGICALREF = customer_accounts.SOURCEFREF
              ) as customer_debts
              WHERE customer_accounts.LOGICALREF > 0
              $id_query
              $date_sql_query
              $date_interval_sql_query ";

    $query = $this->remote_db->query($sql);
    $res = $query->result_array();

    if (!$query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $res = $query->result_array();

    $insert_list = [];
    foreach($res as $report){

      switch ($report["warehouse"]) {
        case 0:
          $warehouse = special_codes("cached_customer_accounts.warehouses.baku");
          break;

        case 5:
          $warehouse = special_codes("cached_customer_accounts.warehouses.ganja");
          break;

        default:
          $warehouse = null;
          break;
      }

      $insert_list[] = [
        "remote_id"                   => htmlentities($report["LOGICALREF"]),
        "remote_cashbox_id"           => htmlentities($report["cashbox_id"]),
        "invoice_id"                  => htmlentities($report["SOURCEFREF"]),
        "company_id"                  => htmlentities($report["CLIENTREF"]),
        "report_tr_code"              => htmlentities($report["TRCODE"]),
        "invoice_code"                => htmlentities($report["invoice_number"]),
        "type"                        => getAccountDescription($report["TRCODE"]),
        // "description"                => ava_tr_code_value($report["TRCODE"]),
        "currency_rate"               => $report["currency_rate"] ? 1/$report["currency_rate"] : NULL,
        "entry_amount"                => $report["entry_amount"],
        "exit_amount"                 => $report["exit_amount"],
        "order_number"                => trim(str_replace(["'", "\"",";","\\"], " ", $report["order_number"])),
        "invoice_tr_code"             => htmlentities($report["invoice_tr_code"]),
        "invoice_source_index"        => htmlentities($report["invoice_source_index"]),

        "add_discounts"               => $report["add_discounts"],
        "total_discounts"             => $report["total_discounts"],

        "currency_value"              => $report["currency_value"],
        "converted_entry_amount"      => $report["converted_entry_amount"],
        "converted_exit_amount"       => $report["converted_exit_amount"],

        "left_amount"                 => 0,
        "warehouse"                   => $warehouse,
        "operation_date"              => htmlentities($report["DATE_"]),
        "remote_created_at"           => $report["CAPIBLOCK_EXTCREATEDDATE"],
      ];
    }


    if($insert_list){
      $cached_info_data = $insert_list;
      $cached_info_insert_sql = insert_dublicate_key(local_table_name($cache_types[$params["type"]]["table"]), $cached_info_data);
      $this->local_db->query($cached_info_insert_sql);

      $this->local_db->insert(local_table_name("cached_history"), [
        "type" => $params["type"],
        "request_type" => $params["request_type"],
        "creator_ip" => $params["creator_ip"],
          "creator_id" => $params["creator_id"],
        "result_count" => count($cached_info_data),
        "operation_date" => $params["date"],
        "interval_start_date" => $params["start_date"] ? $params["start_date"] : (date("Y-m-d H:i:s", strtotime('-'.($params["limit_hour"] * 60).' minutes'))),
        "interval_end_date" => $params["end_date"] ?: now()
      ]);
    }


    $sql_query = "SELECT [LOGICALREF] as id
                  FROM [".remote_table_name("customer_accounts")."]";
    $exist_ids = $this->remote_db->query($sql_query);
    $exist_ids = $exist_ids->result_array();
    $exist_ids = array_map(function($i) {
      return $i["id"];
    },$exist_ids);

    $sql_query = "SELECT `remote_id`
                  FROM `".local_table_name($cache_types[$params["type"]]["table"])."`
                  WHERE `remote_id` NOT IN (".implode(",",$exist_ids).")
                  AND `deleted_at` IS NULL";
    $local_not_exist_ids = $this->local_db->query($sql_query);

    if ($local_not_exist_ids->num_rows()) {
      $local_not_exist_ids = $local_not_exist_ids->result_array();
      $local_not_exist_ids = array_map(function($i) {
        return $i["remote_id"];
      },$local_not_exist_ids);
      $params["local_not_exist_ids"] = $local_not_exist_ids;
      $this->local_db->where_in("remote_id",$params["local_not_exist_ids"]);
      $this->local_db->update(local_table_name($cache_types[$params["type"]]["table"]),[
        "deleted_at" => now()
      ]);
    }


    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Cache refreshed"),
      [
        "date" => $params["date"]
      ]
    );
  }


  function details($params)
  {
    $cache_types = $this->config->item("cache_types");
    $limit_hour = $params["limit_hour"];

    $details_date_sql_query   = "";
    $details_date_mysql_query = "";
    if($limit_hour && is_numeric($limit_hour)) {
      $limit_hour = ($limit_hour + 24) * 60;
      $details_date_sql_query = " AND invoices_details.[DATE_] > DATEADD(MINUTE, -{$limit_hour}, GETDATE())";
      $details_date_mysql_query = " AND TIMESTAMPDIFF(MINUTE,IFNULL(`operation_date`,`created_at`),NOW()) <= {$limit_hour} ";
    }

    $date_interval_sql_query = "";
    $date_interval_mysql_query = "";
    if ($params["start_date"] && $params["end_date"]) {
      $details_date_sql_query = "";
      $end_date = date("Y-m-d",strtotime("+1 day",strtotime($params["end_date"])));
      $date_interval_sql_query = " AND invoices_details.[DATE_] >= '{$params["start_date"]}' ";
      $date_interval_sql_query .= " AND invoices_details.[DATE_] <= '{$end_date}' ";


      $date_interval_mysql_query = " AND IFNULL(`operation_date`,`created_at`) >= '{$params["start_date"]}' ";
      $date_interval_mysql_query .= " AND IFNULL(`operation_date`,`created_at`) <= '{$end_date}' ";
    }


    $details_sql = "SELECT
              				invoices_details.[AMOUNT] AS ORDER_QUANTITY,
                      invoices_details.[REPORTRATE] as currency_rate,

                      invoices_details.[PRICE] AS SALE_PRICE,
                      CASE WHEN invoices_details.[AMOUNT] != 0 THEN invoices_details.[VATMATRAH] / invoices_details.[AMOUNT] ELSE 0 END as discount_amount,
                      CASE WHEN invoices_details.[REPORTRATE] > 0 THEN invoices_details.[PRICE]/invoices_details.[REPORTRATE] ELSE 0 END AS converted_amount,
                      CASE WHEN invoices_details.[REPORTRATE] != 0 AND invoices_details.[AMOUNT] != 0 THEN invoices_details.[VATMATRAH]/invoices_details.[REPORTRATE]/invoices_details.[AMOUNT] ELSE 0 END AS converted_discount_amount,

                      invoices_details.[TOTAL] AS total_amount,
                      invoices_details.[VATMATRAH] AS total_discount_amount,
                      CASE WHEN invoices_details.[REPORTRATE] > 0 THEN invoices_details.[TOTAL]/invoices_details.[REPORTRATE] ELSE 0 END AS converted_total_amount,
                      CASE WHEN invoices_details.[REPORTRATE] > 0 THEN invoices_details.[VATMATRAH]/invoices_details.[REPORTRATE] ELSE 0 END AS converted_total_discount_amount,

              				invoices_details.[CLIENTREF] AS COMPANY_ID,
              				invoices_details.[INVOICEREF] AS INVOICE_ID,
                      invoices_details.[STOCKREF] as PRODUCT_ID,
                      invoices_details.[LOGICALREF] AS remote_id,
              				invoices_details.[TRCODE] AS trcode,
              				product.[CODE] as BREND_KODU,
              				product.[SPECODE] AS BREND,
              				product.[SPECODE2] AS DESCRIPTION,
              				product.[SPECODE3] AS OEM_KODU,
              				product.[SPECODE10] AS PART_NAME,
                      invoices_details.[INVOICELNNO],
                      CONVERT(VARCHAR, DATEADD(year, +0, invoices_details.[DATE_]), 20) as operation_date
              			FROM
              				".remote_table_name("invoices_details")." invoices_details
              			LEFT JOIN ".remote_table_name("products")." product ON product.LOGICALREF = invoices_details.STOCKREF
                    WHERE 1 > 0
                    $details_date_sql_query
                    $date_interval_sql_query ";

    $details_query = $this->remote_db->query($details_sql);
    $details_res = $details_query->result_array();

    $details_list = [];
    foreach ($details_res as $key => $item) {
      $cleaned_brand_code = cleaned_text($item["BREND_KODU"]);
      $cleaned_oem_code = cleaned_text($item["OEM_KODU"]);

      $details_list[] = [
        "remote_id"                   => str_replace("'", "\'",htmlentities($item["remote_id"])),
        "remote_invoice_id"           => str_replace("'", "\'",htmlentities($item["INVOICE_ID"])) ?: NULL,
        "company_id"                  => str_replace("'", "\'",htmlentities($item["COMPANY_ID"])) ?: NULL,
        "invoice_code"                => null,
        "brand"                       => str_replace("'", "\'",htmlentities($item["BREND"])),
        "brand_code"                  => str_replace("'", "\'",htmlentities($item["BREND_KODU"])),
        "cleaned_brand_code"          => $cleaned_brand_code,
        "OEM"                         => str_replace("'", "\'",htmlentities($item["OEM_KODU"])),
        "cleaned_oem_code"            => $cleaned_oem_code,
        "product_name"				        => str_replace("'", "\'",htmlentities($item["PART_NAME"])),
        "product_id"				          => str_replace("'", "\'",htmlentities($item["PRODUCT_ID"])),
        "description"			            => str_replace("'", "\'",htmlentities($item["DESCRIPTION"])),
        "quantity"		                => str_replace("'", "\'",htmlentities($item["ORDER_QUANTITY"])),
        "amount"			                => $item["SALE_PRICE"],
        "trcode"			                => $item["trcode"],
        "total_amount"			          => $item["total_amount"],
        "converted_amount"            => $item["converted_amount"],
        "converted_total_amount"      => $item["converted_total_amount"],

        "discount_amount"                      => $item["discount_amount"],
        "converted_discount_amount"            => $item["converted_discount_amount"],
        "total_discount_amount"                => $item["total_discount_amount"],
        "converted_total_discount_amount"      => $item["converted_total_discount_amount"],

        "currency_rate"			          => $item["currency_rate"] ? 1/$item["currency_rate"] : NULL,
        "operation_date"			        => $item["operation_date"],
      ];
    }

    if($details_list){
      $cached_details_insert_sql = insert_dublicate_key(local_table_name("cached_invoices"), $details_list);
      $this->local_db->query($cached_details_insert_sql);

      $this->local_db->insert(local_table_name("cached_history"), [
        "type" => $params["type"],
        "request_type" => $params["request_type"],
        "creator_ip" => $params["creator_ip"],
          "creator_id" => $params["creator_id"],
        "result_count" => count($details_list),
        "operation_date" => $params["date"],
        "interval_start_date" => $params["start_date"] ? $params["start_date"] : (date("Y-m-d H:i:s", strtotime('-'.($params["limit_hour"] * 60).' minutes'))),
        "interval_end_date" => $params["end_date"] ?: now()
      ]);
    }

    $this->findRemovedInvoices();


    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Cache refreshed"),
      [
        "date" => $params["date"]
      ]
    );
  }


  function findRemovedInvoices()
  {
      $sql_query = "SELECT inv_1.[ID]
                    FROM (SELECT ROW_NUMBER() OVER(ORDER BY LOGICALREF) AS ID FROM LG_001_02_STINVTOT) inv_1
                    WHERE inv_1.[ID] NOT IN (SELECT inv_2.[LOGICALREF] FROM [LG_001_02_STLINE] inv_2)
                    AND inv_1.[ID] < (SELECT MAX(LOGICALREF) FROM [LG_001_02_STLINE])
                    ORDER BY inv_1.[ID] DESC";
    // $sql_query = "SELECT inv_1.[LOGICALREF] + 1 AS ID
    //               FROM [".remote_table_name("invoices_details")."] inv_1
    //               LEFT JOIN [".remote_table_name("invoices_details")."] inv_2 ON inv_2.LOGICALREF = inv_1.LOGICALREF + 1
    //               WHERE inv_2.[LOGICALREF] IS NULL
    //                   AND inv_1.LOGICALREF <> (SELECT MAX(LOGICALREF) FROM [".remote_table_name("invoices_details")."])
    //               ORDER BY inv_1.LOGICALREF";
    $removed_ids_query = $this->remote_db->query($sql_query);
    $removed_ids = $removed_ids_query->result_array();

    if ($removed_ids_query->num_rows()) {
      $params["local_not_exist_ids"] = array_map(function($i){
        return $i["ID"];
      },$removed_ids);
      $sql_update_query = "UPDATE `".local_table_name("cached_invoices")."`
                           SET `deleted_at` = '".now()."'
                           WHERE `remote_id` IN (".implode(",",$params["local_not_exist_ids"]).")
                           AND `deleted_at` IS NULL";
      $this->local_db->query($sql_update_query);
    }

    return true;
  }

}
