<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filters_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function list($params)
  {
    // $is_group_query = "";
    // if(!is_null($params["is_group"])){
    //   $params["is_group"] = $params["is_group"] === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE;
    //   $is_group_query = " AND order_groups.`filter_type`"
    // }

    $groups_sql = "SELECT
                    order_groups.`id`,
                    order_groups.`name`,
                    order_groups.`warehouse_id`,
                    order_groups.`supplier`,
                    order_groups.`is_remote`,
                    order_groups.`details`,
                    '".B4B_CART_FILTER_TYPE_GROUP."' as `filter_type`
                  FROM `".local_table_name("b4b_order_groups")."` order_groups
                  WHERE order_groups.`deleted_at` IS NULL
                  AND order_groups.`is_b4b_active` = '".STATUS_ACTIVE."'
                  AND order_groups.`is_active` = '".STATUS_ACTIVE."'
                  ORDER BY order_groups.`b4b_order`
                  ";
    $groups_query = $this->local_db->query($groups_sql);
    $groups = $groups_query->result_array();


    array_unshift($groups, [
      "id" => null,
      "name" => lang("All"),
      "warehouse_id" => null,
      "supplier" => null,
      "is_remote" => null,
      "details" => null,
      "filter_type" => B4B_CART_FILTER_TYPE_ALL,
    ]);

    $groups[] =  [
      "id" => null,
      "name" => lang("Pending"),
      "warehouse_id" => null,
      "supplier" => null,
      "is_remote" => null,
      "details" => null,
      "filter_type" => B4B_CART_FILTER_TYPE_NO_STOCK,
    ];

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $groups
    );
  }
}
