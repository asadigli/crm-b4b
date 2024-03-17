<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  public function index($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $res_currencies = get_currency();
    $currencies_list = [];
    foreach ($res_currencies as $key => $item) {
      $currencies_list[$item["main_name"]] = $item;
    }

    $product_resource = array_search($params["product_resource"],special_codes("products.resources"));

    if(in_array($product_resource,[PRODUCT_RESOURCE_AVA,PRODUCT_RESOURCE_STORE])) {
      $check_product_sql = "SELECT
                              product.`id`,
                              product.`remote_id`,
                              product.`resource`,
                              product.`name`,
                              product.`cleaned_brand_code`,
                              product.`brand_code`,
                              product.`brand_name`,
                              product.`description`,
                              product.`OEM`,
                              product.`cleaned_OEM`,
                              product.`currency`,
                              IFNULL(product.`final_currency_id`,product.`currency_id`) as `final_currency_id`,
                              IFNULL(product.`custom_main_sale_price`,product.`sale_price`) as `sale_price`
                            FROM `".local_table_name("cached_products")."` product
                            WHERE product.`deleted_at` IS NULL
                            AND product.`id` = {$params["product_id"]}";

      $check_product_query = $this->local_db->query($check_product_sql);
      if(!$check_product_query->num_rows()){
        return rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("Product not found")
        );
      }
      $check_product = $check_product_query->row_array();
    }

    #beacuse store is import products for cached_products and you can choose currency for them
    if(in_array($product_resource,[PRODUCT_RESOURCE_AVA,PRODUCT_RESOURCE_STORE])) {
      $res_currencies = get_currency();
      $currencies_list = [];
      $currencies_name_list = [];
      foreach ($res_currencies as $key => $item) {
        $currencies_list[$item["id"]] = $item;
        $currencies_name_list[$item["main_name"]] = $item;
      }

      $check_product["currency"] = isset($currencies_list[$check_product["final_currency_id"]]) ? $currencies_list[$check_product["final_currency_id"]]["main_name"] : NULL;
    }

    if($params["cart_id"]) {
      $remote_customer_query = $params["remoteb4buserid"] ? " AND `remote_customer_id` = {$params["remoteb4buserid"]}" : "";
      $check_company_cart_sql = " SELECT
                                      `basket_product_id`,
                                      `basket_id`,
                                      `basket_quantity`
                                  FROM `".local_table_name("b4b_cart")."`
                                  WHERE `deleted_at` IS NULL
                                  AND `basket_id` = {$params["cart_id"]}
                                  AND `basket_company_id` = {$params["b4buserid"]}
                                  $remote_customer_query
                                  ";

      $check_company_cart_query = $this->local_db->query($check_company_cart_sql);

      if(!$check_company_cart_query->num_rows()) {
        return rest_response(
          Status_codes::HTTP_NO_CONTENT,
          lang("Product not found")
        );
      }

      $this->local_db->where("basket_id", $params["cart_id"]);
      $this->local_db->update(local_table_name("b4b_cart"),[
        "basket_quantity" => $params["product_quantity"],
        "basket_price" => $product_resource === PRODUCT_RESOURCE_AVA ? $check_product["sale_price"] : $params["product_sale_price"]
      ]);

      return rest_response(
        Status_codes::HTTP_ACCEPTED,
        lang("Product quantity updated")
      );
    }

    $res_currencies = get_currency();
    $currencies_list = [];
    foreach ($res_currencies as $key => $item) {
      $currencies_list[$item["main_name"]] = $item;
    }

    $params["product_currency"] = isset($currencies_list[$params["final_currency"]]) ? $currencies_list[$params["final_currency"]]["main_name"] : NULL;
    $params["currency_id"] = isset($currencies_list[$params["final_currency"]]) ? $currencies_list[$params["final_currency"]]["id"] : NULL;

    $cart_insert_list = [
      "basket_company_id" => $params["b4buserid"],
      "remote_customer_id" => $params["remoteb4buserid"],
      "basket_product_id" => $params["product_id"],
      "basket_from" =>  $product_resource,
      "product_resource" =>  $params["product_resource"],
      "resource" =>  $params["product_resource"],
      "user_ip" => $params["user_ip"],
      "user_access_token" => $params["user_access_token"],
      "basket_price" => $params["product_sale_price"],
      "basket_currency" => $params["product_currency"],
      "currency_id" => $params["currency_id"],
      "basket_quantity" => $params["product_quantity"],
      "basket_name" => $params["product_name"],
      "basket_brend" => $params["product_brand_name"],
      "basket_brend_code" => $params["product_brand_code"],
      "basket_brend_original_code" => $params["product_OEM"],
      "basket_description" => $params["product_description"],
    ];

    $this->local_db->insert(local_table_name("b4b_cart"),$cart_insert_list);
    $cart_id = $this->local_db->insert_id();

    return rest_response(
      Status_codes::HTTP_CREATED,
      lang("Product added to cart"),
      [
        "cart_id" => $cart_id
      ]
    );
  }
}
