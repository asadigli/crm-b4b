<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  public function editQuantity($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $remote_customer_query = $params["remoteb4buserid"] ? " AND cart.`remote_customer_id` = {$params["remoteb4buserid"]}" : "";

    $check_cart_sql = "SELECT
                        cart.`basket_id`,
                        cart.`basket_quantity`
                      FROM `".local_table_name("b4b_cart")."` cart
                      WHERE cart.`deleted_at` IS NULL
                      AND cart.`basket_company_id` = {$params["b4buserid"]}
                      $remote_customer_query
                      AND cart.`basket_id` = {$params["id"]}
                      ";
    $check_cart_query = $this->local_db->query($check_cart_sql);
    if(!$check_cart_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("This cart item is not exists")
      );
    }
    $check_cart = $check_cart_query->row_array();

    $this->local_db->where("basket_id", $params["id"]);
    $this->local_db->update(local_table_name("b4b_cart"),[
      "basket_quantity" => $params["quantity"]
    ]);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Quantity updated"),
    );
  }

  public function editNote($params) {
    escapeAllKeys($params,null,$this->local_db);

    $remote_customer_query = $params["remoteb4buserid"] ? " AND cart.`remote_customer_id` = {$params["remoteb4buserid"]}" : "";

    $check_cart_sql = "SELECT
                          cart.`basket_id`,
                          cart.`comment`
                       FROM `".local_table_name("b4b_cart")."` cart
                       WHERE cart.`deleted_at` IS NULL
                       AND cart.`basket_company_id` = {$params["b4buserid"]}
                       $remote_customer_query
                       AND cart.`basket_id` = {$params["id"]}";
                       
    $check_cart_query = $this->local_db->query($check_cart_sql);
    if(!$check_cart_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("This cart item is not exists")
      );
    }
    $check_cart = $check_cart_query->row_array();

    $this->local_db->where("basket_id", $params["id"]);
    $this->local_db->update(local_table_name("b4b_cart"),[
      "comment" => $params["comment"]
    ]);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Note updated"),
    );
  }

  public function delete($params)
  {
    escapeAllKeys($params,null,$this->local_db);
    $remote_customer_query = $params["remoteb4buserid"] ? " AND cart.`remote_customer_id` = {$params["remoteb4buserid"]}" : "";
    $check_cart_sql = "SELECT
                        cart.`basket_id`,
                        cart.`basket_quantity`
                      FROM `".local_table_name("b4b_cart")."` cart
                      WHERE cart.`deleted_at` IS NULL
                      AND cart.`basket_company_id` = {$params["b4buserid"]}
                      $remote_customer_query
                      AND cart.`basket_id` = {$params["id"]}
                      ";

    $check_cart_query = $this->local_db->query($check_cart_sql);
    if(!$check_cart_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("This cart item is not exists")
      );
    }

    $this->local_db->where("basket_id", $params["id"]);
    $this->local_db->update(local_table_name("b4b_cart"),[
      "deleted_at" => $params["deleted_at"]
    ]);

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Product deleted from cart"),
    );
  }

  public function deleteItems($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $ids = implode(",",$params["cart_ids"]);
    $remote_customer_query = $params["remoteb4buserid"] ? " AND cart.`remote_customer_id` = {$params["remoteb4buserid"]}" : "";
    $check_cart_sql = "SELECT
                        cart.`basket_id`,
                        cart.`basket_quantity`
                      FROM `".local_table_name("b4b_cart")."` cart
                      WHERE cart.`deleted_at` IS NULL
                      AND cart.`basket_company_id` = {$params["b4buserid"]}
                      $remote_customer_query
                      AND cart.`basket_id` IN ({$ids})
                      ";

    $check_cart_query = $this->local_db->query($check_cart_sql);

    if(!$check_cart_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("This cart items is not exists")
      );
    }
    $check_carts = $check_cart_query->result_array();
    $cart_ids = array_map(function($i){return $i["basket_id"];},$check_carts);

    $this->local_db->where_in("basket_id", $cart_ids);
    $this->local_db->update(local_table_name("b4b_cart"), [
      "deleted_at" => $params["deleted_at"]
    ]);

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Products deleted from cart")
    );
  }
}
