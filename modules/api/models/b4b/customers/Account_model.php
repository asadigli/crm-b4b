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
                               customers.`name`,
                               customers.`currency_id`
                           FROM `".local_table_name("cached_customers")."` customers
                           WHERE customers.`deleted_at` IS NULL
                           AND customers.`remote_id` = {$params["remoteb4buserid"]}";

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
    $limit_query = $params["is_excel_export"] === STATUS_ACTIVE ? "" : " LIMIT $page_load_limit";
    $offset_query = $params["is_excel_export"] === STATUS_ACTIVE ? "" : ($params["offset"] && is_numeric($params["offset"]) ? "OFFSET {$params["offset"]}" : "");

    $params["end_date"] = $params["end_date"] ? date("Y-m-d",strtotime("+1 day", strtotime($params["end_date"]))) : NULL;

    $start_date_query = "";
    $debt_end_query   = "";
    $end_date_query   = "";

    $brand_code_query = "";
    $oem_query        = "";

    if ($params["start_date"]) {
      $start_date_query = " AND account.`operation_date` >= '{$params["start_date"]}' ";
    }
    if ($params["end_date"]) {
      $end_date_query = " AND account.`operation_date` < '{$params["end_date"]}' ";
      $debt_end_query = " AND `operation_date` < '{$params["end_date"]}'";
    }


    if ($params["brand_code"]) {
      $brand_code = cleaned_text($params["brand_code"]);
      $brand_code_query = " AND `cleaned_brand_code` LIKE '%{$brand_code}%' ";
    }

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

    $customer_account_sql_query = "SELECT
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
                                              SUM(".($is_converted_to_azn ? "`converted_exit_amount`" : "`exit_amount`") ."),'|||',SUM(CASE WHEN CONCAT(UNIX_TIMESTAMP(sub_account.`operation_date`),'.',sub_account.`remote_id`) <= CONCAT(UNIX_TIMESTAMP(account.`operation_date`),'.',account.`remote_id`)
                                              THEN sub_account.".($is_converted_to_azn ? "`converted_entry_amount`" : "`entry_amount`")." - sub_account.".($is_converted_to_azn ? "`converted_exit_amount`" : "`exit_amount`") ." ELSE 0 END))
                                       FROM `".local_table_name("cached_customer_accounts")."` sub_account
                                       WHERE sub_account.`deleted_at` IS NULL
                                       AND sub_account.`company_id` = {$params["customer_id"]}) as left_amount,
                                      account.`operation_date`,
                                      account.`report_tr_code` as tr_code,
                                      IFNULL(cash.`custom_name`,cash.`name`) as payment_type
                                  FROM ".local_table_name("cached_customer_accounts")." `account`
                                  LEFT JOIN ".local_table_name("cached_cashboxes")." cash ON cash.id = account.cashbox_id
                                  WHERE account.`deleted_at` IS NULL
                                  AND account.`company_id` = {$params["customer_id"]}
                                  $start_date_query
                                  $end_date_query
                                  $details_query
                                  ORDER BY account.`operation_date` ASC, account.`remote_id` ASC
                                  $limit_query
                                  $offset_query ";

    $customer_account_query = $this->local_db->query($customer_account_sql_query);
    $count_query = $this->local_db->query("SELECT FOUND_ROWS() as count");
    $count = $count_query->row_array();

    $customer_total_left_amount_sql_query = "SELECT
                                                 SUM(". ($is_converted_to_azn ? "account.`converted_entry_amount`" : "account.`entry_amount`") ." -
                                                 ". ($is_converted_to_azn ? "account.`converted_exit_amount`" : "account.`exit_amount`") .") as `left_amount`
                                             FROM ".local_table_name("cached_customer_accounts")." `account`
                                             WHERE account.`deleted_at` IS NULL
                                             AND account.`company_id` = {$params["customer_id"]}
                                             $details_query";

    $customer_total_left_amount_query  = $this->local_db->query($customer_total_left_amount_sql_query);
    $customer_total_left_amount        = $customer_total_left_amount_query->row_array()["left_amount"];

    if (!$customer_account_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result"),
        [
          "total_left_amount" => $customer_total_left_amount,
          "count"             => 0,
          "total_sum"         => 0,
          "total_entry"       => 0,
          "total_exit"        => 0,
          "list"              => []
        ]
      );
    }



    $total_entry      = $total_exit = 0;

    $customer_account = $customer_account_query->result_array();

    $baku = special_codes("cached_customer_accounts.warehouses.baku");
    $ganja = special_codes("cached_customer_accounts.warehouses.ganja");
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
      $customer_account[$key]["exit_amount"]   = (float)$item["exit_amount"] > 0 ? $item["exit_amount"] : null;
      $customer_account[$key]["left_amount"]   = isset($amounts[2]) ? $amounts[2] : 0;


      $customer_account[$key]["type"] = $type;
      $customer_account[$key]["is_invoice"] = in_array((int)$item["tr_code"],[33,38]);
      unset($customer_account[$key]["tr_code"]);
    }

    $total_sum  = $total_entry - $total_exit;

    if($params["is_excel_export"] === STATUS_ACTIVE) {
      $account_template = "b4bAccountListTemplate.xlsx";

      $phpExcel = PHPExcel_IOFactory::load(APPPATH ."..". DIRECTORY_SEPARATOR ."assets". DIRECTORY_SEPARATOR ."b4b". DIRECTORY_SEPARATOR ."templates". DIRECTORY_SEPARATOR .$account_template);
      $phpExcel->getProperties()->setCreator("AccountList")
                                  ->setLastModifiedBy("AccountList")
                                  ->setTitle("AccountList")
                                  ->setSubject("AccountList")
                                  ->setDescription("AccountList");
      $writer = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");
      $sheet = $phpExcel ->getActiveSheet();

      $j = 13;
      $no = 0;
      foreach ($customer_account as $key => $item) {
        $no++;
        $operation_date           = $item["operation_date"];
        $invoice_code             = $item["invoice_code"];
        $type                     = $item["type"];
        $payment_type             = $item["payment_type"];
        $warehouse                = $item["warehouse"];
        $currency_rate            = $item["currency_rate"];
        $exit_amount              = $item["exit_amount"];
        $entry_amount             = $item["entry_amount"];
        $left_amount              = $item["left_amount"];


        $phpExcel->setActiveSheetIndex(0)
          ->setCellValueExplicit("A$j", $no, 				      PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("B$j", $operation_date,	PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("C$j", $invoice_code,		PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("D$j", $type,	          PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("E$j", $payment_type, 		PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("F$j", $warehouse,		    PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("G$j", $currency_rate,   PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("H$j", $entry_amount,    PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("I$j", $exit_amount,     PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("J$j", $left_amount,   	PHPExcel_Cell_DataType::TYPE_NUMERIC);

        $j++;
      }

      $company_name = $check_customer["name"];
      $start_date = $params["start_date"];
      $end_date = $params["end_date"];
      $brand_code = $params["brand_code"];
      $brand = $params["brand"];
      $oem_code = $params["oem_code"];

      $result = count($customer_account);
      $left_amount = $total_sum;
      $total_entry_amount = $total_entry;
      $total_exit_amount = $total_exit;
      $export_date = now();

      $phpExcel->setActiveSheetIndex(0)
        ->setCellValueExplicit("C4",  $company_name,             PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("C6",  $start_date,               PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("C7",  $end_date,                 PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("C8",  $brand_code,               PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("C9",  $brand,                    PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("C10", $oem_code,                 PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("F6",  $result,                   PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("F7",  $left_amount,              PHPExcel_Cell_DataType::TYPE_NUMERIC)
        ->setCellValueExplicit("F8",  $total_entry_amount,       PHPExcel_Cell_DataType::TYPE_NUMERIC)
        ->setCellValueExplicit("F9",  $total_exit_amount,        PHPExcel_Cell_DataType::TYPE_NUMERIC)
        ->setCellValueExplicit("F10",  $export_date,             PHPExcel_Cell_DataType::TYPE_STRING);

      $file = "B4B-Cari-Hesab-" . date('YmdHis').'.xlsx';
      $path = DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "b4b" . DIRECTORY_SEPARATOR . "_temp" . DIRECTORY_SEPARATOR . "accounts" . DIRECTORY_SEPARATOR . $file;
      $filepath = APPPATH . "..". $path;
      $writer->save($filepath);

      $insert_list = [
        "entry_id" => $params["b4buserid"],
        "key" => "accounts" . ID_SPRTR . $check_customer["id"],
        "params" => json_encode([
          // "company_name" => $check_customer["name"];
          "start_date" => $params["start_date"],
          "end_date" => $params["end_date"],
          "brand_code" => $params["brand_code"],
          "brand" => $params["brand"],
          "oem_code" => $params["oem_code"],
        ]),
        "name" => $file,
        "reference_count" => $no,
      ];

      $this->local_db->insert(local_table_name("b4b_exports"),$insert_list);
      $insert_id = $this->local_db->insert_id();
      $code = str_pad($insert_id, 8, '0', STR_PAD_LEFT);
      $this->local_db->where("id",$insert_id)->update(local_table_name("b4b_exports"),["code" => $code]);

      return rest_response(
        Status_codes::HTTP_IM_USED,
        lang("Success"),
        ["excel_path" => $path]
      );
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "total_left_amount"   => $customer_total_left_amount,
        "count"               => $count["count"],
        "total_sum"           => $total_sum,
        "total_entry"         => $total_entry,
        "total_exit"          => $total_exit,
        "list"                => $customer_account
      ]
    );
  }


  /*
  *
  *
  */
  public function details($params){
    escapeAllKeys($params,null,$this->local_db);

    $check_customer_sql = "SELECT
                                customers.`id`,
                                customers.`currency_id`
                              FROM `".local_table_name("cached_customers")."` customers
                              WHERE customers.`deleted_at` IS NULL
                              AND customers.`remote_id` = {$params["remoteb4buserid"]}
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
                account.`total_discounts`,
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
            AND detail.`quantity` != 0
            AND account.`invoice_code` = '{$params["code"]}'
            AND account.`company_id` = {$params["customer_id"]} ";

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
}
