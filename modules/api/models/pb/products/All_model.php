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

    $limit_query = $params["p_limit"] ? " LIMIT {$params["p_limit"]}" : NULL;
    $offset_query = $params["p_offset"] ? " OFFSET {$params["p_offset"]}" : NULL;

    $product_carbrand_joints_list_query = "";
    $car_brand_query = "";
    $brand_query = "";

    if($params["carbrand"]) {
      $check_carbrand_sql = "SELECT `id`
                              FROM `".local_table_name("cached_carbrands")."`
                            WHERE `deleted_at` IS NULL
                            AND `name` = '{$params["carbrand"]}'
                            ";
      $check_carbrand_query = $this->local_db->query($check_carbrand_sql);
      $check_carbrand = $check_carbrand_query->row_array();
      $carbrand_id = isset($check_carbrand["id"]) ? $check_carbrand["id"] : null;

      if($carbrand_id) {
        $product_carbrand_joints_query = $this->local_db->query("SELECT
                                                            `product_id`
                                                          FROM `".local_table_name("product_carbrand_joints")."`
                                                          WHERE `deleted_at` IS NULL
                                                          AND `carbrand_id` = {$carbrand_id}
                                                          ");
        $product_carbrand_joints = $product_carbrand_joints_query->result_array();

        $product_carbrand_joints_ids = [];
        foreach ($product_carbrand_joints as $key => $item) {
          if($item["product_id"]){
            $product_carbrand_joints_ids[] = $item["product_id"];
          }
        }

        if($product_carbrand_joints_ids) {
          $product_carbrand_joints_list_query = " AND products.`id` IN (". implode(",",$product_carbrand_joints_ids) .")";
        }

        $car_brand_query = " AND products.`carbrand_id` = {$carbrand_id}";
      }
    }

    if($params["brand"]) {
      $check_brand_sql = "SELECT `id`
                              FROM `".local_table_name("cached_product_brands")."`
                            WHERE `deleted_at` IS NULL
                            AND `name` = '{$params["brand"]}'
                            ";
      $check_brand_query = $this->local_db->query($check_brand_sql);
      $check_brand = $check_brand_query->row_array();
      $brand_id = isset($check_brand["id"]) ? $check_brand["id"] : null;

      if($brand_id) {
        $brand_query = " AND products.`brand_id` = {$brand_id}";
      }
    }

    $products_sql = "SELECT
                        products.`name`,
                        products.`brand_name` as `brand`,
                        products.`brand_code`,
                        products.`OEM`,
                        products.`stock_baku`,
                        products.`stock_ganja`,
                        products.`model`,
                        products.`resource`,
                        products.`description`,
                        IFNULL(products.`final_currency_id`,products.`currency_id`) as `final_currency_id`,
                        IFNULL(products.`custom_main_sale_price`,products.`sale_price`) as `price`,
                        currencies.`main_name` as `currency`,
                        products.`is_b4b_price_hidden`
                        FROM `".local_table_name("cached_products")."` products
                        LEFT JOIN `".local_table_name("cached_currencies")."` currencies ON currencies.`id` = IFNULL(products.`final_currency_id`,products.`currency_id`)
                          AND currencies.`deleted_at` IS NULL
                        WHERE products.`deleted_at` IS NULL
                        AND products.`remote_is_active` = '".STATUS_DEACTIVE."'
                        AND products.`resource` = '".special_codes("products.resources.ava")."'
                        $car_brand_query
                        $brand_query
                        $product_carbrand_joints_list_query
                        $limit_query
                        $offset_query
                      ";
                      // var_dump($products_sql);die;
    $products_query = $this->local_db->query($products_sql);
    if(!$products_query){
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        "ERROR - 342341"
      );
    }

    if(!$products_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Products not found")
      );
    }


    $products = $products_query->result_array();
    foreach ($products as $key => $item) {
      $products[$key]["stock_code"] = $item["brand_code"];

      $stock_show = false;
      $stock_baku = stock_visibility($item["resource"],$stock_show,$item["stock_baku"]);
      $stock_ganja = stock_visibility($item["resource"],$stock_show,$item["stock_ganja"]);
      $products[$key]["stock"] = [
        "baku" => isset($stock_baku["title"]) ? $stock_baku["title"] : NULL,
        "ganja" => isset($stock_ganja["title"]) ? $stock_ganja["title"] : NULL,
      ];

      if($item["is_b4b_price_hidden"] === STATUS_ACTIVE) {
        $products[$key]["sale_price"] = NULL;
        // $products[$key]["currency_name"] = NULL;
      }

      unset($products[$key]["final_currency_id"]);
      unset($products[$key]["is_b4b_price_hidden"]);
      unset($products[$key]["brand_code"]);
      unset($products[$key]["stock_baku"]);
      unset($products[$key]["stock_ganja"]);
      unset($products[$key]["brand_code"]);
      unset($products[$key]["resource"]);
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => count($products),
        "list" => $products
      ]
    );
  }
}
