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
        $filter_query = " ORDER BY products.`stock_baku` ASC";
        break;
      case FILTER_STOCK_BAKU_DESC:
        $filter_query = " ORDER BY products.`stock_baku` DESC";
        break;
      case FILTER_STOCK_BAKU_2_ASC:
        $filter_query = " ORDER BY products.`stock_baku_2` ASC";
        break;
      case FILTER_STOCK_BAKU_2_DESC:
        $filter_query = " ORDER BY products.`stock_baku_2` DESC";
        break;
      case FILTER_STOCK_GANJA_ASC:
        $filter_query = " ORDER BY products.`stock_ganja` ASC";
        break;
      case FILTER_STOCK_GANJA_DESC:
        $filter_query = " ORDER BY products.`stock_ganja` DESC";
        break;
      case FILTER_PRICE_EUR_ASC:
        $filter_query = " ORDER BY products.`sale_price` ASC";
        break;
      case FILTER_PRICE_EUR_DESC:
        $filter_query = " ORDER BY products.`sale_price` DESC";
        break;
      case FILTER_BRAND_NAME_ASC:
        $filter_query = " ORDER BY products.`brand_name` ASC";
        break;
      case FILTER_BRAND_NAME_DESC:
        $filter_query = " ORDER BY products.`brand_name` DESC";
        break;
      case FILTER_PRODUCT_NAME_ASC:
        $filter_query = " ORDER BY products.`name` ASC";
        break;
      case FILTER_PRODUCT_NAME_DESC:
        $filter_query = " ORDER BY products.`name` DESC";
        break;
      default:
        $filter_query = " ORDER BY products.`stock_baku` ASC";
        break;
    }

    $brand_query = $params["brand"] ? " AND products.`brand_name` = '{$params["brand"]}'" : "";
    $car_brand_query = $params["car_brand"] ? " AND products.`carbrand_id` = '{$params["car_brand"]}'" : "";
    $brand_query = $params["brand"] ? " AND products.`brand_id` = '{$params["brand"]}'" : "";
    $in_stock_query = $params["in_stock"] ? " AND products.`stock_baku` > 0" : "";
    $show_discount_query = $params["show_discount"] ? " AND products.`has_discount` = '".STATUS_ACTIVE."'
                                                        AND products.`is_b4b_price_hidden` = '".STATUS_DEACTIVE."' " : "";
    $offset_query = "";
    if ($params["offset"] && is_numeric($params["offset"])) {
      $offset_query = "OFFSET {$params["offset"]}";
    }

    $product_carbrand_joints_list_query = "";
    if($params["car_brand"]) {
      $product_carbrand_joints_query = $this->local_db->query("SELECT
                                                                  `product_id`
                                                               FROM `".local_table_name("product_carbrand_joints")."`
                                                               WHERE `deleted_at` IS NULL
                                                               AND `carbrand_id` = {$params["car_brand"]}");
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

      if($params["search_type"] === SEARCH_TYPE_QUICK) {

        $params["cross_list"] = $info_double_search_code;

        $cross_query = (isset($product_brand_code["id"]) && $product_brand_code["id"]) ? "OR `cleaned_code` IN (SELECT
                                                         `cleaned_code`
                                                       FROM `".local_table_name("cached_product_codes")."`
                                                       WHERE `deleted_at` IS NULL
                                                       AND `product_id` = {$product_brand_code["id"]})" : "";

        $cross_codes_list_sql = "SELECT
                                     `product_id`
                                 FROM `".local_table_name("cached_product_codes")."`
                                 WHERE `deleted_at` IS NULL
                                 AND (`cleaned_code` LIKE '".$cleaned_keyword."%'
                                         OR `cleaned_code` LIKE '".$info_double_search_code."%'
                                         $cross_query)";
      } else {

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
      }

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

    $order_by_query = $params["export"] ? " ORDER BY products.`description` DESC " : $filter_query;
    $resource_store = special_codes("products.resources.store");

    $store_show_query = $params["is_store_active"] === STATUS_ACTIVE ? " " : " AND products.`resource` != '".$resource_store."' ";

    // AND (CASE WHEN ((IFNULL(products.`stock_baku`,0) + IFNULL(products.`stock_ganja`,0)) > 0) THEN 1
    //     ELSE products.`sale_amount` END) > 0

    $products_body_sql = "FROM `".local_table_name("cached_products")."` products
                          WHERE products.`deleted_at` IS NULL
                          AND products.`remote_is_active` = '".STATUS_DEACTIVE."'
                          AND products.`is_b4b_active` = '".STATUS_ACTIVE."'
                          $keyword_cross_query
                          $brand_query
                          $car_brand_query
                          $show_discount_query
                          $store_show_query
                          $product_carbrand_joints_list_query
                          ";
    $products_sql = "SELECT
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
                        IFNULL(products.`final_currency_id`,products.`currency_id`) as `final_currency_id`,
                        IFNULL(products.`custom_main_sale_price`,products.`sale_price`) as `sale_price`,
                        products.`sale_price_description`,
                        products.`resource`,
                        products.`description`,
                        products.`stock_baku` as `maximum`,
                        products.`has_discount`,
                        products.`discount_price`,
                        products.`is_b4b_price_hidden`
                      $products_body_sql
                      $order_by_query
                      $limit_query
                      $offset_query
                      ";

                      // var_dump($products_sql);die;
    $products_query = $this->local_db->query($products_sql);
    $products_count_query = $this->local_db->query("SELECT COUNT(1) as count $products_body_sql");

    $products = $products_query->result_array();
    $products_count = $products_count_query->row_array();

    $product_codes_no_stock = [];
    foreach ($products as $key => $item) {
      $products[$key]["brand"] = [
        "name" => $item["brand_name"],
        "code" => $item["brand_code"],
        "cleaned_code" => $item["cleaned_brand_code"],
      ];

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

      $products[$key]["OEM"] = b2bOemShower($item["cleaned_OEM"],true,$params["keyword"],true);
      // $products[$key]["OEM_is_array"] = true;

      // $products[$key]["resource"] = array_search($products[$key]["resource"], special_codes("products.resources"));

      if(!(int)$item["stock_baku"] && !(int)$item["stock_ganja"] && $item["cleaned_brand_code"]) {
        $product_codes_no_stock[] = $item["cleaned_brand_code"];
      }

      unset($products[$key]["brand_name"]);
      unset($products[$key]["brand_code"]);
    }

    # RONAX PRODUCTS
    $ronax_products_list = [];
    if($params["keyword"] && $info_double_search_code) {
      $ronax_products = Ronax::search(["search_code" => $info_double_search_code]);
      // $ronax_products = Dtcisct::getRonaxProducts($info_double_search_code,"array");

      $config_sql = "SELECT
                      `value`
                      FROM `".local_table_name("b4b_configs")."` config
                      WHERE config.`deleted_at` IS NULL
                      AND config.`is_active`= '" . STATUS_ACTIVE . "'
                      AND config.`key`='b4b_brandname_blacklist'
                      LIMIT 1";
        $config_query = $this->local_db->query($config_sql);
        $config_row = $config_query->row_array();
        $brandname_blacklist = [];
        if (@$config_row["value"]){
          $brandname_blacklist =explode(",",$config_row["value"]);
        }

      foreach ($ronax_products as $key => $item) {
        $id = $item["productid"];
        if (!in_array(trim($item["productname"]),["Aftermarket Stock (14-21 days)"]) && !in_array(strtolower($item["productname"]),$brandname_blacklist) ) {
          $ronax_products_list[$id . "-" . $item["partid"]] = [
            "id"                     => $id,
            "remote_id"              => $item["productid"],
            "resource"               => special_codes("products.resources.ronax"),
            "stock_baku"             => null,
            "stock_baku_2"           => null,
            "stock_ganja"            => null,
            "maximum"                => null,
            "in_cart"                => 0,
            "brand"                  => [
              "name"          => $item["productname"],
              "code"          => $item["partnr"],
              "cleaned_code"  => cleaned_text($item["partnr"], true),
            ],
            "OEM"                   => b2bOemShower(cleaned_text($item["partnr"]),true,$params["keyword"],true),
            "cleaned_OEM"           => cleaned_text($item["partnr"]),
            "name"                  =>  $item["partname"],
            "model"                 => null,
            "currency"              => CURRENCY_EUR,
            "sale_price"            => $item["price"] + ($item["price"] * RONAX_PRICE_RATE),
            "description"           => null,
            "delivery_time"         => $item["deliveryday"],
          ];
        } else {
          unset($ronax_products_list[$id . "-" . $item["partid"]]);
        }
      }
    }

    # STORE SEARCH PRODUCTS
    $store_products_list = [];
    // if($params["keyword"] && $info_double_search_code) {
    //   $store_products = Dtcisct::getStoreProducts($info_double_search_code,"array");
    //   foreach ($store_products as $key => $item) {
    //     $id = (isset($item["file_id"]) ? $item["file_id"] : "").(isset($item["id"]) ? $item["id"] : "");
    //     $store_products_list[$id] = [
    //       "id" => $id,
    //       "remote_id" => $id,
    //       "resource" => special_codes("products.resources.store"),
    //       "stock_baku" => null,
    //       "stock_ganja" => null,
    //       "maximum" => null,
    //       "in_cart" => 0,
    //       "brand" => [
    //         "name" => isset($item["brand"]) ? $item["brand"] : null,
    //         "code" => isset($item["brand_code"]) ? $item["brand_code"] : null,
    //         "cleaned_code" => isset($item["brand_code_clean"]) ? cleaned_text($item["brand_code_clean"], true) : null,
    //       ],
    //       "OEM"                   => b2bOemShower(cleaned_text((isset($item["oem_code_clean"]) ? $item["oem_code_clean"] : null)),true,$params["keyword"],true),
    //       "cleaned_OEM"           => cleaned_text((isset($item["oem_code_clean"]) ? $item["oem_code_clean"] : null)),
    //
    //       "name" => isset($item["product_name"]) ? $item["product_name"] : null,
    //       "model" => null,
    //       "currency" => CURRENCY_EUR,
    //       "sale_price" => isset($item["product_price"]) ? $item["product_price"] : null,
    //       "description" => isset($item["marka"]) ? $item["marka"] : null,
    //       "delivery_time" => STORE_PRODUCT_DELIVERY_TIME,
    //     ];
    //   }
    // }

    $products_count["count"] = $products_count["count"] + count($ronax_products_list) + count($store_products_list);
    if($params["offset"] + $page_load_limit >= $products_count["count"]) {
      $store_products_list = array_values($store_products_list);
      $ronax_products_list = array_values($ronax_products_list);

      $products = array_merge($products, $ronax_products_list);
      $products = array_merge($products, $store_products_list);
    }

    if(!$products) {
      $this->local_db->insert(local_table_name("b4b_searched_keywords"),[
        "search_company_id" => $params["b4buserid"],
        "search_ava_id" => $params["remoteb4buserid"],
        "remote_customer_id" => $params["remoteb4buserid"],
        "search_code" => $params["keyword"],
        "search_brand" => $params["brand"],
        "search_marka" => $params["car_brand"],
        "search_model" => $params["model"],
        "search_user_ip" => $params["entry_ip"],
        "result_count" => 0,
      ]);
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Product not found")
      );
    }

    $cart_list = [];
    $product_ids = array_map(function($i){
      return $i["id"];
    },$products);

    $remote_customer_query = $params["remoteb4buserid"] ? " AND `remote_customer_id` = {$params["remoteb4buserid"]}" : "";

    if($params["is_local"]) {
      $cart_sql = "SELECT
                       `basket_id`,
                       `basket_product_id`,
                       `basket_from`,
                       `resource`,
                       `basket_brend_code` as `brand_code`,
                       `basket_quantity`
                    FROM `".local_table_name("b4b_cart")."`
                    WHERE `deleted_at` IS NULL
                    AND `basket_product_id` IN ('".implode("','",$product_ids)."')
                    AND `basket_company_id` = {$params["b4buserid"]}
                    $remote_customer_query";

      $cart_query = $this->local_db->query($cart_sql);
      $carts = $cart_query->result_array();

      foreach ($carts as $key => $item) {
       if($item["resource"] === special_codes("products.resources.ava")) {
         $cart_list[$item["basket_product_id"]] = [
           "quantity" => $item["basket_quantity"],
           "cart_id" => $item["basket_id"],
         ];
       } else {
         $cart_list[$item["basket_product_id"] . $item["brand_code"]] = [
           "quantity" => $item["basket_quantity"],
           "cart_id" => $item["basket_id"],
         ];;
       }
      }
    }

    $res_currencies = get_currency();
    $currencies_list = [];
    $currencies_name_list = [];

    foreach ($res_currencies as $key => $item) {
      $currencies_list[$item["id"]] = $item;
      $currencies_name_list[$item["main_name"]] = $item;
    }

    foreach ($products as $key => $item) {
      if($item["resource"] === special_codes("products.resources.ava")){
        $products[$key]["in_cart"] = isset($cart_list[$item["id"]]) ? (int)$cart_list[$item["id"]]["quantity"] : 0;
        $products[$key]["cart_id"] = isset($cart_list[$item["id"]]) ? (int)$cart_list[$item["id"]]["cart_id"] : NULL;
      } else {
        $products[$key]["in_cart"] = isset($cart_list[$item["id"] . $item["brand"]["code"]]) ? (int)$cart_list[$item["id"] . $item["brand"]["code"]]["quantity"] : 0;
        $products[$key]["cart_id"] = isset($cart_list[$item["id"] . $item["brand"]["code"]]) ? (int)$cart_list[$item["id"] . $item["brand"]["code"]]["cart_id"] : NULL;
      }

      if($params["export"] && $item["resource"] === special_codes("products.resources.ava")){
        $products[$key]["product_stock"] = $item["stock_ganja"] + $item["stock_baku"] + $item["stock_baku_2"];
      }

      $products[$key]["converted_currency"] = CURRENCY_AZN;

      $products[$key]["is_b4b_price_hidden"] = isset($item["is_b4b_price_hidden"]) ? ($item["is_b4b_price_hidden"] === STATUS_ACTIVE) : NULL;

      $products[$key]["add_to_cart"] = $item["sale_price"] ? STATUS_ACTIVE : STATUS_DEACTIVE;

      $products[$key]["add_to_cart"] = $products[$key]["add_to_cart"] === STATUS_ACTIVE;

      $products[$key]["sale_price"] = $products[$key]["is_b4b_price_hidden"] ? null : $item["sale_price"];

      $products[$key]["discount_price"] = isset($products[$key]["has_discount"]) &&
                                          isset($products[$key]["discount_price"]) &&
                                          ($products[$key]["has_discount"] === STATUS_ACTIVE) ? $products[$key]["discount_price"] : NULL;

      $products[$key]["converted_discount_price"] = isset($products[$key]["has_discount"]) &&
                                                    isset($products[$key]["discount_price"]) &&
                                                    ($products[$key]["has_discount"] === STATUS_ACTIVE) ?
                                                    (isset($currencies_name_list[$item["currency"]]) ?
                                                    $item["discount_price"] * $currencies_name_list[$item["currency"]]["value"] :
                                                    NULL) : NULL;

      if(isset($item["final_currency_id"])) {
        $products[$key]["currency"] = isset($currencies_list[$item["final_currency_id"]]) ? $currencies_list[$item["final_currency_id"]]["main_name"] : NULL;
        $products[$key]["converted_sale_price"] = isset($currencies_list[$item["final_currency_id"]]) ?
                                                  $item["sale_price"] * $currencies_list[$item["final_currency_id"]]["value"] :
                                                  NULL;
      } else {
        $products[$key]["converted_sale_price"] = isset($currencies_name_list[$item["currency"]]) ?
                                                  $item["sale_price"] * $currencies_name_list[$item["currency"]]["value"] :
                                                  NULL;

        // $products[$key]["converted_discount_price"] = isset($products[$key]["has_discount"]) &&
        //                                               isset($products[$key]["discount_price"]) &&
        //                                               ($products[$key]["has_discount"] === STATUS_ACTIVE) ?
        //                                               (isset($currencies_name_list[$item["currency"]]) ?
        //                                               $item["discount_price"] * $currencies_name_list[$item["currency"]]["value"] :
        //                                               NULL) : NULL;
      }

      $stock_show = $params["is_local"] ? $params["auth_user"]["stock_show"] : false;
      $products[$key]["stock_baku"]   = stock_visibility($item["resource"],$stock_show,$item["stock_baku"]);
      $products[$key]["stock_baku_2"] = stock_visibility($item["resource"],$stock_show,$item["stock_baku_2"]);
      $products[$key]["stock_ganja"]  = stock_visibility($item["resource"],$stock_show,$item["stock_ganja"]);

      $products[$key]["has_discount"] = isset($item["has_discount"]) ? $item["has_discount"] === STATUS_ACTIVE : NULL;
    }

    // $products = array_values($products);
    $this->local_db->insert(local_table_name("b4b_searched_keywords"),[
      "search_company_id" => $params["b4buserid"],
      "search_ava_id" => $params["remoteb4buserid"],
      "remote_customer_id" => $params["remoteb4buserid"],
      "search_code" => $params["keyword"],
      "search_brand" => $params["brand"],
      "search_marka" => $params["car_brand"],
      "search_model" => $params["model"],
      "search_user_ip" => $params["entry_ip"],
      "result_count" => $products_count["count"],
    ]);

    if ($params["export"]) {

      $order_template_file = 'ProductTemplate.xlsx';
      $phpExcel = PHPExcel_IOFactory::load(APPPATH ."..". DIRECTORY_SEPARATOR ."assets". DIRECTORY_SEPARATOR ."b4b". DIRECTORY_SEPARATOR ."templates". DIRECTORY_SEPARATOR .$order_template_file);

      $phpExcel->getProperties()->setCreator("Products")
                                  ->setLastModifiedBy("Products")
                                  ->setTitle("Products")
                                  ->setSubject("Products")
                                  ->setDescription("Products");

      $writer = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");
      $sheet = $phpExcel ->getActiveSheet();

      $j=2;
      $number = 0;
      $brand = null;
      foreach ($products as $key => $product) {
        if(isset($product["product_stock"]) && $product["product_stock"]){
          $number++;
          $brand 			      = $product['brand']['name'];
          $brand_code		    = $product['brand']['code'];
          $oem	            = $product['OEM'] ? implode(" ", $product['OEM']) : "";
          $product_name		  = $product['name'];
          $description		  = $product['description'];
          $model		        = $product['model'];
          $baku		          = $product['stock_baku']['title'];
          $baku_2		        = $product['stock_baku_2']['title'];
          $ganja		        = $product['stock_ganja']['title'];
          $delivery_time		= $product['delivery_time'];
          $sale_price 			= round($product['sale_price'], 2);

          $phpExcel->setActiveSheetIndex(0)
            ->setCellValueExplicit("A$j", $number, 					PHPExcel_Cell_DataType::TYPE_NUMERIC)
            ->setCellValueExplicit("B$j", $brand,			      PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("C$j", $brand_code,		  PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("D$j", $oem,	            PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("E$j", $product_name, 		PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("F$j", $description,		  PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("G$j", $model,		        PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("H$j", $baku, 			      PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("I$j", $baku_2, 			    PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("J$j", $ganja, 	        PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("K$j", $delivery_time, 	PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("L$j", $sale_price, 	    PHPExcel_Cell_DataType::TYPE_NUMERIC);
            // ->setCellValueExplicit("L$j", $main_price, 	    PHPExcel_Cell_DataType::TYPE_NUMERIC);

          $j++;
        }
        unset($product["product_stock"]);
      }

      $file = ($brand ?: lang("without_brand")) ."_". date('d_m_Y_His').'.xlsx';
      $path = DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "b4b" . DIRECTORY_SEPARATOR . "_temp" . DIRECTORY_SEPARATOR . "products" . DIRECTORY_SEPARATOR . $file;
      $filepath = APPPATH . "..". $path;
      $writer->save($filepath);

      $insert_list = [
        "entry_id" => $params["b4buserid"],
        "key" => "products",
        "name" => $file,
        "reference_count" => $products_count["count"],
      ];

      $this->local_db->insert(local_table_name("b4b_exports"),$insert_list);
      $insert_id = $this->local_db->insert_id();
      $code = str_pad($insert_id, 8, '0', STR_PAD_LEFT);
      $this->local_db->where("id",$insert_id)->update(local_table_name("b4b_exports"),["code" => $code]);

      return rest_response(
        Status_codes::HTTP_OK,
        lang("Success"),
        [
          "url" => $path,
          "products" => $products
        ]
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
                     AND comments.entry_id = {$params["b4buserid"]}";

    $comments_query = $this->local_db->query($comments_sql);
    if(!$comments_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Not any comment")
      );
    }

    $comments = $comments_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => count($comments),
        "list" => $comments,
      ]
    );
  }

  public function addComments($params){
    escapeAllKeys($params,null,$this->local_db);
    $check_product_sql = "SELECT
                              product.`id`
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

    $insert_list = [
      "entry_id" => $params["b4buserid"],
      "product_id" => $params["product_id"],
      "entry_product_comment" => $params["entry_product_comment"],
      "operation_date" => $params["operation_date"]
    ];


    $this->local_db->insert(local_table_name("b4b_products_comments"),$insert_list);

    $last_inserted_id = $this->local_db->insert_id();

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
                          AND comments.`id` = {$last_inserted_id}
                          ";

    $comments_query = $this->local_db->query($comments_sql);
    if(!$comments_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Comment not found")
      );
    }

    $comments = $comments_query->row_array();

    return rest_response(
      Status_codes::HTTP_CREATED,
      lang("Success"),
      [
        "count" => count($comments),
        "list" => $comments,
      ]
    );
  }

  public function addPriceOffer($params){
    escapeAllKeys($params,null,$this->local_db);
    $check_product_sql = "SELECT
                              product.`id`
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

    $insert_list = [
      "entry_id" => $params["b4buserid"],
      "customer_id" => $params["remoteb4buserid"],
      "product_id" => $params["product_id"],
      "price" => $params["price_offer"],
      "company_name" => $params["company_name"],
      "operation_date" => $params["operation_date"]
    ];


    $this->local_db->insert(local_table_name("b4b_entry_price_requests"),$insert_list);

    // $last_inserted_id = $this->local_db->insert_id();
    // $price_offers_sql = "SELECT
    //                         price_offers.`id`,
    //                         price_offers.`entry_product_price_offer`,
    //                         price_offers.`operation_date`
    //                       FROM `".local_table_name("b4b_entry_price_requests")."` price_offers
    //                       LEFT JOIN `".local_table_name("b4b_companies")."` AS companies
    //                            ON companies.`company_id` = comments.`entry_id`
    //                            AND companies.`deleted_at` IS NULL
    //                       WHERE price_offers.`deleted_at` IS NULL
    //                       AND price_offers.`id` = {$last_inserted_id}
    //                       ";
    //
    // $price_offers_query = $this->local_db->query($price_offers_sql);
    // if(!$price_offers_query->num_rows()){
    //   return rest_response(
    //     Status_codes::HTTP_BAD_REQUEST,
    //     lang("There is not offered price for this product")
    //   );
    // }
    //
    // $price_offers = $price_offers_query->row_array();

    return rest_response(
      Status_codes::HTTP_CREATED,
      lang("Success"),
    );
  }

  public function newFromWarehouses($params)
  {
    $products_sql = "SELECT
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
                         IFNULL(products.`final_currency_id`,products.`currency_id`) as `final_currency_id`,
                         IFNULL(products.`custom_main_sale_price`,products.`sale_price`) as `sale_price`,
                         products.`sale_price_description`,
                         products.`resource`,
                         products.`description`,
                         products.`stock_baku` as `maximum`,
                         products.`has_discount`,
                         products.`discount_price`,
                         products.`new_from_warehouse_image`,
                         products.`is_b4b_price_hidden`
                      FROM `".local_table_name("cached_products")."` products
                      WHERE products.`deleted_at` IS NULL
                      AND products.`remote_is_active` = '".STATUS_DEACTIVE."'
                      AND products.`is_b4b_active` = '".STATUS_ACTIVE."'
                      AND products.`is_new_from_warehouse` = '".STATUS_ACTIVE."'
                      AND products.`new_from_warehouse_start_date` <= '{$params["current_date"]}'
                      AND products.`new_from_warehouse_end_date` >= '{$params["current_date"]}'
                      ORDER BY products.`updated_at` DESC
                      ";

    $products_query = $this->local_db->query($products_sql);
    if(!$products_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No products")
      );
    }
    $products = $products_query->result_array();

    $cart_list = [];
    $product_ids = array_map(function($i){
      return $i["id"];
    },$products);

    $remote_customer_query = $params["remoteb4buserid"] ? " AND `remote_customer_id` = {$params["remoteb4buserid"]}" : "";

    $cart_sql = "SELECT
                    `basket_id`,
                    `basket_product_id`,
                    `basket_from`,
                    `resource`,
                    `basket_brend_code` as `brand_code`,
                    `basket_quantity`
                 FROM `".local_table_name("b4b_cart")."`
                 WHERE `deleted_at` IS NULL
                 AND `basket_product_id` IN ('".implode("','",$product_ids)."')
                 AND `basket_company_id` = {$params["b4buserid"]}
                 $remote_customer_query";

    $cart_query = $this->local_db->query($cart_sql);
    $carts = $cart_query->result_array();

    foreach ($carts as $key => $item) {
     if($item["resource"] === special_codes("products.resources.ava")) {
       $cart_list[$item["basket_product_id"]] = [
         "quantity" => $item["basket_quantity"],
         "cart_id" => $item["basket_id"],
       ];
     } else {
       $cart_list[$item["basket_product_id"] . $item["brand_code"]] = [
         "quantity" => $item["basket_quantity"],
         "cart_id" => $item["basket_id"],
       ];
     }
    }

    $res_currencies = get_currency();
    $currencies_list = [];
    $currencies_name_list = [];

    foreach ($res_currencies as $key => $item) {
      $currencies_list[$item["id"]] = $item;
      $currencies_name_list[$item["main_name"]] = $item;
    }

    foreach ($products as $key => $item) {
      if($item["resource"] === special_codes("products.resources.ava")){
        $products[$key]["in_cart"] = isset($cart_list[$item["id"]]) ? (int)$cart_list[$item["id"]]["quantity"] : 0;
        $products[$key]["cart_id"] = isset($cart_list[$item["id"]]) ? (int)$cart_list[$item["id"]]["cart_id"] : NULL;
      } else {
        $products[$key]["in_cart"] = isset($cart_list[$item["id"] . $item["brand"]["code"]]) ? (int)$cart_list[$item["id"] . $item["brand"]["code"]]["quantity"] : 0;
        $products[$key]["cart_id"] = isset($cart_list[$item["id"] . $item["brand"]["code"]]) ? (int)$cart_list[$item["id"] . $item["brand"]["code"]]["cart_id"] : NULL;
      }

      $products[$key]["converted_currency"] = CURRENCY_AZN;

      $products[$key]["is_b4b_price_hidden"] = isset($item["is_b4b_price_hidden"]) ? ($item["is_b4b_price_hidden"] === STATUS_ACTIVE) : NULL;

      $products[$key]["add_to_cart"] = $item["sale_price"] ? STATUS_ACTIVE : STATUS_DEACTIVE;

      $products[$key]["add_to_cart"] = $products[$key]["add_to_cart"] === STATUS_ACTIVE;

      $products[$key]["sale_price"] = $products[$key]["is_b4b_price_hidden"] ? null : $item["sale_price"];

      $products[$key]["discount_price"] = isset($products[$key]["has_discount"]) &&
                                          isset($products[$key]["discount_price"]) &&
                                          ($products[$key]["has_discount"] === STATUS_ACTIVE) ? $products[$key]["discount_price"] : NULL;

      $products[$key]["converted_discount_price"] = isset($products[$key]["has_discount"]) &&
                                                    isset($products[$key]["discount_price"]) &&
                                                    ($products[$key]["has_discount"] === STATUS_ACTIVE) ?
                                                    (isset($currencies_name_list[$item["currency"]]) ?
                                                    $item["discount_price"] * $currencies_name_list[$item["currency"]]["value"] :
                                                    NULL) : NULL;

      if(isset($item["final_currency_id"])) {
        $products[$key]["currency"] = isset($currencies_list[$item["final_currency_id"]]) ? $currencies_list[$item["final_currency_id"]]["main_name"] : NULL;
        $products[$key]["converted_sale_price"] = isset($currencies_list[$item["final_currency_id"]]) ?
                                                  $item["sale_price"] * $currencies_list[$item["final_currency_id"]]["value"] :
                                                  NULL;
      } else {
        $products[$key]["converted_sale_price"] = isset($currencies_name_list[$item["currency"]]) ?
                                                  $item["sale_price"] * $currencies_name_list[$item["currency"]]["value"] :
                                                  NULL;

      }

      $stock_show = $params["auth_user"]["stock_show"];
      $products[$key]["stock_baku"] = stock_visibility($item["resource"],$stock_show,$item["stock_baku"]);
      $products[$key]["stock_baku_2"] = stock_visibility($item["resource"],$stock_show,$item["stock_baku_2"]);
      $products[$key]["stock_ganja"] = stock_visibility($item["resource"],$stock_show,$item["stock_ganja"]);

      $products[$key]["has_discount"] = isset($item["has_discount"]) ? $item["has_discount"] === STATUS_ACTIVE : NULL;
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => count($products),
        "list" => $products
      ]
    );
  }
}
