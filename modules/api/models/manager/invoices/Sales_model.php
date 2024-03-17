<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  /*
  *
  */
  public function daily_list($params) {
    escapeAllKeys($params,null,$this->local_db);


    $start_date_query = $sub_start_date_query = $end_date_query = $sub_end_date_query = $offset_query = $brand_code_query = $oem_query = "";

    if ($params["start_date"]) {
      $start_date_query = " AND local_query.`selected_date` >= '{$params["start_date"]}' ";
    }
    if ($params["end_date"]) {
      $end_date = date("Y-m-d",strtotime("+1 day", strtotime($params["end_date"])));
      $end_date_query = " AND local_query.`selected_date` < '$end_date' ";
    }


    if ($params["brand_code"]) {
      $params["brand_code"] = cleaned_text($params["brand_code"]);
      $brand_code_query = " AND `cleaned_brand_code` LIKE '%{$params["brand_code"]}%' ";
    }


    $brand_query        = $params["brand"] ? "AND `brand` = '{$params["brand"]}' " : "";
    $oem_query          = $params["oem_code"] ? " AND `cleaned_oem_code` LIKE '%{$params["oem_code"]}%' " : "";

    $warehouse_query    = $params["warehouse"] && in_array($params["warehouse"],["Bakı","Gəncə"])   ? " AND `warehouse` = '".($params["warehouse"] === "Bakı" ? special_codes("cached_customer_accounts.warehouses.baku") : special_codes("cached_customer_accounts.warehouses.ganja"))."' " : "";
    $currency_query     = $params["currency"] && in_array($params["currency"],["AZN","EUR"])        ? " AND `account`.`customer_id` IN
                                                                                                        (SELECT cached_customers.`id`
                                                                                                         FROM ".local_table_name("cached_customers")." cached_customers
                                                                                                         WHERE  ".($params["currency"] === "AZN" ? " cached_customers.`code` LIKE '%AZN' " : "  cached_customers.`code` NOT LIKE '%AZN' ").") " : "";

    $details_query = "";
    if ($brand_query || $brand_code_query || $oem_query) {
      $details_query = " AND account.`invoice_id` IN (SELECT `remote_invoice_id`
                                                      FROM ".local_table_name("cached_invoices")."
                                                      WHERE `deleted_at` IS NULL
                                                      AND `quantity` != 0
                                                      $brand_query
                                                      $brand_code_query
                                                      $oem_query) ";
    }


    $customer_account_query_sql = "SELECT
                                        SUM(IFNULL((account.`entry_amount` - `account`.`exit_amount`),0)) as `entry_amount`,
                                        SUM(IFNULL((account.`entry_amount` - `account`.`exit_amount`) * account.`currency_rate`,0)) as `entry_amount_azn`,
                                        local_query.`selected_date` as `operation_date`
                                  FROM (SELECT * FROM
                                        (SELECT ADDDATE('1970-01-01',T4.I*10000 + T3.I*1000 + T2.I*100 + T1.I*10 + T0.I) SELECTED_DATE FROM
                                         (SELECT 0 I UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) T0,
                                         (SELECT 0 I UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) T1,
                                         (SELECT 0 I UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) T2,
                                         (SELECT 0 I UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) T3,
                                         (SELECT 0 I UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) T4) V
                                        WHERE selected_date BETWEEN '{$params["start_date"]}' AND '{$params["end_date"]}') as local_query
                                  LEFT JOIN ".local_table_name("cached_customer_accounts")." `account` ON account.`operation_date` = local_query.`selected_date`
                                    AND account.`deleted_at` IS NULL
                                    AND account.`type` IN ('".special_codes("cached_customer_accounts.types.sale_invoice")."','".special_codes("cached_customer_accounts.types.return")."')
                                    $details_query
                                    $warehouse_query
                                    $currency_query
                                  $start_date_query
                                  $end_date_query
                                  GROUP BY local_query.`selected_date`
                                  ORDER BY local_query.`selected_date` ASC
                                  ";

    $customer_account_query = $this->local_db->query($customer_account_query_sql);

    if (!$customer_account_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result"),
        [
          "totals" => [
            "entry_amount" => 0,
          ],
          "list" => []
        ]
      );
    }

    $customer_account       = $customer_account_query->result_array();
    $total_entry_amount = 0;
    foreach ($customer_account as $key => $item) {
      $customer_account[$key]["entry_amount"]  = (float)$item["entry_amount"] > 0 ? $item["entry_amount"] : null;
      $total_entry_amount += $customer_account[$key]["entry_amount"];
    }

    if ($params["export"]) {

      $order_template_file = 'SalesTemplate.xlsx';
      $phpExcel = PHPExcel_IOFactory::load(APPPATH ."..". DIRECTORY_SEPARATOR ."assets". DIRECTORY_SEPARATOR ."manager". DIRECTORY_SEPARATOR ."templates". DIRECTORY_SEPARATOR .$order_template_file);

      $phpExcel->getProperties()->setCreator("Sales")
                                  ->setLastModifiedBy("Sales")
                                  ->setTitle("Sales")
                                  ->setSubject("Sales")
                                  ->setDescription("Sales");

      $writer = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");
      $sheet = $phpExcel ->getActiveSheet();

      $j=2;
      $number = 0;
      foreach ($customer_account as $key => $account) {
        $number++;
        $phpExcel->setActiveSheetIndex(0)
          ->setCellValueExplicit("A$j", $number, 					PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("B$j", $account['operation_date'],  PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("I$j", round($account['entry_amount'],2), 	  PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $j++;
      }

      $file = lang("Daily_sales")."_". date('d_m_Y').' ('.$params["start_date"].' - '.$params["end_date"].').xlsx';
      $path = DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "manager" . DIRECTORY_SEPARATOR . "_temp" . DIRECTORY_SEPARATOR . "sales" . DIRECTORY_SEPARATOR . $file;
      $filepath = APPPATH . "..". $path;
      $writer->save($filepath);

      $insert_list = [
        "system_user_id" => $params["userid"],
        "data_type" => "manager_sales",
        "file_name" => $file,
        "file_type" => "xlsx",
        "params" => json_encode($params),
        "operation_date" => $params["operation_date"],
      ];

      $this->local_db->insert(local_table_name("file_export_history"),$insert_list);

      return rest_response(
        Status_codes::HTTP_OK,
        lang("Success"),
        [
          "url" => $path,
        ]
      );
    }


    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "totals" => [
          "entry_amount" => $total_entry_amount,
        ],
        "list" => $customer_account
      ]
    );
  }

  /*
  *
  */
  public function index($params) {
    escapeAllKeys($params,null,$this->local_db);

    $page_load_limit = $params["export"] ? null : "LIMIT ".($this->config->item("customer_accounts_page_load_limit") ?: 200)." ";
    $params["end_date"] = $params["end_date"] ? date("Y-m-d",strtotime("+1 day", strtotime($params["end_date"]))) : NULL;

    $start_date_query = $sub_start_date_query = $end_date_query = $sub_end_date_query = $debt_end_query = $offset_query = $brand_code_query = $oem_query = "";

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
      $params["brand_code"] = cleaned_text($params["brand_code"]);
      $brand_code_query = " AND `cleaned_brand_code` LIKE '%{$params["brand_code"]}%' ";
    }


    $offset_query       = $params["offset"] && is_numeric($params["offset"]) ? "OFFSET {$params["offset"]}" : "";
    $oem_query          = $params["oem_code"] ? " AND `cleaned_oem_code` LIKE '%{$params["oem_code"]}%' " : "";

    $brand_query        = "";
    $entry_amount_query        = " account.`entry_amount` - `account`.`exit_amount` ";
    if ($params["brand"]) {
      $brand_query          = $params["brand"] ? " AND `brand` = '{$params["brand"]}' " : "";
      $entry_amount_query   = " (SELECT SUM(detail.`total_amount`)
                                 FROM ".local_table_name("cached_invoices")." detail
                                 WHERE account.`invoice_id` = detail.`remote_invoice_id`
                                 AND detail.`brand` = '{$params["brand"]}'
                                 AND detail.`quantity` != 0
                                 AND detail.`deleted_at` IS NULL
                                 )";
    }

    $warehouse_query    = $params["warehouse"]  && in_array($params["warehouse"], ["Bakı","Gəncə"])     ? " AND `warehouse` = '".($params["warehouse"] === "Bakı" ? special_codes("cached_customer_accounts.warehouses.baku") : special_codes("cached_customer_accounts.warehouses.ganja"))."' " : "";
    $currency_query     = $params["currency"]   && in_array($params["currency"],  ["AZN","EUR"])        ? ($params["currency"] === "AZN" ? " AND cached_customers.`code` LIKE '%AZN' " : " AND cached_customers.`code` NOT LIKE '%AZN' ") : "";

    $details_query = "";
    if ($brand_query || $brand_code_query || $oem_query) {
      $details_query = " AND account.`invoice_id` IN (SELECT remote_invoice_id
                                                      FROM ".local_table_name("cached_invoices")."
                                                      WHERE `deleted_at` IS NULL
                                                      AND `quantity` != 0
                                                      $brand_query
                                                      $brand_code_query
                                                      $oem_query) ";
    }


    $customer_account_start_sql = "SELECT
                                      account.`id`,
                                      account.`remote_id`,
                                      account.`invoice_id`,
                                      account.`company_id`,
                                      account.`invoice_code`,
                                      account.`warehouse`,
                                      account.`description`,
                                      account.`comment`,
                                      $entry_amount_query as `entry_amount`,
                                      account.`currency_rate`,
                                      account.`operation_date`,
                                      cached_customers.`id` as customer_id,
                                      cached_customers.`code` as customer_code,
                                      cached_customers.`name` as customer_name";
    $customer_account_query_sql = " FROM ".local_table_name("cached_customer_accounts")." `account`
                                  LEFT JOIN ".local_table_name("cached_customers")." cached_customers ON cached_customers.`id` = `account`.`customer_id`
                                  WHERE account.`deleted_at` IS NULL
                                  AND account.`type` IN ('".special_codes("cached_customer_accounts.types.sale_invoice")."')
                                  -- AND cached_customers.`code` LIKE '211.%'
                                  $start_date_query
                                  $end_date_query
                                  $details_query
                                  $warehouse_query
                                  $currency_query
                                  ";
                                  // log_message("ERROR",$customer_account_start_sql . $customer_account_query_sql . " ORDER BY account.`operation_date` ASC, account.`remote_id` ASC $page_load_limit $offset_query ");
    $customer_account_query = $this->local_db->query($customer_account_start_sql . $customer_account_query_sql . " ORDER BY account.`operation_date` ASC, account.`remote_id` ASC $page_load_limit $offset_query ");
    $result_acount_query    = $this->local_db->query("SELECT COUNT(account.`id`) as count, SUM($entry_amount_query) as total_entry $customer_account_query_sql");

    if (!$customer_account_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result"),
        [
          "count" => 0,
          "totals" => [
            "entry" => 0,
          ],
          "list" => []
        ]
      );
    }

    $customer_account       = $customer_account_query->result_array();
    $result_acount_row      = $result_acount_query->row_array();

    $baku   = special_codes("cached_customer_accounts.warehouses.baku");
    $ganja  = special_codes("cached_customer_accounts.warehouses.ganja");

    foreach ($customer_account as $key => $item) {
      $customer_account[$key]["warehouse"]  = $item["warehouse"] ? ($item["warehouse"] === $baku ? lang("Baku") : lang("Ganja")) : NULL;
      $customer_account[$key]["entry_amount"]  = (float)$item["entry_amount"] > 0 ? $item["entry_amount"] : null;
      $customer_account[$key]["entry_amount_azn"] = number_format((round($item["currency_rate"],2) * $customer_account[$key]["entry_amount"]),2,".",",");
      $customer_account[$key]["is_invoice"] = true;
    }

    if ($params["export"]) {

      $order_template_file = 'SalesTemplate.xlsx';
      $phpExcel = PHPExcel_IOFactory::load(APPPATH ."..". DIRECTORY_SEPARATOR ."assets". DIRECTORY_SEPARATOR ."manager". DIRECTORY_SEPARATOR ."templates". DIRECTORY_SEPARATOR .$order_template_file);

      $phpExcel->getProperties()->setCreator("Sales")
                                  ->setLastModifiedBy("Sales")
                                  ->setTitle("Sales")
                                  ->setSubject("Sales")
                                  ->setDescription("Sales");

      $writer = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");
      $sheet = $phpExcel ->getActiveSheet();

      $j=2;
      $number = 0;
      foreach ($customer_account as $key => $account) {
        $number++;
        $description 			= $account['description'];
        $invoice_code		  = $account['invoice_code'];
        $customer_name		= $account['customer_name'];
        $customer_code		= $account['customer_code'];
        $warehouse		    = $account['warehouse'];
        $operation_date		= $account['operation_date'];
        $currency_rate		= round($account['currency_rate'],2);
        $entry_amount     = round($account['entry_amount'],2);

        $phpExcel->setActiveSheetIndex(0)
          ->setCellValueExplicit("A$j", $number, 					PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("B$j", $operation_date,  PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("C$j", $invoice_code,		PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("D$j", $customer_name,	  PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("E$j", $customer_code, 	PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("F$j", $description,		  PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("G$j", $warehouse,		    PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("H$j", $currency_rate,   PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("I$j", $entry_amount, 	  PHPExcel_Cell_DataType::TYPE_NUMERIC);

        $j++;
      }

      $file = lang("Sale_invoice")."_". date('d_m_Y_His').'.xlsx';
      $path = DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "manager" . DIRECTORY_SEPARATOR . "_temp" . DIRECTORY_SEPARATOR . "sales" . DIRECTORY_SEPARATOR . $file;
      $filepath = APPPATH . "..". $path;
      $writer->save($filepath);

      $insert_list = [
        "system_user_id" => $params["userid"],
        "data_type" => "manager_sales",
        "file_name" => $file,
        "file_type" => "xlsx",
        "params" => json_encode($params),
        "operation_date" => $params["operation_date"],
      ];

      $this->local_db->insert(local_table_name("file_export_history"),$insert_list);

      return rest_response(
        Status_codes::HTTP_OK,
        lang("Success"),
        [
          "url" => $path,
        ]
      );
    }


    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => $result_acount_row["count"],
        "totals" => [
          "entry" => $result_acount_row["total_entry"],
        ],
        "list" => $customer_account
      ]
    );
  }

  /*
  *
  */
  public function details($params){
    escapeAllKeys($params,null,$this->local_db);

    $sql = "SELECT
                detail.`remote_id` as `id`,
                detail.`brand`,
                detail.`brand_code`,
                detail.`OEM`,
                detail.`product_name`,
                detail.`description`,
                detail.`quantity`,
                account.`customer_id`,
                (CASE
                  WHEN account.`total_discounts` > 0.1
                    THEN detail.`discount_amount`
                  ELSE detail.`amount` END ) as `amount`,
                (CASE
                  WHEN account.`total_discounts` > 0.1
                    THEN detail.`total_discount_amount`
                  ELSE detail.`total_amount` END ) as `total_amount`,
                detail.`currency_rate`
            FROM ".local_table_name("cached_customer_accounts")." account
            LEFT JOIN ".local_table_name("cached_invoices")." detail ON account.`invoice_id` = detail.`remote_invoice_id` AND detail.`deleted_at` IS NULL AND detail.`quantity` != 0
            WHERE detail.`deleted_at` IS NULL
            AND account.`invoice_code` = '{$params["code"]}'
            AND detail.`quantity` != 0 ";

    $query = $this->local_db->query($sql);

    if (!$query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $account_info = $query->result_array();


    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $account_info
    );
  }


}
