<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  public function index($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $is_developer_query = $params["is_dev"] ? " AND product_import.`is_dev` IN ('0','1') " : " AND product_import.`is_dev` = '0' ";
    $end_date_query = "";

    if ($params["end_date"]) {
      $params["end_date"] = date('Y-m-d H:i:s', strtotime($params["end_date"] . ' +1 day'));
      $end_date_query = " AND product_import.`created_at` <= '{$params['end_date']}'";
    }

    $start_date_query = $params["start_date"] ? " AND product_import.`created_at` > '{$params['start_date']}'" : "";
    $keyword_query = $params["keyword"] ? "AND product_import.`description` LIKE '%{$params["keyword"]}%'
                                            OR product_import.`creator_name` LIKE '%{$params["keyword"]}%'
                                            OR product_import.`brand_name` LIKE '%{$params["keyword"]}%'
                                            OR product_import.`result_count` LIKE '%{$params["keyword"]}%'
                                            " : "";
    $brand_query = $params["brand_id"] ? " AND product_brand.`id` = {$params["brand_id"]}" : "";

    $products_imports_sql = "SELECT
                              product_import.`id`,
                              product_import.`description`,
                              product_import.`file_name`,
                              product_import.`brand_name`,
                              product_import.`result_count`,
                              product_import.`brand_price_rate` * 100 as `brand_price_rate`,
                              product_import.`operation_date`,
                              product_import.`creator_name`
                            FROM `".local_table_name("product_import_history")."` product_import
                            LEFT JOIN `".local_table_name("cached_product_brands")."` product_brand ON product_brand.`id` = product_import.`brand_id`
                              AND product_brand.`deleted_at` IS NULL
                            WHERE product_import.`deleted_at` IS NULL
                            $is_developer_query
                            $start_date_query
                            $end_date_query
                            $brand_query
                            $keyword_query
                            ";
                            // var_dump($products_imports_sql);die;
    $products_imports_query = $this->local_db->query($products_imports_sql);
    if(!$products_imports_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No information")
      );
    }

    $products_imports = $products_imports_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => count($products_imports),
        "list" => $products_imports
      ]
    );
  }

  public function delete($params){
    escapeAllKeys($params,null,$this->local_db);

    $product_imports_sql = "SELECT
                              product_import.`id`
                            FROM `".local_table_name("product_import_history")."` product_import
                            WHERE product_import.`deleted_at` IS NULL
                            AND product_import.`id` = '{$params["id"]}' ";

    $product_imports_query = $this->local_db->query($product_imports_sql);
    if(!$product_imports_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No information")
      );
    }

    $products_sql = "SELECT
                      products.`id`,
                      products.`name`
                    FROM `".local_table_name("cached_products")."` products
                    WHERE products.`deleted_at` IS NULL
                    AND products.`import_history_id` = {$params["id"]}";

    $products_query = $this->local_db->query($products_sql);

    if (!$products_query->num_rows()) {

      $this->local_db->where("id", $params["id"]);
      $this->local_db->update(local_table_name("product_import_history"), ["deleted_at" => $params["now"]]);

      return rest_response(
        Status_codes::HTTP_OK,
        lang("Product import deleted")
      );
    }

    $products = $products_query->result_array();

    foreach ($products as $key => $item) {
      $update_list[] = [
        "id" => $item["id"],
        "deleted_at" => $params["now"],
      ];
    }

    $this->local_db->update_batch(local_table_name("cached_products"), $update_list, "id");

    $this->local_db->where("id", $params["id"]);
    $this->local_db->update(local_table_name("product_import_history"), ["deleted_at" => $params["now"]]);

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Product import deleted")
    );

  }
}
