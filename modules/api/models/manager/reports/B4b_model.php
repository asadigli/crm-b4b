<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class B4b_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  function index($params) {
    $sql_query = "SELECT
                    SUM(1) as annual_order_count,
                    SUM(CASE WHEN MONTH(b4b_orders.`product_order_ins_date`) = '{$params["month"]}' THEN 1 ELSE 0 END) AS monthly_order_count,
                    SUM(CASE WHEN MONTH(b4b_orders.`product_order_ins_date`) = '{$params["month"]}'
                        AND DAY(b4b_orders.`product_order_ins_date`) = '{$params["day"]}' THEN 1 ELSE 0 END) AS daily_order_count
                  FROM `".local_table_name("b4b_orders")."` b4b_orders
                  WHERE b4b_orders.`deleted_at` IS NULL
                  AND YEAR(b4b_orders.`product_order_ins_date`) = '{$params["year"]}'";
    $order_query = $params["local_db"]->query($sql_query);
    $orders = $order_query->row_array();

    if (isset($orders["annual_order_count"])) {
      $orders["order_count"]["annual"]       = number_format($orders["annual_order_count"],0,".",",");
      $orders["order_count"]["monthly"]      = number_format($orders["monthly_order_count"],0,".",",");
      $orders["order_count"]["daily"]        = number_format($orders["daily_order_count"],0,".",",");

      unset($orders["annual_order_count"]);
      unset($orders["monthly_order_count"]);
      unset($orders["daily_order_count"]);
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $orders
    );
  }

}
