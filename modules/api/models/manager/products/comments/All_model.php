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
      $end_date_sql = " AND comments.`created_at` <= '{$params['end_date']}'";
    }

    $start_date_sql = $params["start_date"] ? " AND comments.`created_at` > '{$params['start_date']}'" : "";

    if($params["keyword"]){
      $keyword_sql = "AND comments.`entry_product_comment` LIKE '%{$params["keyword"]}%'
                      OR  products.`name` LIKE '%{$params["keyword"]}%'
                      OR  products.`OEM` LIKE '%{$params["keyword"]}%'
                      OR  products.`brand_code` LIKE '%{$params["keyword"]}%' ";
    }

    $brand_sql = $params["brand_id"] ? " AND products.`brand_id` = {$params["brand_id"]}" : "";
    $entry_sql = $params["entry_id"] ? " AND comments.`entry_id` = {$params["entry_id"]}" : "";



    $comments_sql = "SELECT
                            comments.`id`,
                            comments.`entry_product_comment`,
                            comments.`operation_date`,
                            products.`name` as products_name,
                            products.`brand_code`,
                            products.`OEM`,
                            products.`sale_price`,
                            products.`currency`,
                            (products.`stock_baku` + products.`stock_ganja`) as total_stock,
                            brands.`name` as brand_name,
                            companies.`company_name`
                          FROM `".local_table_name("b4b_products_comments")."` AS  comments
                          LEFT JOIN `".local_table_name("cached_products")."` AS products
                               ON products.`id` = comments.`product_id`
                               AND products.`deleted_at` IS NULL
                          LEFT JOIN `".local_table_name("b4b_companies")."` AS companies
                               ON companies.`company_id` = comments.`entry_id`
                               AND companies.`deleted_at` IS NULL
                          LEFT JOIN `".local_table_name("cached_product_brands")."` brands
                               ON brands.`id` = products.`brand_id`
                               AND brands.`deleted_at` IS NULL
                          WHERE comments.`deleted_at` IS NULL
                          $start_date_sql
                          $end_date_sql
                          $brand_sql
                          $entry_sql
                          $keyword_sql
                          ORDER BY comments.`operation_date` DESC
                          ";

    $comments_query = $this->local_db->query($comments_sql);
    if(!$comments_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("No data found")
      );
    }

    $comments = $comments_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success")
      ,
      [
        "count" => count($comments),
        "list" => $comments,
      ]
    );
  }

  public function entriesList($params){
    escapeAllKeys($params,null,$this->local_db);


    $entries_sql = "SELECT
                          companies.`company_id` as id,
                          companies.`company_name` as name
                          FROM `".local_table_name("b4b_companies")."` AS companies
                          WHERE companies.`deleted_at` IS NULL
                          ";

    $entries_query = $this->local_db->query($entries_sql);
    if(!$entries_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("No entry found")
      );
    }

    $entries = $entries_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success")
      ,
      [
        "count" => count($entries),
        "list" => $entries,
      ]
    );
  }

}
