<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

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

    $stock_type_query = "";
    $product_resources = special_codes("products.resources");

    if($params["filter_type"] === B4B_CART_FILTER_TYPE_NO_STOCK) {
      $stock_type_query = " AND products.`stock_baku` <= 0
                            AND products.`stock_baku_2` <= 0
                            AND products.`stock_ganja` <=0
                            AND cart.`resource` = '".special_codes("products.resources.ava")."'
                            ";

    } elseif ($params["filter_type"] === B4B_CART_FILTER_TYPE_GROUP) {
      if($params["is_remote"] === STATUS_DEACTIVE) {

        if((int)$params["wid"] === (int)WAREHOUSE_BAKU_ID) {
          $stock_type_query = " AND products.`stock_baku` > 0";
        }elseif ((int)$params["wid"] === (int)WAREHOUSE_BAKU_2_ID) {
          $stock_type_query = " AND products.`stock_baku_2` > 0";
        }elseif ((int)$params["wid"] === (int)WAREHOUSE_GANJA_ID) {
          $stock_type_query = " AND products.`stock_ganja` > 0";
        }
      } elseif ($params["is_remote"] === STATUS_ACTIVE) {
        if(in_array($params["resource"], [special_codes("products.resources.ronax"),special_codes("products.resources.store")])) {
          $stock_type_query = " AND cart.`resource` = '".$params["resource"]."'";
        }
      }
    }

    $remote_customer_query = $params["remoteb4buserid"] ? " AND cart.`remote_customer_id` = {$params["remoteb4buserid"]}" : "";

    $cart_sql = "SELECT
                                          cart.`basket_id` as `id`,
                                          cart.`basket_product_id` as `product_id`,
                                          cart.`basket_quantity` as `quantity`,
                                          cart.`basket_from` as `product_from`,
                                          cart.`basket_price` as `product_sale_price`,
                                          cart.`basket_currency` as `product_currency`,
                                          cart.`basket_name` as `product_name`,
                                          cart.`basket_brend` as `product_brand_name`,
                                          cart.`basket_ins_date` as `operation_date`,
                                          cart.`comment`,
                                          cart.`basket_brend_code` as `product_brand_code`,
                                          cart.`basket_brend_original_code` as `product_OEM`,
                                          cart.`basket_description` as `product_basket_description`,
                                          products.`discount_price`,
                                          products.`has_discount`,
                                          products.`discount_rate`,
                                          products.`is_b4b_price_hidden`,
                                          IFNULL(products.`custom_main_sale_price`,products.`sale_price`) as `product_custom_sale_price`,
                                          (CASE WHEN cart.`basket_from` = '".PRODUCT_RESOURCE_AVA."'
                                            THEN products.`stock_baku`
                                            ELSE NULL END) as `product_stock_baku`,
                                          (CASE WHEN cart.`basket_from` = '".PRODUCT_RESOURCE_AVA."'
                                            THEN products.`stock_baku_2`
                                            ELSE NULL END) as `product_stock_baku_2`,
                                          (CASE WHEN cart.`basket_from` = '".PRODUCT_RESOURCE_AVA."'
                                            THEN products.`stock_ganja`
                                            ELSE NULL END) as `product_stock_ganja`
                                        FROM `".local_table_name("b4b_cart")."` cart
                                        LEFT JOIN `".local_table_name("cached_products")."` products ON products.`id` = cart.`basket_product_id`
                                          AND products.`deleted_at` IS NULL
                                        WHERE cart.`deleted_at` IS NULL
                                        $stock_type_query
                                        AND cart.`basket_company_id` = {$params["b4buserid"]}
                                        AND cart.`basket_currency` = '{$params["b4busercurrency"]}'
                                        $remote_customer_query
                                        ORDER BY cart.`basket_ins_date` DESC

                ";
    $cart_query = $this->local_db->query($cart_sql);
    if(!$cart_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No product in cart")
      );
    }

    $cart = $cart_query->result_array();

    $cart_list = [];
    $cart_list_no_stock = [];

    $res_currencies = get_currency();
    $currencies_name_list = [];
    foreach ($res_currencies as $key => $item) {
      $currencies_name_list[$item["main_name"]] = $item;
    }

    foreach ($cart as $key => $item) {
      $to_order = true;
      $delivery_time = AVA_PRODUCT_DELIVERY_TIME;
      if($item["product_from"] === PRODUCT_RESOURCE_RONAX) {
        $delivery_time = RONAX_PRODUCT_DELIVERY_TIME;
      }
      if($item["product_from"] === PRODUCT_RESOURCE_STORE) {
        $delivery_time = STORE_PRODUCT_DELIVERY_TIME;
      }

      if($item["product_stock_baku"] <= 0 && $item["product_stock_baku_2"] <= 0 && $item["product_stock_ganja"] <= 0){
        $to_order = false;
      }

      $item["product_custom_sale_price"] = $item["is_b4b_price_hidden"] === STATUS_ACTIVE ? NULL : $item["product_custom_sale_price"];
      if($item["product_from"] === PRODUCT_RESOURCE_RONAX) {
        $item["product_custom_sale_price"] = $item["product_sale_price"];
      }

      $item["discount_price"] = $item["is_b4b_price_hidden"] === STATUS_ACTIVE ? NULL : ($item["has_discount"] ? ($item["product_sale_price"]) : null);

      $converted_sale_price = isset($currencies_name_list[$item["product_currency"]]) ? $item["product_custom_sale_price"] * $currencies_name_list[$item["product_currency"]]["value"] : NULL;
      $converted_discount_price = isset($currencies_name_list[$item["product_currency"]]) ? $item["discount_price"] * $currencies_name_list[$item["product_currency"]]["value"] : NULL;

      $cart_list[] = [
        "id" => $item["id"],
        "quantity" => (int)$item["quantity"],
        "comment" => $item["comment"],
        "operation_date" => $item["operation_date"],
        "product" => [
          "id" => $item["product_id"],
          "to_order" => $to_order,
          "name" => $item["product_name"],
          "OEM" => $item["product_OEM"],
          "delivery_time" => $delivery_time,
          "resource" => $item["product_from"] ? special_codes("products.resources.".$item["product_from"]) : null,
          "currency" => $item["product_currency"],
          "sale_price" => $item["product_custom_sale_price"],
          "converted_sale_price" => $converted_sale_price,
          "discount_price" => $item["has_discount"] ? $item["discount_price"] : null,
          "converted_discount_price" => $item["has_discount"] ? $converted_discount_price : null,
          "converted_currency" => CURRENCY_AZN,
          "total_sale_price" => $item["quantity"] * $item["product_custom_sale_price"],
          "total_discount_price" => $item["has_discount"] ? $item["quantity"] * $item["discount_price"] : null,
          "converted_total_sale_price" => $item["quantity"] * $converted_sale_price,
          "converted_total_discount_price" => $item["has_discount"] ? $item["quantity"] * $converted_discount_price : null,
          "has_discount" => $item["has_discount"] === STATUS_ACTIVE,
          "stock_baku" => stock_visibility(special_codes("products.resources.".$item["product_from"]),$params["auth_user"]["stock_show"],$item["product_stock_baku"]),
          "stock_baku_2" => stock_visibility(special_codes("products.resources.".$item["product_from"]),$params["auth_user"]["stock_show"],$item["product_stock_baku_2"]),
          "stock_ganja" => stock_visibility(special_codes("products.resources.".$item["product_from"]),$params["auth_user"]["stock_show"],$item["product_stock_ganja"]),
          "brand" => [
            "name" => $item["product_brand_name"],
            "code" => $item["product_brand_code"],
          ],
        ],
      ];
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => count($cart_list),
        "list" => $cart_list,
      ]
    );
  }

  public function cartCount($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $res_currencies = get_currency();
    $currencies_list = [];
    foreach ($res_currencies as $key => $item) {
      $currencies_list[$item["main_name"]] = $item;
    }
    $remote_customer_query = $params["remoteb4buserid"] ? " AND cart.`remote_customer_id` = {$params["remoteb4buserid"]}" : "";

    $cart_sql = "SELECT
                    cart.`basket_id` as `id`,
                    cart.`basket_quantity` as `quantity`,
                    cart.`basket_price` as `sale_price`,
                    products.`has_discount`,
                    products.`discount_price`,
                    products.`is_b4b_price_hidden`
                    FROM `".local_table_name("b4b_cart")."` cart
                    LEFT JOIN `".local_table_name("cached_products")."` products ON products.`id` = cart.`basket_product_id`
                    WHERE cart.`deleted_at` IS NULL
                    AND products.`deleted_at` IS NULL
                    AND cart.`basket_company_id` = {$params["b4buserid"]}
                    AND cart.`basket_currency` = '{$params["b4busercurrency"]}'
                    $remote_customer_query
                    ";

    $cart_query = $this->local_db->query($cart_sql);
    $cart = $cart_query->result_array();
    // return json_response(rest_response(205,"test",["key" => $cart]));
    $cart_list = [];
    $sale_price = 0;
    $main_sale_price = 0;
    $count = 0;
    foreach ($cart as $key => $item) {
      // $item["sale_price"] = $item["is_b4b_price_hidden"] === STATUS_ACTIVE ? 0 : ($item["has_discount"] === STATUS_ACTIVE ? $item["discount_price"] : $item["sale_price"]);
      $item["sale_price"] = $item["has_discount"] === STATUS_ACTIVE ? $item["discount_price"] : $item["sale_price"];
      $item["sale_price"] = $item["is_b4b_price_hidden"] === STATUS_ACTIVE ? 0 : $item["sale_price"];
      // $item["sale_price"] = $item["is_b4b_price_hidden"] ? 0 : ($item["has_discount"] ? $item["discount_price"] : $item["sale_price"]);
      $count++;
      // $cart["sale_price"] = $item["sale_price"];
      $sale_price += $item["sale_price"] * $item["quantity"];
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "sale_price" => $sale_price,
        "count" => $count,
        // "cart" => $cart,
      ]
    );
  }
}
