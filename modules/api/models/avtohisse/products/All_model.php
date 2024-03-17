<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  public function list($params)
  {
    escapeAllKeys($params,null,$this->local_db);
    $limit_query = $params["limit"] ? " LIMIT {$params["limit"]}" : "";


    $products_sql = "SELECT
                      products.`id`,
                      products.`name`,
                      products.`brand_name`,
                      products.`brand_code`,
                      products.`OEM`,
                      products.`description` as `marka`,
                      products.`model`,
                      IFNULL(products.`custom_main_sale_price`,products.`sale_price`) as `sale_price`,
                      currency.`main_name` as `currency_name`,
                      products.`stock_baku` as `baku_quantity`,
                      products.`stock_ganja` as `ganja_quantity`,
                      (IFNULL(products.`stock_ganja`,0) + IFNULL(products.`stock_baku`,0)) as `whole_quantity`

                    FROM `".local_table_name("cached_products")."` products
                    LEFT JOIN `".local_table_name("cached_currencies")."` currency ON currency.`id` = IFNULL(products.`final_currency_id`,products.`currency_id`)
                      AND currency.`deleted_at` IS NULL
                    WHERE products.`deleted_at` IS NULL
                    $limit_query
                    ";
    $products_query = $this->local_db->query($products_sql);
    if(!$products_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Products not found")
      );
    }
    $products = $products_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $products
    );
  }
}
