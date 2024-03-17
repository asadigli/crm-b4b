<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  private $db_table = 'sa_users';

  private $admin_list = ['admin','main_admin','developer'];


  function getConfigs($user = null){
    if (!$user) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }
    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $data = getConfig('custom');
    return $data;
  }

  function updateConfig($params = []){
    if (!$params["user"] || !$params["config_name"] || !$params['config_value']) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }
    
    $u = isAdmin($params["user"]);
    if (!$u["status"]) return $u["body"];

    $path = APPPATH.'config/jsons/custom.json';
    $json_data = getConfig('custom');
    $new_list = [];
    foreach (array_keys($json_data) as $key => $data) {
      if ($data === $params['config_name']) {
        $new_val = $json_data[$data]["value"];
        if ($json_data[$data]["type"] === "INT") {
          $new_val = filter_var($params['config_value'], FILTER_VALIDATE_INT) &&
                      $params['config_value'] <= $json_data[$data]["max"] &&
                          $params['config_value'] >= $json_data[$data]["min"] ? (int)$params['config_value'] : $json_data[$data]["value"];
        }else{
          $new_val = $params['config_value'];
        }
        $new_list = [
          $data => [
            "value" => $new_val,
            "type" => $json_data[$data]["type"],
            "min" => $json_data[$data]["min"],
            "max" => $json_data[$data]["max"],
          ]
        ];
      }else{
        $new_list = [
          $data => [
            "value" => $json_data[$data]["value"],
            "type" => $json_data[$data]["type"],
            "min" => $json_data[$data]["min"],
            "max" => $json_data[$data]["max"],
          ]
        ];
      }
    }
    $newJsonString = json_encode($new_list);
    count($new_list) ? file_put_contents($path, $newJsonString) : "";
    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),[]);
  }


  function homeProducts($user = null){
    $sql = "SELECT prod.`id`,prod.`order`,prod.`LOGICALREF`,prod.`slug`,
                   prod.`SPECODE2` as parent,prod.`quantity`,prod.`SALE_PRICE` as price,
                   prod.`PRODUCERCODE` as producercode,prod.`STATENAME` as prod_name,
                   prod.`SPECODE` as brand,prod.`CODE` as brand_code,prod.`OEM`,prod.`status`
            FROM `sa_products` prod
            WHERE  prod.`deleted_at` IS NULL
            AND prod.`order` > 0
            ORDER BY prod.`order` DESC";

    $products_query = $this->db->query($sql);
    $products = $products_query->result_array();

    $hp_limit = getConfig('custom')["main_page_products"];

    $products_new = [];
    foreach ($products as $index => $product) {
      $products_new[] = [
        "key" => $product["id"],
        "order" => $product["order"],
        "slug" => $product["slug"],
        "OEM" => $product["OEM"],
        "prod_code" => $product["prod_code"],
        "brand_code" => $product["brand_code"],
        "price" => $product["sale_price"],
        "product_name" => $product["prod_name"],
        "short_name" => str_limit($product["prod_name"],30),
        "OEM" => $product["OEM"],
        "product_limit" => $hp_limit["value"],
        "product_limit_min" => $hp_limit["min"],
        "product_limit_max" => $hp_limit["max"],
      ];
    }
    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),$products_new);
  }


  // function checkProdCode($code = null){
  //   $sql = "SELECT prod.`id`,prod.`STATENAME` as prod_name
  //           FROM `sa_products` prod
  //           WHERE  prod.`deleted_at` IS NULL
  //           AND prod.`PRODUCERCODE` = '$code'
  //           ORDER BY prod.`order` DESC
  //           LIMIT 1";
  //   $products_query = $this->db->query($sql);
  //   $products = $products_query->result_array();
  //   $products = isset($products[0]) ? $products[0] : $products;
  //   return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),$products);
  // }

  function addHP_product($params = []){
    $prod = $params["product"];
    $last_order = $this->db->select("`order`")->from("`sa_products`")->order_by("`order`","desc")->limit(1)->get();
    $last_order = $last_order->result_array()[0]["order"];
    if ($last_order) {
      $prod ? $this->db->where('id',$prod) : $this->db->where('PRODUCERCODE',$params["code"]);
      $order = $params["reset"] ? 0 : ($last_order + 1);
      $this->db->update("`sa_products`",["order" => $order]);
    }
    return rest_response(
      $last_order ? Status_codes::HTTP_OK : Status_codes::HTTP_CONFLICT,
      $last_order ? $this->lang->line("Success") : $this->lang->line("Product not found"),
      []
    );
  }


  function updateProductOrders($params){
    $user = $params['user'];
    $list = $params['list'];

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $exist_products_ids = [];
    $sub_query = "";
    foreach ($list as $key => $item) {
      if (isset($item["product"])) {
        $exist_products_ids[] = $item["product"];
      }
      $sub_query .= " WHEN `id` = {$item["product"]} THEN {$item["order"]} ";
    }

    if (!count($exist_products_ids)) {
      return rest_response(
        Status_codes::HTTP_NOT_FOUND,
        $this->lang->line("Product not found"),
        []
      );
    }


    if ($exist_products_ids) {
      $sql_query = "UPDATE `sa_products`
                    SET `order` = (CASE $sub_query END)
                    WHERE `id` in (".implode(",",$exist_products_ids).")";
      $this->db->query($sql_query);
    }

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),[]);
  }

}
