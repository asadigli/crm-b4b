<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  public function index($params){
    escapeAllKeys($params,null,$this->local_db);

    $keyword_sql = "";

    if($params["keyword"]){
      $keyword_sql = "WHERE discount_packages.`name` LIKE '%{$params["keyword"]}%' ";
    }


    $discount_packages_sql = "SELECT
                              discount_packages.`id`,
                              discount_packages.`code`,
                              discount_packages.`name`,
                              discount_packages.`discount_rate`,
                              (SELECT SUM((products.`stock_baku`+products.`stock_ganja`) * products.`buying_price`)
                              FROM `".local_table_name("cached_products")."` products
                              WHERE products.`deleted_at` IS NULL
                              AND discount_packages.`deleted_at` IS NULL
                              AND products.`currency_id` = '".CURRENCY_EUR_ID."'
                              AND products.`discount_package_id` = discount_packages.`id`) as `eur_last_purchase_price`,

                              (SELECT currency.`main_name`
                              FROM `".local_table_name("cached_currencies")."` currency
                              WHERE currency.`deleted_at` IS NULL
                              AND currency.`id` = '".CURRENCY_EUR_ID."') as `eur_last_purchase_currency`,

                              (SELECT SUM((products.`stock_baku`+products.`stock_ganja`) * products.`buying_price`)
                              FROM `".local_table_name("cached_products")."` products
                              WHERE products.`deleted_at` IS NULL
                              AND discount_packages.`deleted_at` IS NULL
                              AND products.`currency_id` = '".CURRENCY_AZN_ID."'
                              AND products.`discount_package_id` = discount_packages.`id`) as `azn_last_purchase_price`,

                              (SELECT currency.`main_name`
                              FROM `".local_table_name("cached_currencies")."` currency
                              WHERE currency.`deleted_at` IS NULL
                              AND currency.`id` = '".CURRENCY_AZN_ID."') as `azn_last_purchase_currency`,

                              (SELECT COUNT(products.`id`)
                              FROM `".local_table_name("cached_products")."` products
                              WHERE products.`deleted_at` IS NULL
                              AND products.`discount_package_id` = discount_packages.`id`) as `product_count`,

                              discount_packages.`params`,
                              discount_packages.`operation_date`,
                              discount_packages.`deleted_at`
                            FROM `".local_table_name("product_dicsount_packages")."` discount_packages
                            $keyword_sql
                            ORDER BY discount_packages.`operation_date` DESC
                            ";
                            // var_dump($discount_packages_sql);die;
    $discount_packages_query = $this->local_db->query($discount_packages_sql);
    if(!$discount_packages_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No information")
      );
    }

    $discount_packages = $discount_packages_query->result_array();

    foreach ($discount_packages as $key => $item) {
      $discount_packages[$key]["params"] = json_decode($item["params"]);
    }


    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => count($discount_packages),
        "list" => $discount_packages
      ]
    );
  }

  public function delete($params){
    $check_package_sql = "SELECT
                            discount_packages.`id`,
                            discount_packages.`params`
                          FROM `".local_table_name("product_dicsount_packages")."` discount_packages
                          WHERE discount_packages.`deleted_at` IS NULL
                          AND discount_packages.`id` = {$params["id"]}";
    $check_package_query = $this->local_db->query($check_package_sql);

    if (!$check_package_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No information")
      );
    }

    $check_package = $check_package_query->result_array();

    $products_sql = "SELECT
                      products.`id`,
                      products.`name`
                    FROM `".local_table_name("cached_products")."` products
                    WHERE products.`deleted_at` IS NULL
                    AND products.`discount_package_id` = {$params["id"]}";
    $products_query = $this->local_db->query($products_sql);

    if (!$products_query->num_rows()) {

      $this->local_db->where("id",$params["id"]);
      $this->local_db->update(local_table_name("product_dicsount_packages"),["deleted_at" => $params["now"]]);

      return rest_response(
        Status_codes::HTTP_OK,
        lang("Discount package deleted")
      );
    }

    $products = $products_query->result_array();

    foreach ($products as $key => $item) {
      $update_list[] = [
        "id" => $item["id"],
        "has_discount" => STATUS_DEACTIVE,
        "discount_package_id" => null
      ];
    }

    $this->local_db->update_batch(local_table_name("cached_products"),$update_list,"id");

    $this->local_db->where("id",$params["id"]);
    $this->local_db->update(local_table_name("product_dicsount_packages"),["deleted_at" => $params["now"]]);



    return rest_response(
      Status_codes::HTTP_OK,
      lang("Discount package deleted")
    );
  }
}
