<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit','1200006M');
ini_set('max_execution_time', 900); //300 seconds = 5 minutes

class Search_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }



  public function index($params)
  {
    escapeAllKeys($params,null,$this->local_db);
    $page_load_limit = $params["export"] ? null : ($this->config->item("products_page_load_limit") ?: 200);
    $limit_query = "";
    if ($page_load_limit) {
      $limit_query = " LIMIT {$page_load_limit}";
    }

    $filter_query = "";
    switch ($params["filter"]) {
      case FILTER_STOCK_BAKU_ASC:
        $filter_query = " ORDER BY local_query.`stock_baku` ASC";
        break;
      case FILTER_STOCK_BAKU_DESC:
        $filter_query = " ORDER BY local_query.`stock_baku` DESC";
        break;
      case FILTER_STOCK_BAKU_2_ASC:
        $filter_query = " ORDER BY local_query.`stock_baku_2` ASC";
        break;
      case FILTER_STOCK_BAKU_2_DESC:
        $filter_query = " ORDER BY local_query.`stock_baku_2` DESC";
        break;
      case FILTER_STOCK_GANJA_ASC:
        $filter_query = " ORDER BY local_query.`stock_ganja` ASC";
        break;
      case FILTER_STOCK_GANJA_DESC:
        $filter_query = " ORDER BY local_query.`stock_ganja` DESC";
        break;
      case FILTER_PRICE_EUR_ASC:
        $filter_query = " ORDER BY local_query.`sale_price` ASC";
        break;
      case FILTER_PRICE_EUR_DESC:
        $filter_query = " ORDER BY local_query.`sale_price` DESC";
        break;
      case FILTER_BRAND_NAME_ASC:
        $filter_query = " ORDER BY local_query.`brand_name` ASC";
        break;
      case FILTER_BRAND_NAME_DESC:
        $filter_query = " ORDER BY local_query.`brand_name` DESC";
        break;
      case FILTER_PRODUCT_NAME_ASC:
        $filter_query = " ORDER BY local_query.`name` ASC";
        break;
      case FILTER_PRODUCT_NAME_DESC:
        $filter_query = " ORDER BY local_query.`name` DESC";
        break;
      default:
        $filter_query = " ORDER BY local_query.`stock_baku` ASC";
        break;
    }

    $brand_query = $params["brand"] ? " AND products.`brand_name` = '{$params["brand"]}'" : "";
    $car_brand_query = $params["car_brand"] ? " AND products.`carbrand_id` = '{$params["car_brand"]}'" : "";
    $product_resource_query = $params["product_resource"] ? " AND products.`resource` = '{$params["product_resource"]}'" : "";
    $in_stock_query = $params["in_stock"] ? " AND products.`stock_baku` > 0" : "";
    $offset_query = "";
    $dead_stock_query = "";
    $ds_sub_query = "";
    $warehouse_min_query = "";
    $warehouse_max_query = "";

    $only_warehouse_query = "";

    $discount_package_query = $params["discount_package_id"] ? " AND products.`discount_package_id` = '{$params["discount_package_id"]}'" : "";

    if($params["warehouse_id"] && (int)$params["warehouse_id"] === WAREHOUSE_BAKU_ID){
      $warehouse_max_query = $params["max_search_quantity"] ? "AND products.`stock_baku` < {$params["max_search_quantity"]}" :
                                                              ($params["max_search_quantity"] === 0 ? "AND products.`stock_baku` = {$params["max_search_quantity"]}" : " ");

      $warehouse_min_query = $params["min_search_quantity"] ? "AND products.`stock_baku` >= {$params["min_search_quantity"]}" :
                                                              ($params["min_search_quantity"] === 0 ? "AND products.`stock_baku` = {$params["min_search_quantity"]}" : "");

      $only_warehouse_query = $params["only_warehouse"] === STATUS_ACTIVE ? " AND products.`stock_ganja` = 0  AND products.`stock_baku_2` = 0 " : "";
    } elseif ($params["warehouse_id"] && (int)$params["warehouse_id"] === WAREHOUSE_BAKU_2_ID) {
        $warehouse_max_query = $params["max_search_quantity"] ? "AND products.`stock_baku_2` < {$params["max_search_quantity"]}" :
                                                                ($params["max_search_quantity"] === 0 ? "AND products.`stock_baku_2` = {$params["max_search_quantity"]}" : " ");

        $warehouse_min_query = $params["min_search_quantity"] ? "AND products.`stock_baku_2` >= {$params["min_search_quantity"]}" :
                                                                ($params["min_search_quantity"] === 0 ? "AND products.`stock_baku_2` = {$params["min_search_quantity"]}" : " ");

      $only_warehouse_query = $params["only_warehouse"] === STATUS_ACTIVE ? " AND products.`stock_ganja` = 0 AND products.`stock_baku` = 0  " : "";
    } elseif($params["warehouse_id"] && (int)$params["warehouse_id"] === WAREHOUSE_GANJA_ID) {
      $warehouse_max_query = $params["max_search_quantity"] ? "AND products.`stock_ganja` < {$params["max_search_quantity"]}" :
                                                              ($params["max_search_quantity"] === 0 ? "AND products.`stock_ganja` = {$params["max_search_quantity"]}" : " ");

      $warehouse_min_query = $params["min_search_quantity"] ? "AND products.`stock_ganja` >= {$params["min_search_quantity"]}" :
                                                              ($params["min_search_quantity"] === 0 ? "AND products.`stock_ganja` = {$params["min_search_quantity"]}" : " ");

      $only_warehouse_query = $params["only_warehouse"] === STATUS_ACTIVE ? " AND products.`stock_baku` = 0  AND products.`stock_baku_2` = 0 " : "";
    }else{
      $warehouse_max_query = $params["max_search_quantity"] ?
          "AND (products.`stock_baku` < {$params["max_search_quantity"]}
                  OR products.`stock_baku_2` < {$params["max_search_quantity"]}
                  OR products.`stock_ganja` < {$params["max_search_quantity"]} )" :
        ($params["max_search_quantity"] === 0 ?
              "AND (products.`stock_baku` = {$params["max_search_quantity"]}
                     OR products.`stock_baku_2` = {$params["max_search_quantity"]}
                     OR products.`stock_ganja` = {$params["max_search_quantity"]})" : " ");

      $warehouse_min_query = $params["min_search_quantity"] ?
          "AND (products.`stock_baku` >= {$params["min_search_quantity"]}
                OR products.`stock_baku_2` >= {$params["min_search_quantity"]}
                OR products.`stock_ganja` >= {$params["min_search_quantity"]})" :
        ($params["min_search_quantity"] === 0 ?
              "AND (products.`stock_baku` = {$params["min_search_quantity"]}
                OR products.`stock_baku_2` = {$params["min_search_quantity"]}
                OR products.`stock_ganja` = {$params["min_search_quantity"]} )" : " ");
    }

    if($params["is_dead_stock"] === STATUS_ACTIVE) {
      $params["dead_stock"] = $params["dead_stock"] ?: 180;

      $dead_stock_end_date_query = date("Y-m-d H:i:s");
      $dead_stock_start_date = date("Y-m-d", strtotime($params["now"] . " - {$params["dead_stock"]} day"));

      $dead_stock_start_date_last_six = date("Y-m-d", strtotime($params["now"] . " - 180 day"));

      $dead_stock_query = " AND
                                (IFNULL((local_query.`buying_price`),0) != IFNULL((local_query.`sale_price`),0))
                                  AND
                                (
                                  sale_cached_invoices.`operation_date` IS NULL
                                    OR
                                  sale_cached_invoices.`operation_date` < '".$dead_stock_start_date."'
                                )
                                  AND
                                (
                                  purch_cached_invoices.`operation_date` IS NULL
                                    OR
                                  purch_cached_invoices.`operation_date` < '".$dead_stock_start_date_last_six."'
                                )
                            ";
    }
    if ($params["offset"] && is_numeric($params["offset"])) {
      $offset_query = "OFFSET {$params["offset"]}";
    }


    $product_carbrand_joints_list_query = "";
    if($params["car_brand"]) {
      $product_carbrand_joints_query = $this->local_db->query("SELECT
                                                          `product_id`
                                                        FROM `".local_table_name("product_carbrand_joints")."`
                                                        WHERE `deleted_at` IS NULL
                                                        AND `carbrand_id` = {$params["car_brand"]}
                                                        ");
      $product_carbrand_joints = $product_carbrand_joints_query->result_array();

      $product_carbrand_joints_ids = [];
      foreach ($product_carbrand_joints as $key => $item) {
        if($item["product_id"]){
          $product_carbrand_joints_ids[] = $item["product_id"];
        }
      }

      if($product_carbrand_joints_ids) {
        $product_carbrand_joints_list_query = " OR products.`id` IN (". implode(",",$product_carbrand_joints_ids) .")";
      }
    }


    $keyword_cross_query = "";
    $info_double_search_code = null;
    if($params["keyword"]) {
      $cleaned_keyword = cleaned_text($params["keyword"], true);

      $product_brand_code_query = $this->local_db->query("SELECT
                                                    `id`,
                                          					`cleaned_OEM`
                                          				FROM `".local_table_name("cached_products")."`
                                          				WHERE `deleted_at` IS NULL
                                          				AND `cleaned_brand_code` = '{$cleaned_keyword}'
                                          				LIMIT 1
                                                  ");
      $product_brand_code = $product_brand_code_query->row_array();

      $info_double_search_code = $cleaned_keyword;
      if($product_brand_code_query->num_rows()){
        $info_double_search_code = $product_brand_code["cleaned_OEM"];
      }

      $params["cross_list"] = [];

      // if($params["search_type"] === SEARCH_TYPE_QUICK) {
      //
      //   $params["cross_list"] = $info_double_search_code;
      //
      //   $cross_query = (isset($product_brand_code["id"]) && $product_brand_code["id"]) ? "OR `cleaned_code` IN (SELECT
      //                                                    `cleaned_code`
      //                                                  FROM `".local_table_name("cached_product_codes")."`
      //                                                  WHERE `deleted_at` IS NULL
      //                                                  AND `product_id` = {$product_brand_code["id"]})" : "";
      //
      //   $cross_codes_list_sql = "SELECT
      //                                 `product_id`
      //                               FROM `".local_table_name("cached_product_codes")."`
      //                               WHERE `deleted_at` IS NULL
      //                               AND (`cleaned_code` LIKE '".$cleaned_keyword."%'
      //                                       OR `cleaned_code` LIKE '".$info_double_search_code."%'
      //                                       $cross_query
      //                                   )
      //                                 ";
      // } else {

        $params["cross_list"] = Dtcisct::getCodesByCode($info_double_search_code,"array");
        $params["cross_list"][] = $cleaned_keyword;

        if($product_brand_code_query->num_rows()){
          $params["cross_list"][] = $product_brand_code["cleaned_OEM"];
        }

        $cross_codes_list_sql = "SELECT
                                      `product_id`
                                    FROM `".local_table_name("cached_product_codes")."`
                                    WHERE `deleted_at` IS NULL
                                    AND (`cleaned_code` LIKE '%".$cleaned_keyword."%'
                                          OR `cleaned_code` IN ('".implode("','",$params["cross_list"])."')
                                          )
                                      ";
      // }

      $cross_codes_list_query = $this->local_db->query($cross_codes_list_sql);
      $cross_codes_list = $cross_codes_list_query->result_array();

      $code_product_ids = [];
      foreach ($cross_codes_list as $key => $item) {
        if (trim($item["product_id"])) {
          $code_product_ids[] = $item["product_id"];
        }
      }
      // $code_product_ids = array_map(function($i){return $i["product_id"];},$cross_codes_list);
      $code_product_ids = array_values(array_unique($code_product_ids));

      $cross_list_query = $code_product_ids ? " OR products.`id` IN (".implode(",", $code_product_ids).")" : "";

      $keyword_cross_query = " AND (products.`cleaned_brand_code` LIKE '{$cleaned_keyword}'
                                    OR products.`cleaned_OEM` LIKE '{$cleaned_keyword}'
                                    OR products.`name` LIKE '{$params["keyword"]}'
                                    -- OR products.`model` LIKE '{$params["keyword"]}'
                                    $cross_list_query)";
    }

    $order_by_query = $params["export"] ? " ORDER BY local_query.`description` DESC " : $filter_query;

    // AND (CASE WHEN ((IFNULL(products.`stock_baku`,0) + IFNULL(products.`stock_ganja`,0)) > 0) THEN 1
    //     ELSE products.`sale_amount` END) > 0
    if($params["apply_discount"] === STATUS_ACTIVE){
      $limit_query = "";
      $offset_query = "";
    }

    if($params["hide_price"] === STATUS_ACTIVE){
      $limit_query = "";
      $offset_query = "";
    }


    $products_sql = "SELECT
                        SQL_CALC_FOUND_ROWS local_query.`id`,
                        sale_cached_invoices.`operation_date` as last_sale_operation_date,
                        local_query.*
                     FROM (SELECT
                              products.`id`,
                              products.`remote_id`,
                              products.`stock_baku`,
                              products.`stock_baku_2`,
                              products.`stock_ganja`,
                              products.`brand_name`,
                              products.`brand_code`,
                              products.`cleaned_brand_code`,
                              products.`OEM`,
                              products.`cleaned_OEM`,
                              products.`name`,
                              products.`model`,
                              products.`currency`,
                              products.`resource`,
                              products.`final_currency_id`,
                              products.`currency_id`,
                              products.`sale_price`,
                              products.`buying_price`,
                              products.`custom_main_sale_price`,
                              products.`sale_price_description`,
                              products.`discount_price`,
                              products.`discount_rate`,
                              products.`has_discount`,
                              products.`is_b4b_price_hidden`,
                              products.`is_new_from_warehouse`,
                              products.`new_from_warehouse_start_date`,
                              products.`new_from_warehouse_end_date`,

                              products.`description`,
                              products.`stock_baku` as `maximum`,
                        (SELECT
                          cached_invoices.`id`
                        FROM `".local_table_name("cached_invoices")."` cached_invoices
                        WHERE cached_invoices.`deleted_at` IS NULL
                        AND cached_invoices.`trcode` IN (8,12,51)
                        AND products.`remote_id` = cached_invoices.`product_id`
                        AND cached_invoices.`quantity` > 0
                        ORDER BY cached_invoices.`operation_date` DESC
                        LIMIT 1) as last_sale_operation_id,
                        (SELECT
                          cached_invoices.`id`
                        FROM `".local_table_name("cached_invoices")."` cached_invoices
                        WHERE cached_invoices.`deleted_at` IS NULL
                        AND cached_invoices.`trcode` NOT IN (8,12,51)
                        AND products.`remote_id` = cached_invoices.`product_id`
                        AND cached_invoices.`quantity` > 0
                        ORDER BY cached_invoices.`operation_date` DESC
                        LIMIT 1) as last_purchase_operation_id
                      FROM `".local_table_name("cached_products")."` products
                      WHERE products.`deleted_at` IS NULL
                      AND products.`remote_is_active` = '".STATUS_DEACTIVE."'
                      $keyword_cross_query
                      $brand_query
                      $car_brand_query
                      $product_resource_query
                      $product_carbrand_joints_list_query
                      $warehouse_max_query
                      $warehouse_min_query
                      $only_warehouse_query
                      $discount_package_query
                      ) as local_query
                      LEFT JOIN `".local_table_name("cached_invoices")."` sale_cached_invoices ON sale_cached_invoices.`id` = local_query.`last_sale_operation_id`


                      LEFT JOIN `".local_table_name("cached_invoices")."` purch_cached_invoices ON purch_cached_invoices.`id` = local_query.`last_purchase_operation_id`

                      WHERE 1 > 0
                      $dead_stock_query
                      $order_by_query
                      $limit_query
                      $offset_query";
                      // $dead_stock_query
                      // var_dump($products_sql);die;
    $products_query = $this->local_db->query($products_sql);
    $products_count_query = $this->local_db->query("SELECT FOUND_ROWS() as `count`");

    $products = $products_query->result_array();
    $products_count = $products_count_query->row_array();

    $res_currencies = get_currency();
    $currencies_list = [];
    $currencies_name_list = [];
    foreach ($res_currencies as $key => $item) {
      $currencies_list[$item["id"]] = $item;
      $currencies_name_list[$item["main_name"]] = $item;
    }

    $product_codes_no_stock = [];




    foreach ($products as $key => $item) {
      // $last_sale_operation = explode("000000000000000",$item["last_sale_operation"]);

      // if ($params["is_dead_stock"] !== STATUS_ACTIVE || (
      //   $params["is_dead_stock"] === STATUS_ACTIVE &&
      //   $params["dead_stock"] &&
      //   round((time() - strtotime($last_sale_operation[0])) / (60 * 60 * 24)) < $params["dead_stock"]
      // )) {
        $products[$key]["brand"] = [
          "name" => $item["brand_name"],
          "code" => $item["brand_code"],
          "cleaned_code" => $item["cleaned_brand_code"],
        ];

        $products[$key]["last_sale_operation_date"] = $item["last_sale_operation_date"] ? date("Y-m-d",strtotime($item["last_sale_operation_date"])) : NULL;

        // $products[$key]["last_sale_operation"] = [
        //   "id" => isset($last_sale_operation[1]) ? $last_sale_operation[1] : NULL,
        //   "date" => isset($last_sale_operation[0]) && $last_sale_operation[0] > 0 ? $last_sale_operation[0] : NULL
        // ];


        $products[$key]["delivery_time"] = AVA_PRODUCT_DELIVERY_TIME;

        switch ($products[$key]["resource"]) {
          case special_codes("products.resources.ronax"):
            $products[$key]["delivery_time"] = RONAX_PRODUCT_DELIVERY_TIME;
            break;
          case special_codes("products.resources.store"):
            $products[$key]["delivery_time"] = STORE_PRODUCT_DELIVERY_TIME;
            break;
          case special_codes("products.resources.ava"):
            $products[$key]["delivery_time"] = AVA_PRODUCT_DELIVERY_TIME;
            break;
        }

        $products[$key]["converted_currency"] = CURRENCY_AZN;
        // if(isset($item["final_currency_id"])) {
        //   $products[$key]["currency"] = isset($currencies_list[$item["final_currency_id"]]) ? $currencies_list[$item["final_currency_id"]]["main_name"] : NULL;
        //   $products[$key]["converted_sale_price"] = isset($currencies_list[$item["final_currency_id"]]) ? $item["sale_price"] * $currencies_list[$item["final_currency_id"]]["value"] : NULL;
        // } else {
        //   $products[$key]["converted_sale_price"] = isset($currencies_name_list[$item["currency"]]) ? $item["sale_price"] * $currencies_name_list[$item["currency"]]["value"] : NULL;
        // }

        // $products[$key]["resource"] = array_search($products[$key]["resource"], special_codes("products.resources"));

        if(!(int)$item["stock_baku"] && !(int)$item["stock_ganja"] && $item["cleaned_brand_code"]) {
          $product_codes_no_stock[] = $item["cleaned_brand_code"];
        }

        $products[$key]["OEM"] = b2bOemShower($item["cleaned_OEM"],true,$params["keyword"],true,100);

        unset($products[$key]["brand_name"]);
        unset($products[$key]["brand_code"]);
      // } else {
        // unset($products[$key]);
      // }
    }

    // $products = array_values($products);

    $products_count["count"] = $products_count["count"];
    // if($params["offset"] + $page_load_limit >= $products_count["count"]) {
    //   $store_products_list = array_values($store_products_list);
    //   $ronax_products_list = array_values($ronax_products_list);
    //
    //   $products = array_merge($products, $ronax_products_list);
    //   $products = array_merge($products, $store_products_list);
    // }

    if(!$products) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Product not found")
      );
    }

    $cart_list = [];
    // $product_ids = array_map(function($i){
    //   return $i["id"];
    // },$products);

    $res_currencies = get_currency();
    $currencies_list = [];
    foreach ($res_currencies as $key => $item) {
      $currencies_list[$item["main_name"]] = $item;
    }

    foreach ($products as $key => $item) {

      $products[$key]["main_currency"] = CURRENCY_AZN;
      if(isset($currencies_list[CURRENCY_AZN]) && ((int)$currencies_list[CURRENCY_AZN]["id"] === (int)$item["currency_id"])) {
        $products[$key]["main_sale_price"] = $item["sale_price"];
      } else {
        # if eur prod only
        $products[$key]["main_sale_price"] = isset($currencies_list[CURRENCY_AZN]["value"]) ? $item["sale_price"] / $currencies_list[CURRENCY_AZN]["value"] : NULL;
      }

      $sale_price_for_discount = $item["custom_main_sale_price"] ?: $item["sale_price"];
      $products[$key]["discount_price"] = $item["has_discount"] === STATUS_ACTIVE ? $item["discount_price"] :
                                                      $sale_price_for_discount - ($sale_price_for_discount * ($params["discount_rate"]/100));

      $products[$key]["discount_price"] = round($products[$key]["discount_price"], 2);
      $products[$key]["is_b4b_price_hidden"] = $item["is_b4b_price_hidden"] === STATUS_ACTIVE;

      $products[$key]["is_new_from_warehouse"] = $item["is_new_from_warehouse"] === STATUS_ACTIVE;
      $products[$key]["new_from_warehouse_start_date"] = $item["new_from_warehouse_start_date"] ? date("Y-m-d", strtotime($item["new_from_warehouse_start_date"])) : NULL;
      $products[$key]["new_from_warehouse_end_date"] = $item["new_from_warehouse_end_date"] ? date("Y-m-d", strtotime($item["new_from_warehouse_end_date"])) : NULL;
    }

    if($params["hide_price"] === STATUS_ACTIVE){
      foreach ($products as $key => $item) {
        $update_list[] = [
          "id" => $item["id"],
          "is_b4b_price_hidden" => $params["hide_price"],
        ];
      }
      $this->local_db->update_batch(local_table_name("cached_products"),$update_list,"id");

      return rest_response(
        Status_codes::HTTP_ACCEPTED,
        lang("Success")
      );
    }

    if($params["apply_discount"] === STATUS_ACTIVE){
      $discount_rate = $params["discount_rate"];

      $this->local_db->insert(local_table_name("product_dicsount_packages"),[
        "name"=> $params["name"],
        "product_count" => count($products),
        "discount_rate" => $discount_rate,
        "operation_date" => $params["operation_date"],
        "params" => json_encode([
          "keyword" => $params["keyword"],
          "brand" => $params["brand"],
          "filter" => $params["filter"],
          "search_type" => $params["search_type"],
          "car_brand" => $params["car_brand"],
          "model" => $params["model"],
          "in_stock" => $params["in_stock"],
          "cr_search" => $params["cr_search"],
          "is_dead_stock" => $params["is_dead_stock"],
          "offset" => $params["offset"],
          "export" => $params["export"],
          "dead_stock" => $params["dead_stock"],
          "warehouse_id" => $params["warehouse_id"],
          "min_search_quantity" => $params["min_search_quantity"],
          "max_search_quantity" => $params["max_search_quantity"],
          "discount_package_id" => $params["discount_package_id"],
        ]),
      ]);
      $insert_id = $this->local_db->insert_id();
      $code = str_pad($insert_id, 8, "0", STR_PAD_LEFT);

      $this->local_db->where("id", $insert_id);
      $this->local_db->update(local_table_name("product_dicsount_packages"), ["code" => $code]);

      foreach ($products as $key => $item) {
        $update_list[] = [
          "id" => $item["id"],
          "discount_price" => $item["discount_price"],
          "discount_rate" => $discount_rate,
          "has_discount" => STATUS_ACTIVE,
          "discount_package_id" => $insert_id,
        ];
      }
      $this->local_db->update_batch(local_table_name("cached_products"),$update_list,"id");

      return rest_response(
        Status_codes::HTTP_ACCEPTED,
        lang("Success")
      );
    }


    # sorting with php for test and preventing additional select
    if($params["filter"] === FILTER_CART_ASC) {
      usort($products,function($item1,$item2){
        return ($item1['in_cart'] > $item2['in_cart']) ? 1 : -1;
      });
    } elseif ($params["filter"] === FILTER_CART_DESC) {
      usort($products,function($item1,$item2){
        return ($item1['in_cart'] < $item2['in_cart']) ? 1 : -1;
      });
    }

    if($params["filter"] === FILTER_PRICE_AZN_ASC) {
      usort($products,function($item1,$item2){
        return ($item1['main_sale_price'] > $item2['main_sale_price']) ? 1 : -1;
      });
    } elseif ($params["filter"] === FILTER_PRICE_AZN_DESC) {
      usort($products,function($item1,$item2){
        return ($item1['main_sale_price'] < $item2['main_sale_price']) ? 1 : -1;
      });
    }

    if ($params["export"]) {

      $order_template_file = 'ProductTemplate.xlsx';
      $phpExcel = PHPExcel_IOFactory::load(APPPATH ."..". DIRECTORY_SEPARATOR ."assets". DIRECTORY_SEPARATOR ."manager". DIRECTORY_SEPARATOR ."templates". DIRECTORY_SEPARATOR .$order_template_file);

      $phpExcel->getProperties()->setCreator("Products")
                                  ->setLastModifiedBy("Products")
                                  ->setTitle("Products")
                                  ->setSubject("Products")
                                  ->setDescription("Products");

      $writer = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");
      $sheet = $phpExcel ->getActiveSheet();

      $j=2;
      $number = 0;
      foreach ($products as $key => $product) {
        $number++;
        $brand 			      = $product['brand']['name'];
        $brand_code		    = $product['brand']['code'];
        $oem	            = $product['OEM'] ? implode(" ", $product['OEM']) : "";
        $product_name		  = $product['name'];
        $description		  = $product['description'];
        $model		        = $product['model'];
        $baku		          = $product['stock_baku'];
        $baku_2		          = $product['stock_baku_2'];
        $ganja		        = $product['stock_ganja'];
        $delivery_time		= $product['delivery_time'];
        $sale_price 			= $product["custom_main_sale_price"] ? round($product['custom_main_sale_price'], 2) : round($product['sale_price'], 2);
        $discount_price 	= $product['has_discount'] ? round($product['discount_price'], 2) : "";
        $currency 		  	= $product["custom_main_sale_price"] ? $product["main_currency"] : $product['currency'];

        $phpExcel->setActiveSheetIndex(0)
          ->setCellValueExplicit("A$j", $number, 					PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("B$j", $brand,			      PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("C$j", $brand_code,		  PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("D$j", $oem,	            PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("E$j", $product_name, 		PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("F$j", $description,		  PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("G$j", $model,		        PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("H$j", $baku, 			      PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("I$j", $baku_2, 			      PHPExcel_Cell_DataType::TYPE_STRING)

          ->setCellValueExplicit("K$j", $ganja, 	        PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("K$j", $delivery_time, 	PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("L$j", $sale_price, 	    PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("M$j", $discount_price, 	PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("N$j", $currency, 	      PHPExcel_Cell_DataType::TYPE_STRING);

        $j++;
      }

      $file = (($params["brand"] . "-") ?: null) . ($params["keyword"] ?: null) ."_". date('d_m_Y_His').'.xlsx';
      $path = DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "manager" . DIRECTORY_SEPARATOR . "_temp" . DIRECTORY_SEPARATOR . "products" . DIRECTORY_SEPARATOR . $file;
      $filepath = APPPATH . "..". $path;
      $writer->save($filepath);

      $insert_list = [
        "system_user_id" => $params["userid"],
        "data_type" => "manager_products",
        "file_name" => $file,
        "file_type" => "xlsx",
        "params" => json_encode($params),
        "operation_date" => $params["operation_date"],
      ];

      $this->local_db->insert(local_table_name("file_export_history"),$insert_list);

      return rest_response(
        Status_codes::HTTP_OK,
        lang("Success"),
        [
          "url" => $path,
        ]
      );
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => $products_count["count"],
        "list" => $products,
      ]
    );
  }

  public function tecdocCrosses($params)
  {
    escapeAllKeys($params,null,$this->local_db);
    $cleaned_keyword = cleaned_text($params["keyword"], true);


    # TecDoc Cross Information
    $result_codes_list = [];
    $code_count = 0;
    if($params["keyword"] && $cleaned_keyword) {
      $result_codes = Dtcisct::getCrossReference($cleaned_keyword, "array");
      $code_count = count($result_codes);
      foreach ($result_codes as $key => $item) {
        $result_codes_list[$item["oembrand"]][] = [
          "number" => $item["number"],
  				"brand" => $item["brand"],
  				"oembrand"	 	=> $item["oembrand"],
  				"oemnumber" => $item["oemnumber"],
  				"group" => $item["group"],
  				"product" => $item["product"]
        ];
      }
      # sorting by alpahbetic order
      ksort($result_codes_list);
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "counts" => [
          "brands" => count($result_codes_list),
          "codes" => $code_count,
        ],
        "list" => $result_codes_list,
      ]
    );
  }

  public function comments($params){
    escapeAllKeys($params,null,$this->local_db);
    $comments_sql = "SELECT
                            comments.`id`,
                            comments.`entry_product_comment`,
                            comments.`operation_date`,
                            companies.`company_name`
                          FROM `".local_table_name("b4b_products_comments")."` comments
                          LEFT JOIN `".local_table_name("b4b_companies")."` AS companies
                               ON companies.`company_id` = comments.`entry_id`
                               AND companies.`deleted_at` IS NULL
                          WHERE comments.`deleted_at` IS NULL
                          AND comments.`product_id` = {$params["product_id"]}
                          ";

    $comments_query = $this->local_db->query($comments_sql);
    if(!$comments_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Not any comment")
      );
    }

    $comments = $comments_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success")
      ,
      [
        "count" => count($comments),
        "list" => $comments,
      ]
    );
  }

  public function priceOffers($params){
    escapeAllKeys($params,null,$this->local_db);
    $price_offers_sql = "SELECT
                            price_requests.`id`,
                            price_requests.`company_name`,
                            price_requests.`price`,
                            price_requests.`operation_date`,
                            companies.`company_name` as entry_name
                          FROM `".local_table_name("b4b_entry_price_requests")."` price_requests
                          LEFT JOIN `".local_table_name("b4b_companies")."` AS companies
                               ON companies.`company_id` = price_requests.`entry_id`
                               AND companies.`deleted_at` IS NULL
                          WHERE price_requests.`deleted_at` IS NULL
                          AND price_requests.`product_id` = {$params["product_id"]}
                          ";

    $price_offers_query = $this->local_db->query($price_offers_sql);
    if(!$price_offers_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Not any price offers")
      );
    }

    $price_offers = $price_offers_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success")
      ,
      [
        "count" => count($price_offers),
        "list" => $price_offers,
      ]
    );
  }

}
