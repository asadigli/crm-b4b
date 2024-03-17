<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit','1200006M');
ini_set('max_execution_time', 900); //300 seconds = 5 minutes

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }


  public function index($params){
    escapeAllKeys($params,null,$this->local_db);

    $keyword_sql = "";
    $start_date_sql = "";
    $end_date_sql = "";

    if ($params["end_date"]) {
      $params["end_date"] = date('Y-m-d H:i:s', strtotime($params["end_date"] . ' +1 day'));
      $end_date_sql = " AND price_requests.`created_at` <= '{$params['end_date']}'";
    }

    $start_date_sql = $params["start_date"] ? " AND price_requests.`created_at` > '{$params['start_date']}'" : "";

    if($params["keyword"]){
      $keyword_sql = "AND price_requests.`company_name` LIKE '%{$params["keyword"]}%'
                      OR  products.`name` LIKE '%{$params["keyword"]}%'
                      OR  products.`OEM` LIKE '%{$params["keyword"]}%'
                      OR  products.`brand_code` LIKE '%{$params["keyword"]}%' ";
    }

    $brand_sql = $params["brand_id"] ? " AND products.`brand_id` = {$params["brand_id"]}" : "";
    $entry_sql = $params["entry_id"] ? " AND price_requests.`entry_id` = {$params["entry_id"]}" : "";



    $price_offers_sql = "SELECT
                            price_requests.`id`,
                            price_requests.`company_name`,
                            price_requests.`price`,
                            price_requests.`operation_date`,
                            customers.`name` as customer_name,
                            products.`name` as products_name,
                            products.`brand_code`,
                            products.`OEM`,
                            products.`currency`,
                            IFNULL(products.`final_currency_id`,products.`currency_id`) as `final_currency_id`,
                            IFNULL(products.`custom_main_sale_price`,products.`sale_price`) as `sale_price`,
                            products.`has_discount`,
                            products.`discount_price`,
                            (products.`stock_baku` + products.`stock_ganja`) as total_stock,
                            brands.`name` as brand_name,
                            companies.`company_name` as entry_name
                          FROM `".local_table_name("b4b_entry_price_requests")."` AS  price_requests
                          LEFT JOIN `".local_table_name("cached_products")."` AS products
                               ON products.`id` = price_requests.`product_id`
                               AND products.`deleted_at` IS NULL
                          LEFT JOIN `".local_table_name("b4b_companies")."` AS companies
                               ON companies.`company_id` = price_requests.`entry_id`
                               AND companies.`deleted_at` IS NULL
                          LEFT JOIN `".local_table_name("cached_product_brands")."` brands
                               ON brands.`id` = products.`brand_id`
                               AND brands.`deleted_at` IS NULL
                          LEFT JOIN `".local_table_name("cached_customers")."` AS customers
                               ON customers.`remote_id` = price_requests.`customer_id`
                          WHERE price_requests.`deleted_at` IS NULL
                          $start_date_sql
                          $end_date_sql
                          $brand_sql
                          $entry_sql
                          $keyword_sql
                          ORDER BY price_requests.`operation_date` DESC
                          ";

    $price_offers_query = $this->local_db->query($price_offers_sql);
    if(!$price_offers_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("No data found")
      );
    }

    $price_offers = $price_offers_query->result_array();

    $res_currencies = get_currency();
    $currencies_list = [];
    $currencies_name_list = [];

    foreach ($res_currencies as $key => $item) {
      $currencies_list[$item["id"]] = $item;
      $currencies_name_list[$item["main_name"]] = $item;
    }

    foreach ($price_offers as $key => $item) {
      $price_offers[$key]["converted_currency"] = CURRENCY_AZN;
      $price_offers[$key]["discount_price"] = isset($price_offers[$key]["has_discount"]) &&
                                          isset($price_offers[$key]["discount_price"]) &&
                                          ($price_offers[$key]["has_discount"] === STATUS_ACTIVE) ? $price_offers[$key]["discount_price"] : NULL;

      $price_offers[$key]["converted_discount_price"] = isset($price_offers[$key]["has_discount"]) &&
                                                    isset($price_offers[$key]["discount_price"]) &&
                                                    ($price_offers[$key]["has_discount"] === STATUS_ACTIVE) ?
                                                    (isset($currencies_name_list[$item["currency"]]) ?
                                                    $item["discount_price"] * $currencies_name_list[$item["currency"]]["value"] :
                                                    NULL) : NULL;

      if(isset($item["final_currency_id"])) {
        $price_offers[$key]["currency"] = isset($currencies_list[$item["final_currency_id"]]) ? $currencies_list[$item["final_currency_id"]]["main_name"] : NULL;
        $price_offers[$key]["converted_sale_price"] = isset($currencies_list[$item["final_currency_id"]]) ?
                                                  $item["sale_price"] * $currencies_list[$item["final_currency_id"]]["value"] :
                                                  NULL;
      } else {
        $price_offers[$key]["converted_sale_price"] = isset($currencies_name_list[$item["currency"]]) ?
                                                  $item["sale_price"] * $currencies_name_list[$item["currency"]]["value"] :
                                                  NULL;

        // $price_offers[$key]["converted_discount_price"] = isset($price_offers[$key]["has_discount"]) &&
        //                                               isset($price_offers[$key]["discount_price"]) &&
        //                                               ($products[$key]["has_discount"] === STATUS_ACTIVE) ?
        //                                               (isset($currencies_name_list[$item["currency"]]) ?
        //                                               $item["discount_price"] * $currencies_name_list[$item["currency"]]["value"] :
        //                                               NULL) : NULL;
      }
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success")
      ,
      [
        "count" => count($price_offers),
        "list" => $price_offers,
      ]
    );
  }

}
