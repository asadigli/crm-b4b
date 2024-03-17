<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function isApprove($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $check_customer_sql = "SELECT
                                customers.`id`,
                                customers.`max_allowed_order_limit`,
                                customers.`max_order_limit`,
                                customers.`has_order_limit`
                              FROM `".local_table_name("cached_customers")."` customers
                              WHERE customers.`deleted_at` IS NULL
                              AND customers.`remote_id` = {$params["remoteb4buserid"]}
                              ";
    $check_customer_sql =  "SELECT
                        customers.`id`,
                        customers.`remote_id`,
                        customers.`max_order_limit`,
                        customers.`max_allowed_order_limit`,
                        customers.`has_order_limit`,

                        (SUM(IFNULL(customer_accounts.`entry_amount`,0)) -
                        SUM(IFNULL(customer_accounts.`exit_amount`,0))) as `debt_amount`
                  FROM `".local_table_name("cached_customer_accounts")."` `customer_accounts`
                  RIGHT JOIN `".local_table_name("cached_customers")."` customers ON customers.`id` = customer_accounts.`customer_id`
                    AND customers.`deleted_at` IS NULL
                    AND customers.`remote_is_active` = '".STATUS_ACTIVE."'
                  WHERE customer_accounts.`deleted_at` IS NULL
                  AND customers.`remote_id` = {$params["remoteb4buserid"]}
                  GROUP BY customer_accounts.`customer_id`
                  ";

    $check_customer_query = $this->local_db->query($check_customer_sql);
    // if(!$check_customer_query->num_rows()) {
    //   return rest_response(
    //     Status_codes::HTTP_NO_CONTENT,
    //     lang("Customer not found")
    //   );
    // }
    $check_customer = $check_customer_query->row_array();

    $stock_query = "";

    $check_group_sql = "SELECT
                          order_groups.`id`,
                          order_groups.`warehouse_id`,
                          order_groups.`supplier`,
                          order_groups.`name`,
                          order_groups.`is_remote`
                        FROM `".local_table_name("b4b_order_groups")."` order_groups
                        WHERE order_groups.`deleted_at` IS NULL
                        AND order_groups.`is_active` = '".STATUS_ACTIVE."'
                        AND order_groups.`is_b4b_active` = '".STATUS_ACTIVE."'
                        AND order_groups.`id` = {$params["group_id"]}
                          ";
    $check_group_query = $this->local_db->query($check_group_sql);
    if(!$check_group_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Special error: ". special_codes("app_status_codes.errors.group_not_found"))
      );
    }

    $check_group = $check_group_query->row_array();

    $stock_query = "THEN products.`stock_baku`";
    if((int)$check_group["warehouse_id"] === WAREHOUSE_GANJA_ID) {
      $stock_query = "THEN products.`stock_ganja`";
    }

    if((int)$check_group["warehouse_id"] === WAREHOUSE_BAKU_2_ID) {
      $stock_query = "THEN products.`stock_baku_2`";
    }

    $cart_ids = implode(",",$params["cart_ids"]);

    $res_currencies = get_currency();
    $currencies_list = [];
    foreach ($res_currencies as $key => $item) {
      $currencies_list[$item["main_name"]] = $item;
    }

    $remote_customer_query = $params["remoteb4buserid"] ? " AND cart.`remote_customer_id` = {$params["remoteb4buserid"]}" : "";

    $check_cart_sql = "SELECT
                          cart.`basket_id` as `id`,
                          cart.`basket_product_id` as `product_id`,
                          cart.`basket_quantity` as `quantity`,
                          cart.`basket_from` as `product_from`,
                          cart.`basket_price` as `product_sale_price`,
                          cart.`basket_currency` as `product_currency`,
                          cart.`basket_quantity` as `quantity`,
                          cart.`basket_name` as `product_name`,
                          cart.`basket_brend` as `product_brand_name`,
                          cart.`basket_ins_date` as `operation_date`,
                          cart.`resource` as `resource`,
                          cart.`basket_brend_code` as `product_brand_code`,
                          cart.`basket_brend_original_code` as `product_OEM`,
                          cart.`basket_description` as `product_description`,
                          products.`stock_baku` as `product_stock_baku`,
                          products.`stock_baku_2` as `product_stock_baku_2`,
                          products.`stock_ganja` as `product_stock_ganja`,
                          products.`has_discount`,
                          products.`discount_rate`,
                          products.`discount_price`,
                          products.`is_b4b_price_hidden`,
                          ".($stock_query ? "(CASE WHEN cart.`basket_from` = '".PRODUCT_RESOURCE_AVA."'
                            $stock_query
                            ELSE '0' END)" : "'0'") ." as `product_stock`
                      FROM `".local_table_name("b4b_cart")."` cart
                      LEFT JOIN `".local_table_name("cached_products")."` products ON products.`id` = cart.`basket_product_id`
                        AND products.`deleted_at` IS NULL
                      WHERE cart.`deleted_at` IS NULL
                      AND cart.`basket_company_id` = {$params["b4buserid"]}
                      $remote_customer_query
                      AND cart.`basket_id` IN ({$cart_ids})
                      ";

    $check_cart_query = $this->local_db->query($check_cart_sql);

    if(!$check_cart_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("This cart items is not exists")
      );
    }
    $check_carts = $check_cart_query->result_array();

    $cart_list = [];
    $warning_messages = [];
    $total_sale_price = 0;
    $total_main_sale_price = 0;
    $order_details_insert_list = [];

    $only_ronax_products = 0;
    $only_store_products = 0;
    $only_ava_products = 0;

    $ronax_check = 1;

    foreach ($check_carts as $key => $item) {

        $delivery_time = AVA_PRODUCT_DELIVERY_TIME;
        if($item["product_from"] === PRODUCT_RESOURCE_RONAX) {
          $delivery_time = RONAX_PRODUCT_DELIVERY_TIME;
          $only_ronax_products++;
        }

        if($item["product_from"] === PRODUCT_RESOURCE_STORE) {
          $delivery_time = STORE_PRODUCT_DELIVERY_TIME;
          $only_store_products++;
        }
        if($item["product_from"] === PRODUCT_RESOURCE_AVA) {
          $only_ava_products++;
        }

        if(
          (
            $item["quantity"] > 0 &&
            $check_group["supplier"] === $item["resource"] &&
            $item["product_currency"] === $params["b4busercurrency"]
          ) &&
          (
            (
              $check_group["is_remote"] === STATUS_DEACTIVE &&
              $item["resource"] === special_codes("products.resources.ava") &&
              $item["product_stock"] > 0
              )
              ||
            (
              $check_group["is_remote"] === STATUS_ACTIVE &&
              in_array($item["resource"], [special_codes("products.resources.ronax"),special_codes("products.resources.store")])
            )
          )
        ) {

            $item["product_sale_price"] = $item["is_b4b_price_hidden"] ? NULL : $item["product_sale_price"];
            // $discount_price = $item["has_discount"] ? ($item["product_sale_price"]) : 0;
            // $discount_price = $item["is_b4b_price_hidden"] ? NULL : $discount_price;

            $total_sale_price += ($item["product_sale_price"] * $item["quantity"]);

            $converted_sale_price = isset($currencies_name_list[$item["product_currency"]]) ? $item["product_sale_price"] * $currencies_name_list[$item["product_currency"]]["value"] : NULL;

            $converted_discount_price = isset($currencies_name_list[$item["product_currency"]]) ? $discount_price * $currencies_name_list[$item["product_currency"]]["value"] : NULL;
            $converted_discount_price = $item["is_b4b_price_hidden"] ? NULL : $converted_discount_price;
            // $main_sale_price = isset($currencies_list[$item["product_currency"]]) ? ($item["quantity"] * $currencies_list[$item["product_currency"]]["value"] * $item["product_sale_price"]) : NULL;
            // $total_main_sale_price += $main_sale_price;

            $cart_list[] = [
              "id" => $item["id"],
              "quantity" => $item["quantity"],
              "product" => [
                "id" => $item["product_id"],
                "stock_baku" => stock_visibility($item["resource"],$params["auth_user"]["stock_show"],$item["product_stock_baku"]),
                "stock_baku_2" => stock_visibility($item["resource"],$params["auth_user"]["stock_show"],$item["product_stock_baku_2"]),
                "stock_ganja" => stock_visibility($item["resource"],$params["auth_user"]["stock_show"],$item["product_stock_ganja"]),
                "name" => $item["product_name"],
                "OEM" => $item["product_OEM"],
                "delivery_time" => $delivery_time,
                "resource" => $item["product_from"] ? special_codes("products.resources.".$item["product_from"]) : null,
                "currency" => $item["product_currency"],
                "sale_price" => $item["product_sale_price"],
                "converted_sale_price" => $converted_sale_price,
                // "main_sale_price" => isset($currencies_list[$item["product_currency"]]) ? ($currencies_list[$item["product_currency"]]["value"] * $item["product_sale_price"]) : NULL,
                "total_sale_price" => ($item["quantity"] * $item["product_sale_price"]),
                "converted_total_sale_price" => ($item["quantity"] * $converted_sale_price),
                // "total_main_sale_price" => $main_sale_price,
                "converted_currency" => CURRENCY_AZN,
                "brand" => [
                  "name" => $item["product_brand_name"],
                  "code" => $item["product_brand_code"],
                ],
              ],
            ];

            // foreach ($cart_list as $key => $item) {
              if ($item["resource"] !== special_codes("products.resources.ronax")) {
                  $ronax_check = 0;
              }
            // }
        }
    }

    if($check_customer["has_order_limit"] &&
      (((float)$check_customer["debt_amount"] + (float)$total_sale_price) > (float)$check_customer["max_allowed_order_limit"])
    ) {

      $order_limit_message = "";
      // $configs = Services::configs(["group" => "other"]);
      // if(isset($configs[MAX_AMOUNT_ORDER_LIMIT_MESSAGE]) && $configs[MAX_AMOUNT_ORDER_LIMIT_MESSAGE]) {
        // $order_limit_message = isset($configs[MAX_AMOUNT_ORDER_LIMIT_MESSAGE][0]) ? $configs[MAX_AMOUNT_ORDER_LIMIT_MESSAGE][0] : "";
        $max_amount_order_sql = "SELECT configs.`value`
                                 FROM `".local_table_name("b4b_configs")."` AS configs
                                 WHERE configs.`deleted_at` IS NULL
                                 AND configs.`key` = '".MAX_AMOUNT_ORDER_LIMIT_MESSAGE."'
                                ";

        $max_amount_order_query = $this->local_db->query($max_amount_order_sql);

        if(!$max_amount_order_query->num_rows()) {
          return rest_response(
            Status_codes::HTTP_NO_CONTENT,
            lang("Not found data")
          );
        }
        $max_amount_order = $max_amount_order_query->row_array();


        if($order_limit_message){
          $order_limit_message = str_replace(REPLACE_ORDER_AMOUNT,
                                                ($check_customer["max_order_limit"] ?: "0.00") . " ". CURRENCY_EUR,
                                                    $max_amount_order["value"]);
        }

        if(!$order_limit_message) {
          $order_limit_message = lang("_you_are_above_order_limit",["count" => $check_customer["max_order_limit"] ?: 0]);
        }

        return rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          $order_limit_message
        );
    }

    if(!$cart_list) {

      if($only_ava_products === count($check_carts) && $check_group["is_remote"]) {
        return rest_response(
          Status_codes::HTTP_NO_CONTENT,
          lang("For ordering this products you should choose Baku or Ganja group")
        );
      }
      if($only_ronax_products === count($check_carts) && count($check_carts) > 0) {
        return rest_response(
          Status_codes::HTTP_NO_CONTENT,
          lang("This products could sent only from OEM group")
        );
      }

      if($only_store_products === count($check_carts) && count($check_carts) > 0) {
        return rest_response(
          Status_codes::HTTP_NO_CONTENT,
          lang("This products could sent only from 60-90 group")
        );
      }


      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Products are over the limit")
      );
    }


    if ($only_ronax_products === count($cart_list) && count($cart_list) > 0 && $ronax_check) {
      $order_min_amount = "";
      $total_price = 0;
      $ronax_check = 1;

      // $configs = Services::configs(["group" => "other"]);
      // if (isset($configs[MINIMUM_OEM_PRODUCT_AMOUNT]) && $configs[MINIMUM_OEM_PRODUCT_AMOUNT]) {
      //     $order_min_amount = isset($configs[MINIMUM_OEM_PRODUCT_AMOUNT][0]) ? $configs[MINIMUM_OEM_PRODUCT_AMOUNT][0] : "";
      // }

      $config_group_type = special_codes("configs.groups.other");

      $order_min_amount_sql = "SELECT configs.`value`
                               FROM `".local_table_name("b4b_configs")."` AS configs
                               WHERE configs.`deleted_at` IS NULL
                               AND configs.`is_active` = '".STATUS_ACTIVE."'
                               AND configs.`group` = '{$config_group_type}'
                               AND configs.`key` = '".MINIMUM_OEM_PRODUCT_AMOUNT."'
                              ";

      $order_min_amount_query = $this->local_db->query($order_min_amount_sql);
      $order_min_amount = $order_min_amount_query->row_array();

      foreach ($cart_list as $key => $item) {
          $total_price += $item["product"]["total_sale_price"];
      }

      if (isset($order_min_amount["value"]) && $order_min_amount["value"] !== null) {
        if ($total_price < (int)$order_min_amount["value"]) {
          $order_min_amount_limit_message = lang("_you_are_below_planned_oem_order_amount", ["count" => $order_min_amount["value"] ?: 0]);
          return rest_response(
              Status_codes::HTTP_BAD_REQUEST,
              $order_min_amount_limit_message
          );
        }
      }
  }


    return rest_response(
      Status_codes::HTTP_CREATED,
      lang("Success"),
      [
        "order_approve" => $cart_list,
        // "order_id" => $insert_id,
        // "warehouse" => $check_warehouse ? [
        //   "id" =>  $check_warehouse["depo_id"],
        //   "name" => $check_warehouse["depo_name"],
        // ] : null,
        "group" => [
          "id" => $check_group["id"],
          "name" => $check_group["name"],
        ],
        "entry_comment" => $params["entry_comment"],
        "totals" => [
          "sale_price" => $total_sale_price,
          "main_sale_price" => $total_main_sale_price,
        ],
        // "messages" => $warning_messages,
      ]
    );
  }
}
