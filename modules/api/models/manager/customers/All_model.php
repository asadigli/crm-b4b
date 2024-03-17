<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model
{

  public function __construct() {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  public function index($params) {
    escapeAllKeys($params,null,$this->local_db);
    $current_month = date('Y-m') . "-01 00:00:00";
    $page_load_limit = $params["export"] ? null : "LIMIT ".($this->config->item("customers_page_load_limit") ?: 200)." ";
    $limit_query = $page_load_limit ? " {$page_load_limit}" : "";
    $offset_query = $params["offset"] && is_numeric($params["offset"]) ?  " OFFSET {$params["offset"]}" : "";

    $inactive_customers_query = "";
    $inactive_customers_body_query = "";

    if($params["is_inactive_customers"] === STATUS_ACTIVE) {
      $params["inactive_customers"] = $params["inactive_customers"] ?: 180;

      $inactive_customers_query = " AND (customer_account_inactives.`sum_entry_amount` = 0 OR customer_account_inactives.`sum_entry_amount` IS NULL)";


      $inactive_customers_body_query = "LEFT JOIN(SELECT
                                              MAX(customer_accounts.`customer_id`) as `customer_id`,
                                              SUM(IFNULL(customer_accounts.`entry_amount`,0)) as `sum_entry_amount`
                                           FROM `".local_table_name("cached_customer_accounts")."` customer_accounts
                                           WHERE customer_accounts.`deleted_at` IS NULL
                                           AND customer_accounts.`operation_date` >= DATE_SUB(NOW(), INTERVAL {$params["inactive_customers"]} DAY)
                                           AND customer_accounts.`operation_date` <= '{$params["due_date"]}'
                                           GROUP BY customer_accounts.`customer_id`
                                           ) as customer_account_inactives ON customer_account_inactives.`customer_id` = customers.`id`
                                           ";
    }


    // $is_azn_customers = $params["is_azn_customers"] ? " AND currencies.`main_name` = '".CURRENCY_AZN."' " : "";

    $keyword_query = $params["keyword"] ? " AND (customers.`code` LIKE '%{$params["keyword"]}%'
                                                  OR customers.`name` LIKE '%{$params["keyword"]}%'
                                                    OR customers.`custom_name` LIKE '%{$params["keyword"]}%') " : "";

    $customer_id_query = $params["customer_id"] ? " AND customers.`id` = ".(int)$params["customer_id"]." " : "";

    $city_id_query = $params["city_id"] ? " AND cities.`city_id` = ".(int)$params["city_id"]." " : "";

    $currency_id_query = $params["currency_id"] ? " AND currencies.`id` = ".(int)$params["currency_id"]." " : "";

    $left_price_sql = "
    (
      SELECT
          SUM(IFNULL(customer_accounts.`entry_amount`,0))
       FROM `".local_table_name("cached_customer_accounts")."` customer_accounts
       WHERE customer_accounts.`deleted_at` IS NULL
       AND customer_accounts.`operation_date` < '{$params["due_date"]}'
       AND customer_accounts.`customer_id` = customers.`id`
    ) - (
      SELECT
          SUM(IFNULL(customer_accounts.`exit_amount`,0))
       FROM `".local_table_name("cached_customer_accounts")."` customer_accounts
       WHERE customer_accounts.`deleted_at` IS NULL
       AND customer_accounts.`operation_date` < '{$params["due_date"]}'
       AND customer_accounts.`customer_id` = customers.`id`
    )
      ";

    $debts_query= "";

    if ($params["search_by_debts"] === "is_negative_debt") {
      $debts_query = "AND $left_price_sql < 0";
    }elseif ($params["search_by_debts"] === "is_no_debt") {
      $debts_query = "AND $left_price_sql = 0";
    }elseif ($params["search_by_debts"] === "is_positive_debt") {
      $debts_query = "AND $left_price_sql > 0";
    }

    $order_by_query = "ORDER BY currencies.`main_name` DESC";
    if($params["search_by_debts"]) {
      $order_by_query = "ORDER BY $left_price_sql ";
    }

    $customer_type_query    = $params["customer_type"]  ? " AND customers.`code` LIKE '{$params["customer_type"]}%' " : "";
    $remote_is_active_query = $params["status"]         ? " AND customers.`remote_is_active` = '".($params["status"] === "active" ? "1" : "0")."' " : "";

    $customers_body_query = "FROM `".local_table_name("cached_customers")."` customers
                            LEFT JOIN `".local_table_name("cached_currencies")."` currencies ON currencies.`id` = customers.`currency_id`
                              AND currencies.`deleted_at` IS NULL
                            LEFT JOIN `".local_table_name("cities")."` cities ON cities.`city_id` = customers.`city_id`
                              AND cities.`city_delete` = '".STATUS_NO."'

                            $inactive_customers_body_query

                            WHERE customers.`deleted_at` IS NULL
                            -- AND customers.`remote_is_active` = '".STATUS_ACTIVE."'
                            AND customers.`id` IS NOT NULL
                            $keyword_query
                            $customer_id_query
                            $city_id_query
                            $currency_id_query
                            $customer_type_query
                            $remote_is_active_query
                            $debts_query

                            $inactive_customers_query

                            $order_by_query
                            ";

    $customers_sql = "SELECT
                        customers.`id`,
                        cities.`city_name`,
                        customers.`remote_id`,
                        customers.`code`,
                        customers.`max_order_limit`,
                        customers.`is_blocked`,
                        customers.`max_allowed_order_limit`,
                        customers.`has_order_limit`,
                        (SELECT
                          GROUP_CONCAT(DISTINCT CONCAT(companies.`company_name`, '-||-', companies.`company_phone`) SEPARATOR '|||') AS `customers_entry_info`
                         FROM `".local_table_name("b4b_entry_customers")."` as entry_customers
                         LEFT JOIN `".local_table_name("b4b_companies")."` as  companies ON companies.`company_id` = entry_customers.`entry_id` AND companies.`deleted_at` IS NULL
                         WHERE entry_customers.`deleted_at` IS NULL AND entry_customers.`customer_id` = customers.`id`) AS entry_info,
                        currencies.`main_name` as `currency`,
                        IFNULL(customers.`custom_name`,customers.`name`) as name,
                        customers.`description`,
                        (SELECT
                            SUM(IFNULL(customer_accounts.`entry_amount`,0))
                         FROM `".local_table_name("cached_customer_accounts")."` customer_accounts
                         WHERE customer_accounts.`deleted_at` IS NULL
                         AND customer_accounts.`operation_date` < '{$params["due_date"]}'
                         AND customer_accounts.`customer_id` = customers.`id`) as `sale_amount`,
                        (SELECT
                            SUM(IFNULL(customer_accounts.`exit_amount`,0))
                         FROM `".local_table_name("cached_customer_accounts")."` customer_accounts
                         WHERE customer_accounts.`deleted_at` IS NULL
                         AND customer_accounts.`operation_date` < '{$params["due_date"]}'
                         AND customer_accounts.`customer_id` = customers.`id`) as `payment_amount`,
                         (
                           SELECT
                               SUM(IFNULL(customer_accounts.`entry_amount`,0))
                            FROM `".local_table_name("cached_customer_accounts")."` customer_accounts
                            WHERE customer_accounts.`deleted_at` IS NULL
                            AND customer_accounts.`operation_date` < '{$params["due_date"]}'
                            AND customer_accounts.`customer_id` = customers.`id`
                         ) - (
                           SELECT
                               SUM(IFNULL(customer_accounts.`exit_amount`,0))
                            FROM `".local_table_name("cached_customer_accounts")."` customer_accounts
                            WHERE customer_accounts.`deleted_at` IS NULL
                            AND customer_accounts.`operation_date` < '{$params["due_date"]}'
                            AND customer_accounts.`customer_id` = customers.`id`
                         ) AS `left_prices`,
                        (SELECT
                          SUM(IFNULL(
                            (CASE
                            WHEN customer_accounts.`operation_date` >= '".$current_month."' THEN customer_accounts.`entry_amount`
                            ELSE 0 END),0))
                          FROM `".local_table_name("cached_customer_accounts")."` customer_accounts
                          WHERE customer_accounts.`deleted_at` IS NULL
                          AND customer_accounts.`operation_date` < '{$params["due_date"]}'
                          AND customer_accounts.`customer_id` = customers.`id`) as `monthly_sale_amount`,
                        (SELECT
                          SUM(IFNULL(
                            (CASE
                            WHEN customer_accounts.`operation_date` >= '".$current_month."' THEN customer_accounts.`exit_amount`
                            ELSE 0 END),0))
                          FROM `".local_table_name("cached_customer_accounts")."` customer_accounts
                          WHERE customer_accounts.`deleted_at` IS NULL
                          AND customer_accounts.`operation_date` < '{$params["due_date"]}'
                          AND customer_accounts.`customer_id` = customers.`id`) as `monthly_payment_amount`,
                        (SELECT
                            `operation_date`
                         FROM `".local_table_name("cached_customer_accounts")."`
                         WHERE `deleted_at` IS NULL
                         AND `customer_id` = customers.`id`
                         AND `operation_date` < '{$params["due_date"]}'
                         AND `entry_amount` > 0
                         ORDER BY `operation_date` DESC
                         LIMIT 1) as last_sale_date,
                        (SELECT
                            `operation_date`
                          FROM `".local_table_name("cached_customer_accounts")."`
                          WHERE `deleted_at` IS NULL
                          AND `customer_id` = customers.`id`
                          AND `operation_date` < '{$params["due_date"]}'
                          AND `exit_amount` > 0
                          ORDER BY `operation_date` DESC
                          LIMIT 1) as last_payment_date
                    $customers_body_query
                    $limit_query
                    $offset_query
                    ";
                    // log_message("ERROR",$customers_sql);
                    // AND customer_accounts.`type` IN (
                    //   '".special_codes("cached_customer_accounts.types.payment")."',
                    //   '".special_codes("cached_customer_accounts.types.debt_first")."',
                    //   '".special_codes("cached_customer_accounts.types.credit")."',
                    //   '".special_codes("cached_customer_accounts.types.initial")."',
                    //   '".special_codes("cached_customer_accounts.types.return")."',
                    //   '".special_codes("cached_customer_accounts.types.sale_invoice")."'
                    // )
    $customers_query = $this->local_db->query($customers_sql);
    $debt_sql_query = "SELECT
                            COUNT(1) as `count`,
                            (SELECT
                              SUM(customer_accounts.`entry_amount` - customer_accounts.`exit_amount`)
                              FROM `".local_table_name("cached_customer_accounts")."` customer_accounts
                              LEFT JOIN `".local_table_name("cached_customers")."` customers ON customers.`remote_id` = customer_accounts.`company_id`
                              AND customers.`deleted_at` IS NULL

                              LEFT JOIN `".local_table_name("cached_currencies")."` currencies ON currencies.`id` = customers.`currency_id`
                              AND currencies.`deleted_at` IS NULL
                              LEFT JOIN `".local_table_name("cities")."` cities ON cities.`city_id` = customers.`city_id`
                              AND cities.`city_delete` = '".STATUS_NO."'


                              WHERE customer_accounts.`deleted_at` IS NULL
                              AND customers.`remote_is_active` = '1'
                              $customer_type_query
                              AND customers.`id` IS NOT NULL
                              AND customer_accounts.`operation_date` < '{$params["due_date"]}'
                              $keyword_query
                              $customer_id_query
                              $city_id_query
                              $currency_id_query
                              $customer_type_query
                              $remote_is_active_query
                              $debts_query
                              $order_by_query) as `total_left_amount` " . $customers_body_query;
    $customers_count_query = $this->local_db->query($debt_sql_query);
    $customers_count_row          = $customers_count_query->row_array();
    $customers_count              = $customers_count_row["count"];
    $customers_total_left_amount  = $customers_count_row["total_left_amount"];
    // log_message("ERROR",$debt_sql_query);

    if (!$customers_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $customers = $customers_query->result_array();
    $baku = special_codes("cached_customer_accounts.warehouses.baku");
    $ganja = special_codes("cached_customer_accounts.warehouses.ganja");


    foreach ($customers as $key => $item) {
      $customers[$key]["has_order_limit"] = $item["has_order_limit"] === STATUS_ACTIVE;
      $customers[$key]["is_blocked"] = $item["is_blocked"] === STATUS_ACTIVE;

      $customers[$key]["left_amount"] = $item["sale_amount"] - $item["payment_amount"];

      $entryInfo = isset($item["entry_info"]) ? explode('|||', $item['entry_info']) : [];
      $explodedEntries = [];

      foreach ($entryInfo as $subkey => $subitem) {
        $item = explode("-||-", $subitem);
        if($item){
          $explodedEntries[] = $item;
        }
      }
      $customers[$key]["entry_info"] = $explodedEntries;
    }

    if ($params["export"]) {

      $customer_template_file = 'CustomersTemplate.xlsx';
      $phpExcel = PHPExcel_IOFactory::load(APPPATH ."..". DIRECTORY_SEPARATOR ."assets". DIRECTORY_SEPARATOR ."manager". DIRECTORY_SEPARATOR ."templates". DIRECTORY_SEPARATOR .$customer_template_file);

      $phpExcel->getProperties()->setCreator("Customers")
                                  ->setLastModifiedBy("Customers")
                                  ->setTitle("Customers")
                                  ->setSubject("Customers")
                                  ->setDescription("Customers");

      $writer = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");
      $sheet = $phpExcel ->getActiveSheet();

      $j=2;
      $number = 0;
      foreach ($customers as $key => $customer) {
        $number++;
        $name 		      	= $customer['name'];
        $code		          = $customer['code'];
        $entry_info		    = $customer['entry_info'];

        $entry_info_string = '';
        if (is_array($entry_info)) {
            foreach ($entry_info as $entry) {
                if (is_array($entry)) {
                    $entry_info_string .= implode(", ", $entry) . "\r\n";
                } else {
                    $entry_info_string .= $entry . "\r\n";
                }
            }
        } else {
            $entry_info_string = $entry_info;
          }

        $currency		      = $customer['currency'];
        $city_name		    = $customer['city_name'];
        $monthly_sale_amount		    = $customer['monthly_sale_amount'];
        $monthly_payment_amount		= $customer['monthly_payment_amount'];
        $remote_id		    = $customer['remote_id'];
        $sale_amount		  = $customer['sale_amount'];
        $payment_amount   = $customer['payment_amount'];
        $last_sale_date   = $customer["last_sale_date"];
        $last_payment_date   = $customer["last_payment_date"];
        $left_prices   = $customer["left_prices"];

        $phpExcel->setActiveSheetIndex(0)
          ->setCellValueExplicit("A$j", $number, 					PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("B$j", $name,            PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("C$j", $code,	         	PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("D$j", $entry_info_string,  	  PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("E$j", $currency, 	      PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("F$j", $city_name, 	      PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("G$j", $monthly_sale_amount,		  PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("H$j", $monthly_payment_amount,		    PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("I$j", $remote_id,       PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("J$j", $sale_amount, 	  PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("K$j", $payment_amount, 	  PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("L$j", $last_sale_date, 	  PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("M$j", $last_payment_date, 	  PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("N$j", $left_prices, 	  PHPExcel_Cell_DataType::TYPE_NUMERIC);

        $j++;
      }

      $file = lang("Customers")."_". date('d_m_Y_His').'.xlsx';
      $path = DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "manager" . DIRECTORY_SEPARATOR . "_temp" . DIRECTORY_SEPARATOR . "customers" . DIRECTORY_SEPARATOR . $file;
      $filepath = APPPATH . "..". $path;
      $writer->save($filepath);

      $insert_list = [
        "system_user_id" => $params["userid"],
        "data_type" => "manager_customers",
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
        "total_left_amount" => $customers_total_left_amount,
        "count" => $customers_count,
        "list" => $customers
      ]
    );
  }

  public function list($params) {
    $customers_sql =  "SELECT
                  customers.`id`,
                  customers.`remote_id`,
                  customers.`code`,
                  IFNULL(customers.`custom_name`,customers.`name`) as name,
                  customers.`description`
            FROM `".local_table_name("cached_customers")."` `customers`
            WHERE deleted_at IS NULL";

    $customers_query = $this->local_db->query($customers_sql);

    if (!$customers_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $customers = $customers_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $customers
    );
  }

  public function cityList($params) {
    $cities_sql =  "SELECT
                  cities.`city_id`,
                  cities.`city_name`
            FROM `".local_table_name("cities")."` AS cities
            WHERE deleted_at IS NULL";

    $cities_query = $this->local_db->query($cities_sql);

    if (!$cities_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $cities = $cities_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $cities
    );
  }

}
