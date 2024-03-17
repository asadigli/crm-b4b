<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function confirmApprove($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $check_group_sql = "SELECT
                          order_groups.`id`,
                          order_groups.`warehouse_id`,
                          order_groups.`supplier`,
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
                          cart.`resource` as `resource`,
                          cart.`basket_price` as `product_sale_price`,
                          cart.`basket_currency` as `product_currency`,
                          cart.`basket_quantity` as `quantity`,
                          cart.`basket_name` as `product_name`,
                          cart.`basket_brend` as `product_brand_name`,
                          cart.`basket_ins_date` as `operation_date`,
                          cart.`basket_brend_code` as `product_brand_code`,
                          cart.`basket_brend_original_code` as `product_OEM`,
                          cart.`basket_description` as `product_description`,
                          products.`has_discount`,
                          products.`discount_rate`,
                          products.`discount_price`,
                          products.`is_b4b_price_hidden`,
                          IFNULL(products.`custom_main_sale_price`,products.`sale_price`) as `before_discount_price`
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

    $total_sale_price = 0;
    $order_details_insert_list = [];
    $cart_ids = [];

    foreach ($check_carts as $key => $item) {

        if($item["quantity"] > 0) {

            $cart_ids[] = $item["id"];

            // $item["product_sale_price"] = $item["has_discount"] ? ($item["product_sale_price"] - ($item["product_sale_price"] * ($item["discount_rate"]/100))) : $item["product_sale_price"];
            $item["product_sale_price"] = $item["is_b4b_price_hidden"] ? NULL : $item["product_sale_price"];

            $item["before_discount_price"] = $item["is_b4b_price_hidden"] ? NULL : $item["before_discount_price"];
            $item["has_discount"] = $item["is_b4b_price_hidden"] ? STATUS_DEACTIVE : $item["has_discount"];
            if($item["product_from"] === PRODUCT_RESOURCE_RONAX){
              $item["before_discount_price"] = $item["product_sale_price"];
              $item["has_discount"] = STATUS_DEACTIVE;
            }


            $total_sale_price += $item["product_sale_price"] * $item["quantity"];

            $order_details_insert_list[] = [
              "group_id" => $params["group_id"],
              "orlist_delete" => STATUS_NO,
            	"orlist_order_id" => null,
            	"orlist_basket_id" => $item["id"],
            	"orlist_quantity" => $item["quantity"],
            	"orlist_price" => $item["product_sale_price"],
            	"orlist_currency" => $item["product_currency"],
              "status" => special_codes("b4b_orders.statuses.pending"),

              "before_discount_price" => $item["before_discount_price"],
              "has_discount" => $item["has_discount"],

            	"orlist_from" => $item["product_from"] ?: "",
            	"orlist_name" => $item["product_name"] ?: "",
            	"orlist_brend" => $item["product_brand_name"] ?: "",
            	"orlist_brend_code" => $item["product_brand_code"] ?: "",
            	"orlist_brend_org_code" => $item["product_OEM"] ?: "",
            	"orlist_description" => $item["product_description"] ?: "",
            	"orlist_ins_date" => $params["date"],
            ];

        }
    }

    $order_insert_list = [
      "product_order_delete" => STATUS_NO,
      "product_order_approve" => STATUS_YES,
      "group_id" => $params["group_id"],
      "product_order_company_id" => $params["b4buserid"],
      "remote_customer_id" => $params["remoteb4buserid"],
      "product_order_depo_id" => $check_group["warehouse_id"],
      "product_order_user_ip" => $params["user_ip"],
      "product_order_status_id" => STATUS_ACTIVE,
      "status" => special_codes("b4b_orders.statuses.pending"),
      "product_order_amount" => $total_sale_price,
      "product_order_currency" => $params["b4busercurrency"],
      "product_order_product_count"  => count($cart_ids),
      "product_order_comment" => $params["entry_comment"] ?: "  ",
      "product_order_ins_date" => $params["date"],
    ];
    $this->local_db->insert(local_table_name("b4b_orders"), $order_insert_list);
    $insert_id = $this->local_db->insert_id();

    foreach ($order_details_insert_list as $key => $item) {
      $order_details_insert_list[$key]["orlist_order_id"] = $insert_id;
    }

    $this->local_db->insert_batch(local_table_name("b4b_order_details"), $order_details_insert_list);

    $code = "AVH" . str_pad($insert_id, 10, "0", STR_PAD_LEFT);
    $order_update = $this->local_db
      ->where("product_order_id", $insert_id)
      ->update(local_table_name("b4b_orders"), [
        "code" => $code,
    ]);

     if($cart_ids) {
       $this->local_db->where_in("basket_id", $cart_ids);
       $this->local_db->update(local_table_name("b4b_cart"), [
         "deleted_at" => $params["date"]
       ]);
     }

   return rest_response(
     Status_codes::HTTP_ACCEPTED,
     lang("Your order sended")
   );
  }
}
