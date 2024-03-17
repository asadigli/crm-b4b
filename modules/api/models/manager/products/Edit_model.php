<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  public function editPrice($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $check_product_sql = "SELECT
                            products.`id`,
                            products.`currency_id`,
                            IFNULL(products.`final_currency_id`,products.`currency_id`) as `final_currency_id`
                          FROM `".local_table_name("cached_products")."` products
                          WHERE products.`deleted_at` IS NULL
                          AND products.`id` = {$params["id"]}
                          ";
    $check_product_query = $this->local_db->query($check_product_sql);
    if(!$check_product_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Product not found")
      );
    }
    $check_product = $check_product_query->row_array();

    $res_currencies = get_currency();
    $currencies_list = [];
    $currencies_name_list = [];
    foreach ($res_currencies as $key => $item) {
      $currencies_list[$item["main_name"]] = $item;
      $currencies_list[$item["id"]] = $item;
    }

    if(!isset($currencies_list[CURRENCY_AZN]["id"])) {
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Currency AZN is not found")
      );
    }

    if(!isset($currencies_list[CURRENCY_EUR]["id"])) {
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Currency AZN is not found")
      );
    }

    $update_list = [
      "custom_main_sale_price" => $params["custom_main_sale_price"],
      "final_currency_id" => $currencies_list[CURRENCY_AZN]["id"],
    ];

    if(is_null($params["custom_main_sale_price"])) {
      $update_list["final_currency_id"] = $check_product["currency_id"];
    }

    // if(isset($currencies_list[$check_product["final_currency_id"]]) && $currencies_list[$check_product["final_currency_id"]]["main_name"] !== CURRENCY_AZN) {
    //   $update_list["final_currency_id"] = $currencies_list[CURRENCY_AZN]["id"];
    // }

    $this->local_db->where("id", $params["id"]);
    $this->local_db->update(local_table_name("cached_products"),$update_list);



    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Success")
    );
  }

  public function discountPrice($params){
    escapeAllKeys($params,null,$this->local_db);

    $check_product_sql = "SELECT
                            product.`id`,
                            product.`custom_main_sale_price`,
                            product.`sale_price`
                          FROM `".local_table_name("cached_products")."` product
                          WHERE product.`deleted_at` IS NULL
                          AND product.`id` = {$params["id"]} ";

    $check_product_query = $this->local_db->query($check_product_sql);

    if(!$check_product_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Product not found")
      );
    }

    $check_product = $check_product_query->row_array();

    $price = $check_product["custom_main_sale_price"] ?: $check_product["sale_price"];;

    $discount_rate = $price ? ((((float)$price - $params["discount_price"])/(float)$price)*100) : 0;

    $update_list = [
      "discount_price" => $params["discount_price"],
      "discount_rate" => $discount_rate,
      "has_discount" => $params["has_discount"],
    ];

    $this->local_db->where("id", $params["id"]);
    $this->local_db->update(local_table_name("cached_products"),$update_list);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Success")
    );
  }

  public function hidePrice($params){
    escapeAllKeys($params,null,$this->local_db);

    $check_product_sql = "SELECT
                            product.`id`
                          FROM `".local_table_name("cached_products")."` product
                          WHERE product.`deleted_at` IS NULL
                          AND product.`id` = {$params["id"]} ";

    $check_product_query = $this->local_db->query($check_product_sql);

    if(!$check_product_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Product not found")
      );
    }

    $this->local_db->where("id", $params["id"]);
    $this->local_db->update(local_table_name("cached_products"),["is_b4b_price_hidden" => $params["is_b4b_price_hidden"]]);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Success")
    );
  }

  public function isNewFromWarehouse($params)
  {
    escapeAllKeys($params,null,$this->local_db);
    removeDashboardCache("dashboard");

    $check_product_sql = "SELECT
                            product.`id`
                          FROM `".local_table_name("cached_products")."` product
                          WHERE product.`deleted_at` IS NULL
                          AND product.`id` = {$params["id"]} ";

    $check_product_query = $this->local_db->query($check_product_sql);

    if(!$check_product_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Product not found")
      );
    }

    $this->local_db->where("id", $params["id"]);
    $this->local_db->update(local_table_name("cached_products"), [
      "is_new_from_warehouse" => $params["is_new_from_warehouse"],
      "new_from_warehouse_start_date" => $params["new_from_warehouse_start_date"],
      "new_from_warehouse_end_date" => $params["new_from_warehouse_end_date"],
      "new_from_warehouse_image" => $params["new_from_warehouse_image"],
    ]);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Success")
    );
  }
}
