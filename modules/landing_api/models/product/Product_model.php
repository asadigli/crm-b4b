<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit','-1');
ini_set('max_execution_time', 900); //300 seconds = 5 minutes

class Product_model extends CI_Model{

  function __construct(){
    parent::__construct();
  }

  private $image_divider = "|PRODUCT_IMAGE_DIVIDER|";


  function updateProducts($date){
    $products_res = callARM_API('GET','parts/all_parts',[]);

    if (!isset($products_res["code"]) || $products_res["code"] !== 200) {
      return rest_response(Status_codes::HTTP_CONFLICT,'failed',[]);
    }

    $products = $products_res["data"];
    $insert_title = "INSERT INTO `sa_products` (`LOGICALREF`,`STOK_BAKU`,`STOK_GANCA`,`CODE`,`OEM`,`PRODUCERCODE`,`STATENAME`,`slug`,`SPECODE`,`SPECODE2`,`SALE_PRICE`,`quantity`,`deleted_at`) VALUES ";
    $product_ids = array_map(function($i) {return $i["LOGICALREF"];}, $products);
    $this->db->query("UPDATE `sa_products` SET `deleted_at` = '$date' WHERE `id` > 0 AND `deleted_at` IS NULL AND `type` = 'system'");
    $exist_products = $this->db->query("SELECT `id`,`LOGICALREF`
                                        FROM `sa_products`
                                        WHERE `LOGICALREF` IN ('".implode("','",$product_ids)."')
                                        AND `type` = 'system'");
    $exist_products_ids = array_map(function($i) {return $i["LOGICALREF"];}, $exist_products->result_array());

    $product_insert_list = [];
    $product_update_list = [];
    foreach ($products as $index => $product) {
      if ($product["LOGICALREF"] && $product["STATENAME"]) {
        $slug = slugify($product["STATENAME"])."-".((int)$product["LOGICALREF"] * 4321);
        $quantity = (int)$product["STOK_GANCA"] + (int)$product["STOK_BAKU"];
        if (!in_array($product["LOGICALREF"],$exist_products_ids)) {
          $product_insert_list[] = " (".trim($product["LOGICALREF"]).",".isNullSQL((int)$product["STOK_BAKU"]).",".isNullSQL((int)$product["STOK_GANCA"]).",".isNullSQL($product["CODE"]).",
                                        ".isNullSQL($product["OEM"]).",".isNullSQL($product["PRODUCERCODE"]).",".isNullSQL($product["STATENAME"]).",'{$slug}',".isNullSQL($product["SPECODE"]).",
                                          ".isNullSQL($product["SPECODE2"]).",".isNullSQL((float)$product["SALE_PRICE"]).",".isNullSQL($quantity).",NULL)";
        }else{
          $product_update_list[] = " (".trim($product["LOGICALREF"]).",".isNullSQL((int)$product["STOK_BAKU"]).",".isNullSQL((int)$product["STOK_GANCA"]).",".isNullSQL($product["CODE"]).",
                                        ".isNullSQL($product["OEM"]).",".isNullSQL($product["PRODUCERCODE"]).",".isNullSQL($product["STATENAME"]).",'{$slug}',".isNullSQL($product["SPECODE"]).",
                                          ".isNullSQL($product["SPECODE2"]).",".isNullSQL((float)$product["SALE_PRICE"]).",".isNullSQL($quantity).",NULL)";
        }
      }
    }

    $query = $insert_title.implode(",",$product_insert_list).";";
    $update_query = $insert_title.implode(",",$product_update_list)." ON DUPLICATE KEY UPDATE `LOGICALREF`=VALUES(`LOGICALREF`), `STOK_BAKU`=VALUES(`STOK_BAKU`),`STOK_GANCA`=VALUES(`STOK_GANCA`),`CODE`=VALUES(`CODE`), `OEM`=VALUES(`OEM`),`PRODUCERCODE`=VALUES(`PRODUCERCODE`),
                                                  `STATENAME`=VALUES(`STATENAME`),`slug`=VALUES(`slug`),`SPECODE`=VALUES(`SPECODE`),`SPECODE2`=`SPECODE2`,`SALE_PRICE`=VALUES(`SALE_PRICE`),`quantity`=VALUES(`quantity`),`status`=VALUES(`status`),`updated_at`=VALUES(`updated_at`),`deleted_at`=VALUES(`deleted_at`);";
    count($product_insert_list) ? $this->db->query($query) : "";
    count($product_update_list) ? $this->db->query($update_query) : "";
    $afftectedRows=$this->db->affected_rows();
    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Success"),
      [
        "added" => count($product_insert_list),
        "updated" => count($product_update_list)
      ]
    );
  }


  function productDetails($params){
    $user = $params["user"];
    $user_token = $params["user_token"];
    $slug = $params["slug"];
    $id = $params["id"];
    $version = $params["version"] === "full";
    $is_admin = false;

    if ($user) {
      $u = isAdmin($user);
      if (!$u["status"]) {
        $is_admin = false;
      }else{
        $is_admin = $version;
      }
    }

    if (!$slug && !$id) {
      return rest_response(Status_codes::HTTP_CONFLICT,$this->lang->line("Missed parameters"),[]);
    }

    $user_id = null;
    if ($user) {
      $exist_user = $this->db->select("id")->from("sa_users")
                        ->where("token",$user)->where("deleted_at",null)
                          ->limit(1)->get();
      $user_id = $exist_user->num_rows() ? $exist_user->row()->id : null;
    }

    $slug_query = $slug ? " AND (prod.`slug` = '$slug' OR prod.`custom_slug` = '$slug') " : " ";
    $id_query = $id ? " AND (prod.`id` = {$id}) " : " ";

    // $cart_query = ($user_id || $user_token) ?
    //                 " (SELECT quantity FROM `sa_cart` crt
    //                    WHERE crt.`product_id` = prod.`id`
    //                    ".($user_id ? " AND crt.`user_id` = $user_id " : ($user_token ? " AND crt.`user_key` = '$user_token' " : ""))."
    //                    LIMIT 1) " : " NULL ";
    // $wishlist_query = ($user_id || $user_token) ?
    //                 " (SELECT quantity FROM `sa_wishlist` wsh
    //                    WHERE wsh.`product_id` = prod.`id`
    //                    ".($user_id ? " AND wsh.`user_id` = $user_id " : ($user_token ? " AND wsh.`user_key` = '$user_token' " : ""))."
    //                    LIMIT 1) " : " NULL ";
    $sql = "SELECT prod.`id`,
                   prod.`LOGICALREF`,prod.`SPECODE2` as parent,prod.`quantity`,prod.`SALE_PRICE` as price,
                   prod.`PRODUCERCODE` as producercode,
                   CASE
                    WHEN prod.`custom_name` IS NOT NULL OR prod.`custom_name` <> ''
                    THEN prod.`custom_name` ELSE prod.`STATENAME` END
                   AS prod_name,
                   prod.`type`,
                   CASE
                    WHEN prod.`custom_slug` IS NOT NULL OR prod.`custom_slug` <> ''
                    THEN prod.`custom_slug` ELSE prod.`slug` END
                   AS prod_slug,
                   prod.`group_id`,
                   prod.`brand_id`,
                   prod.`first_category_id`,
                   prod.`second_category_id`,
                   (SELECT CAST(AVG(`rating`) as decimal(10,2)) FROM `sa_ratings` rr WHERE rr.`product_id` = prod.`id` AND rr.`deleted_at` IS NULL) as rating,
                   CASE
                    WHEN prod.`brand_id` IS NOT NULL
                    THEN (SELECT cat.`name`
                          FROM `sa_category` cat
                          WHERE cat.`id` = prod.`brand_id`
                          AND cat.`type` = 'brand'
                          AND cat.`deleted_at` IS NULL
                          LIMIT 1) ELSE prod.`SPECODE` END
                   AS brand,
                   prod.`CODE` as brand_code,
                   prod.`OEM`,
                   prod.`description`,
                   prod.`short_description`,
                   prod.`home_product`,
                   prod.`status`,img_query.`names` as images
            FROM `sa_products` prod
            LEFT JOIN (
              SELECT imgs.`product_id`,
                     GROUP_CONCAT(DISTINCT imgs.`name` SEPARATOR '|ARRAY_DIVIDER_FROM_SQL|') as names
              FROM `sa_product_images` as imgs
              WHERE imgs.`deleted_at` IS NULL
              GROUP BY imgs.`product_id`,imgs.`deleted_at`) img_query ON img_query.`product_id` = prod.`id`
            WHERE prod.`deleted_at` IS NULL
            {$slug_query} {$id_query}
            ".($is_admin ? " " : " AND prod.`status` = '1' ")."
            LIMIT 1";
    $this->db->query("SET SESSION group_concat_max_len = 1000000");
    $product_query = $this->db->query($sql);
    // {$cart_query} as in_cart,
    // {$wishlist_query} as in_wishlist,

    if (!$product_query->num_rows()) {
      return rest_response(Status_codes::HTTP_NOT_FOUND,$this->lang->line("Product not found"),[]);
    }
    $product = (array)$product_query->row();

    $images = $product["images"] ? explode("|ARRAY_DIVIDER_FROM_SQL|",$product["images"]) : [];
    $images_list = [];
    foreach ($images as $key => $item) {
      $child_arr = explode($this->image_divider,$item);
      $images_list[] = ["small" => isset($child_arr[0]) ? $child_arr[0] : "","large" => isset($child_arr[1]) ? $child_arr[1] : ""];
    }
    $new_list = [
      "id" => $product["id"],
      "LOGICALREF" => $product["LOGICALREF"],
      "parent" => $product["parent"],
      "quantity" => $product["quantity"],
      "price" => $product["price"],
      // "in_cart" => $product["in_cart"],
      // "in_wishlist" => $product["in_wishlist"],
      "producercode" => $product["producercode"],
      "prod_name" => $product["prod_name"],
      "type" => $product["type"],
      "prod_slug" => $product["prod_slug"],
      "group_id" => $product["group_id"],
      "brand_id" => $product["brand_id"],
      "first_category_id" => $product["first_category_id"],
      "second_category_id" => $product["second_category_id"],
      "rating" => $product["rating"],
      "brand" => $product["brand"],
      "brand_code" => $product["brand_code"],
      "OEM" => $product["OEM"],
      "description" => $product["description"],
      "short_description" => $product["short_description"],
      "home_product" => $product["home_product"] === "1",
      "status" => $product["status"] === "1",
      "images" => $images_list,
    ];

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),$new_list);
  }


  function homePageList($params){
    $user = $params["user"];
    $user_token = $params["user_token"];
    // $limit = getConfig('custom')["main_page_products"]["value"];
    $limit_query = "";
    $user_id = null;
    if ($user) {
      $exist_user = $this->db->select("id")
                              ->from("sa_users")
                                ->where("token",$user)
                                  ->where('deleted_at',null)
                                    ->limit(1)
                                      ->get();
      $user_id = $exist_user->num_rows() ? $exist_user->row()->id : null;
    }

    $cart_query = ($user_id || $user_token) ?
                    " (SELECT quantity FROM `sa_cart` crt
                       WHERE crt.`product_id` = prod.`id`
                       ".($user_id ? " AND crt.`user_id` = $user_id " : ($user_token ? " AND crt.`user_key` = '$user_token' " : ""))."
                       LIMIT 1) " : " NULL ";
    $wishlist_query = ($user_id || $user_token) ?
                    " (SELECT quantity FROM `sa_wishlist` wsh
                       WHERE wsh.`product_id` = prod.`id`
                       ".($user_id ? " AND wsh.`user_id` = $user_id " : ($user_token ? " AND wsh.`user_key` = '$user_token' " : ""))."
                       LIMIT 1) " : " NULL ";

    $sql = "SELECT prod.`id`,prod.`SALE_PRICE` as `price`,
                   CASE
                    WHEN prod.`custom_name` IS NOT NULL OR prod.`custom_name` <> ''
                    THEN prod.`custom_name` ELSE prod.`STATENAME` END
                   AS prod_name,
                   CASE
                    WHEN prod.`custom_slug` IS NOT NULL OR prod.`custom_slug` <> ''
                    THEN prod.`custom_slug` ELSE prod.`slug` END
                   AS slug,
                   CASE
                    WHEN prod.`brand_id` IS NOT NULL
                    THEN (SELECT cat.`name`
                          FROM `sa_category` cat
                          WHERE cat.`id` = prod.`brand_id`
                          AND cat.`type` = 'brand'
                          AND cat.`deleted_at` IS NULL
                          LIMIT 1) ELSE prod.`SPECODE` END
                   AS brand,
                   prod.`SPECODE2` as parent,
                   {$cart_query} as in_cart,
                   {$wishlist_query} as in_wishlist,
                   (SELECT imgs.`name`
                     FROM `sa_product_images` as imgs
                     WHERE imgs.`deleted_at` IS NULL
                     AND imgs.`product_id` = prod.`id`
                     ORDER BY imgs.`order` DESC,imgs.`id` DESC
                     LIMIT 1) as images,
                   prod.`OEM`, prod.`CODE` as brand_code
            FROM `sa_products` prod
            -- LEFT JOIN  img_query ON img_query.`product_id` = prod.`id`
            WHERE  prod.`deleted_at` IS NULL
            -- AND prod.`order` > 0
            AND prod.`home_product` = '1'
            AND prod.`status` = 1
            ORDER BY prod.`order` ASC
            {$limit_query}";

    $this->db->query("SET SESSION group_concat_max_len = 1000000");
    $products_query = $this->db->query($sql);
    $products = $products_query->result_array();

    $products_codes = array_map(function($i) {
      return ['code' => $i['brand_code'],'oem' => $i['OEM']];
    }, $products);

    $new_list = [];
    foreach ($products as $key => $product) {
      $images = [];
      if ($product["images"]) {
        $exploded_images = explode($this->image_divider,$product["images"]);
        $images = [
          "small" => $exploded_images[0],
          "large" => $exploded_images[1]
        ];
      }
      $new_list[] = [
        "id" => $product["id"],
        "price" => $product["price"],
        "prod_name" => $product["prod_name"],
        "code" => $product["brand_code"],
        "OEM" => $product["OEM"],
        "slug" => $product["slug"],
        "brand" => $product["brand"],
        "parent" => $product["parent"],
        "in_cart" => $product["in_cart"],
        "in_wishlist" => $product["in_wishlist"],
        "images" => $images,
      ];
    }
    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Success"),
      [
        "codes" => $products_codes,
        "products" => $new_list
      ]
    );
  }


  function latestProducts($params){
    $limit = $params["limit"];
    $offset = $params["offset"];
    $user = $params["user"];
    $system_brands = $params["system_brands"];
    $version = $params["version"] === "full";
    $main_brands = $params["main_brands"];
    $user_token = $params["user_token"] ? $this->db->escape($params["user_token"]) : null;
    $keyword = $params["keyword"] ? trim($this->db->escape($params["keyword"])) : null;
    $is_admin = false;

    if ($user) {
      $u = isAdmin($user);
      if (!$u["status"]) {
        $is_admin = false;
      }else{
        $is_admin = $version;
      }
    }

    $custom_groups = isset($params["custom_groups"]) ? $params["custom_groups"] : null;
    $custom_brands = isset($params["custom_brands"]) ? $params["custom_brands"] : null;
    $custom_categories = isset($params["custom_categories"]) ? $params["custom_categories"] : null;
    $custom_second_categories = isset($params["custom_second_categories"]) ? $params["custom_second_categories"] : null;


    $carbrands = is_array($params["carbrands"]) ?
                    "'".implode("','",$params["carbrands"])."'" :
                        "'".implode("','",array_map('trim', explode(',', $params["carbrands"])))."'";
    $brands = is_array($params["brands"]) ?
                    "'".implode("','",$params["brands"])."'" :
                        "'".implode("','",array_map('trim', explode(',', $params["brands"])))."'";

    $user_id = null;
    if (!$is_admin && $user) {
      $exist_user = $this->db->select("id")->from("sa_users")
                        ->where('token',$user)->where('deleted_at',null)
                          ->limit(1)->get();
      $user_id = $exist_user->num_rows() ? $exist_user->row()->id : null;
    }

    $main_brands_query = "";
    if ($main_brands) {
      $main_brands = is_array($main_brands) ? $main_brands : explode(",",$main_brands);
      $main_brands_query = count($main_brands) ? " AND SPECODE2 IN ('".implode("','",$main_brands)."') " : "";
    }

    // custom section
    $custom_groups_query = "";
    if ($custom_groups) {
      $custom_groups = is_array($custom_groups) ? $custom_groups : explode(",",$custom_groups);
      $custom_groups_query = count($custom_groups) ? " AND group_id IN ('".implode("','",$custom_groups)."') " : "";
    }

    $custom_brands_query = "";
    if ($custom_brands) {
      $custom_brands = is_array($custom_brands) ? $custom_brands : explode(",",$custom_brands);
      $custom_brands_query = count($custom_brands) ? " AND brand_id IN ('".implode("','",$custom_brands)."') " : "";
    }

    $custom_categories_query = "";
    if ($custom_categories) {
      $custom_categories = is_array($custom_categories) ? $custom_categories : explode(",",$custom_categories);
      $custom_categories_query = count($custom_categories) ? " AND first_category_id IN ('".implode("','",$custom_categories)."') " : "";
    }

    $custom_second_categories_query = "";
    if ($custom_second_categories) {
      $custom_second_categories = is_array($custom_second_categories) ? $custom_second_categories : explode(",",$custom_second_categories);
      $custom_second_categories_query = count($custom_second_categories) ? " AND second_category_id IN ('".implode("','",$custom_second_categories)."') " : "";
    }


    $system_brands_cleaned = $this->db->escape_str($system_brands);
    $system_brands_query = $system_brands && $system_brands_cleaned ? " AND `SPECODE` = '$system_brands_cleaned' " : "";


    // $cart_query = ($user_id || $user_token) ?
    //                 " (SELECT quantity FROM `sa_cart` crt
    //                    WHERE crt.`product_id` = prod.`id`
    //                    ".($user_id ? " AND crt.`user_id` = $user_id " : ($user_token ? " AND crt.`user_key` = $user_token " : ""))."
    //                    LIMIT 1) " : " NULL ";
    // $wishlist_query = ($user_id || $user_token) ?
    //                 " (SELECT quantity FROM `sa_wishlist` wsh
    //                    WHERE wsh.`product_id` = prod.`id`
    //                    ".($user_id ? " AND wsh.`user_id` = $user_id " : ($user_token ? " AND wsh.`user_key` = $user_token " : ""))."
    //                    LIMIT 1) " : " NULL ";



    $limit_query = " LIMIT 12 ";
    if (is_numeric($limit) && is_numeric($offset) && $offset > 0) {
      $limit_query = " LIMIT $limit OFFSET $offset ";
    }elseif(is_numeric($limit)){
      $limit_query = $limit > 0 ? " LIMIT $limit " : "";
    }

    $keywords_query = "";
    if ($keyword) {
      $sql_first = "SELECT CONCAT(prod.`CODE`,',',prod.`OEM`) as code
                    FROM `sa_products` prod
                    WHERE prod.`deleted_at` IS NULL
                    AND (prod.`OEM` IN (SELECT rsub.`OEM` FROM `sa_products` rsub WHERE TRIM(rsub.`CODE`) = $keyword AND rsub.`deleted_at` IS NULL)
                    OR TRIM(prod.`OEM`) = $keyword)";
      $all_codes_query = $this->db->query($sql_first);
      $codes = array_map(function($i){
        return str_replace(" ","",$i["code"]);
      },$all_codes_query->result_array());
      $codes_string = implode(",",$codes);
      $keywords = getCodesByCode($codes_string);
      // return $keywords;
      $keywords_string = "";
      foreach (array_keys($keywords) as $key) {
        $keywords_string .= "'".implode("','",(array_map(function($i){
          return $i;
        },$keywords[$key])))."'";
      }
      $keywords_query = $keywords ? "AND (prod.`OEM` IN (SELECT rsub.`OEM` FROM `sa_products` rsub WHERE rsub.`CODE` IN ({$keywords_string}) AND rsub.`deleted_at` IS NULL)
                                   OR prod.`OEM` IN ({$keywords_string})) " : "";
    }



    $min_query = $params["min"] ? " AND `SALE_PRICE` >= {$params['min']} " : "";
    $max_query = $params["max"] ? " AND `SALE_PRICE` <= {$params['max']} " : "";

    $carbrands_query = strlen($carbrands) > 2 ? " AND TRIM(SPECODE2) IN ($carbrands) " : "";
    $brands_query = strlen($brands) > 2 ? " AND TRIM(SPECODE) IN ($brands) " : "";

    $sort_by_query = " ORDER BY prod.`id` DESC ";

    if ($params["sort_by"] === 'price_low_to_high') {
      $sort_by_query = " ORDER BY prod.`SALE_PRICE` ASC ";
    }elseif($params["sort_by"] === 'price_high_to_low'){
      $sort_by_query = " ORDER BY prod.`SALE_PRICE` DESC ";
    }

    $sql = "SELECT prod.`id`,prod.`LOGICALREF` as system_id,
                   CASE
                    WHEN prod.`custom_slug` IS NOT NULL OR prod.`custom_slug` <> ''
                    THEN prod.`custom_slug` ELSE prod.`slug` END
                   AS slug,
                   prod.`SPECODE2` as parent,
                   CASE
                    WHEN prod.`brand_id` IS NOT NULL
                    THEN (SELECT cat.`name`
                          FROM `sa_category` cat
                          WHERE cat.`id` = prod.`brand_id`
                          AND cat.`type` = 'brand'
                          AND cat.`deleted_at` IS NULL
                          LIMIT 1) ELSE prod.`SPECODE` END
                   AS brand,prod.`SALE_PRICE` as price,
                   prod.`PRODUCERCODE` as producercode,
                   CASE
                    WHEN prod.`custom_name` IS NOT NULL OR prod.`custom_name` <> ''
                    THEN prod.`custom_name` ELSE prod.`STATENAME` END
                   AS prod_name,
                   prod.`CODE` as brand_code,
                   prod.`OEM`,
                   prod.`status`,prod.`home_product`,prod.`type`,
                   img_query.`names` as images
            FROM `sa_products` prod
            LEFT JOIN (
              SELECT imgs.`product_id`,
                     GROUP_CONCAT(DISTINCT imgs.`name` SEPARATOR '|ARRAY_DIVIDER_FROM_SQL|') as names
              FROM `sa_product_images` as imgs
              WHERE imgs.`deleted_at` IS NULL
              GROUP BY imgs.`product_id`,imgs.`deleted_at`) img_query ON img_query.`product_id` = prod.`id`
            WHERE  prod.`deleted_at` IS NULL
            $system_brands_query
            {$carbrands_query} {$brands_query}
            {$keywords_query} {$min_query} {$max_query} $main_brands_query
            $custom_groups_query $custom_brands_query $custom_categories_query $custom_second_categories_query
            {$sort_by_query} {$limit_query}";

    // -- {$cart_query} as in_cart,
    // -- {$wishlist_query} as in_wishlist,
    $this->db->query("SET SESSION group_concat_max_len = 1000000");
    $products_query = $this->db->query($sql);
    $products = $products_query->result_array();
    $sql_count = "SELECT COUNT(*) as `count` FROM `sa_products` prod
                  WHERE  prod.`deleted_at` IS NULL
                  {$system_brands_query}
                  {$carbrands_query} {$brands_query}
                  {$keywords_query} {$min_query} {$max_query} $main_brands_query
                  $custom_groups_query $custom_brands_query $custom_categories_query $custom_second_categories_query
                  {$sort_by_query}";
    $count = $this->db->query($sql_count)->num_rows() ? $this->db->query($sql_count)->row()->count : 0;

    $products_codes = [];
    foreach ($products as $key => $prod) {
      if ($prod['brand_code'] || $prod['OEM']) {
        $products_codes[] =  ['code' => $prod['brand_code'],'oem' => $prod['OEM']];
      }
    }

    $products_new = [];
    foreach ($products as $index => $product) {
      $images = $product["images"] ? explode("|ARRAY_DIVIDER_FROM_SQL|",$product["images"]) : [];
      $images_list = [];
      foreach ($images as $key => $item) {
        $child_arr = explode($this->image_divider,$item);
        $images_list[] = ["small" => isset($child_arr[0]) ? $child_arr[0] : "","large" => isset($child_arr[1]) ? $child_arr[1] : ""];
      }
      $sub_array = [
        "id" => $product["id"],
        "slug" => $product["slug"],
        "price" => $product["price"],
        // "in_cart" => $product["in_cart"],
        // "in_wishlist" => $product["in_wishlist"],
        "product_name" => $product["prod_name"],
        "brand" => $product["brand"],
        "short_name" => str_limit($product["prod_name"],30),
        "OEM" => $product["OEM"],
        "brand_code" => $product["brand_code"],
        "images" => $images_list,
      ];
      if($is_admin){
        $sub_array["status"] = $product["status"] === "1";
        $sub_array["home_product"] = $product["home_product"] === "1";
        $sub_array["is_local"] = $product["type"] === "local";
      }
      $products_new[] = $sub_array;
    }

    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Success"),
      [
        "count" => $count,
        "codes" => $products_codes,
        "products" => $products_new
      ]
    );
  }


  function similarProducts($params){
    $user = $params["user"];
    $user_token = $params["user_token"];
    $id = $params["id"];
    $slug = $params["slug"];

    if (!$slug && !$id) {
      return rest_response(Status_codes::HTTP_CONFLICT,$this->lang->line("Missed parameters"),[]);
    }

    $query = $id ? " `id` = {$id} " : " (`slug` = '{$slug}' OR `custom_slug` = '{$slug}') ";
    // CASE WHEN `custom_slug` IS NOT NUL OR `custom_slug` <> '' THEN `custom_slug` ELSE `slug` END = '{$slug}'
    // var_dump($query);die;

    $exist_product = $this->db->select("CODE,OEM")
                                ->from("sa_products")
                                  ->where($query,NULL, FALSE)
                                    ->where("deleted_at",null)
                                      ->where(" CODE IS NOT NULL ", NULL, FALSE)
                                        ->limit(1)
                                          ->get();

    if (!$exist_product->num_rows()) {
      return rest_response(Status_codes::HTTP_NOT_FOUND,$this->lang->line("Product not found"),[]);
    }

    $row = $exist_product->row();
    $keyword = $row->CODE ? $row->CODE : $row->OEM;

    $new_params = [
      "limit" => $params["limit"],
      "offset" => $params["offset"],
      "keyword" => $keyword,
      "user" => $user,
      "user_token" => $user_token,
      "carbrands" => null,
      "brands" => null,
      "min" => null,
      "max" => null,
      "sort_by" => null,
      "main_brands" => null,
      "version" => null,
    ];

    return $this->latestProducts($new_params);
  }

  /*
  * Products by group
  *
  */
  function getByEngine($params){
    $user = $params["user"];
    $user_token = $params["user_token"] ? $this->db->escape($params["user_token"]) : null;
    $engine = $params["engine"] ? $this->db->escape_str($params["engine"]) : null;
    $category = $params["category"] ? $this->db->escape_str($params["category"]) : null;
    $limit = $params["limit"] ? $this->db->escape_str($params["limit"]) : null;
    $offset = $params["offset"] ? $this->db->escape_str($params["offset"]) : null;
    $sort_by = $params["sort_by"] ? $this->db->escape_str($params["sort_by"]) : null;

    if (!$engine || !$category) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameters")
      );
    }

    $user_id = null;
    if ($user) {
      $exist_user = $this->db->select("id")->from("sa_users")
                        ->where('token',$user)->where('deleted_at',null)
                          ->limit(1)->get();
      $user_id = $exist_user->num_rows() ? $exist_user->row()->id : null;
    }

    $cart_query = ($user_id || $user_token) ?
                    " (SELECT quantity FROM `sa_cart` crt
                       WHERE crt.`product_id` = prod.`id`
                       ".($user_id ? " AND crt.`user_id` = $user_id " : ($user_token ? " AND crt.`user_key` = $user_token " : ""))."
                       LIMIT 1) " : " NULL ";
    $wishlist_query = ($user_id || $user_token) ?
                    " (SELECT quantity FROM `sa_wishlist` wsh
                       WHERE wsh.`product_id` = prod.`id`
                       ".($user_id ? " AND wsh.`user_id` = $user_id " : ($user_token ? " AND wsh.`user_key` = $user_token " : ""))."
                       LIMIT 1) " : " NULL ";



    $limit_query = " LIMIT 12 ";
    if (is_numeric($limit) && is_numeric($offset) && $offset > 0) {
      $limit_query = " LIMIT $limit OFFSET $offset ";
    }elseif(is_numeric($limit)){
      $limit_query = $limit > 0 ? " LIMIT $limit " : "";
    }


    $sort_by_query = " ORDER BY prod.`id` DESC ";

    if ($params["sort_by"] === 'price_low_to_high') {
      $sort_by_query = " ORDER BY prod.`SALE_PRICE` ASC ";
    }elseif($params["sort_by"] === 'price_high_to_low'){
      $sort_by_query = " ORDER BY prod.`SALE_PRICE` DESC ";
    }
    // elseif($params["sort_by"] === 'rated'){
       // $sort_by_query = " ORDER BY prod.`SALE_PRICE` DESC ";
    // }

    $sql = "SELECT prod.`id`,prod.`LOGICALREF` as system_id,
                   prod.`slug`,prod.`SPECODE2` as parent,
                   CASE
                    WHEN prod.`brand_id` IS NOT NULL
                    THEN (SELECT cat.`name`
                          FROM `sa_category` cat
                          WHERE cat.`id` = prod.`brand_id`
                          AND cat.`type` = 'brand'
                          AND cat.`deleted_at` IS NULL
                          LIMIT 1) ELSE prod.`SPECODE` END
                   AS brand,
                   prod.`SALE_PRICE` as price,
                   prod.`PRODUCERCODE` as producercode,
                   CASE
                    WHEN prod.`custom_name` IS NOT NULL OR prod.`custom_name` <> ''
                    THEN prod.`custom_name` ELSE prod.`STATENAME` END
                   AS prod_name,
                   prod.`CODE` as brand_code,
                   prod.`OEM`,
                   {$cart_query} as in_cart,
                   {$wishlist_query} as in_wishlist,
                   prod.`status`,img_query.`names` as images
            FROM `sa_products` prod
            LEFT JOIN (
              SELECT imgs.`product_id`,
                     GROUP_CONCAT(DISTINCT imgs.`name` SEPARATOR '|ARRAY_DIVIDER|') as names
              FROM `sa_product_images` as imgs
              WHERE imgs.`deleted_at` IS NULL
              GROUP BY imgs.`product_id`,imgs.`deleted_at`) img_query ON img_query.`product_id` = prod.`id`
            WHERE  prod.`deleted_at` IS NULL
            {$sort_by_query} {$limit_query}";
    // var_dump($sql);die;

    $products_query = $this->db->query($sql);
    $products = $products_query->result_array();
    $sql_count = "SELECT COUNT(*) as `count` FROM `sa_products` prod
            WHERE  prod.`deleted_at` IS NULL
            {$sort_by_query}";
    $count = $this->db->query($sql_count)->num_rows() ? $this->db->query($sql_count)->row()->count : 0;

    $products_codes = array_map(function($i) {
      return ['code' => $i['brand_code'],'oem' => $i['OEM']];
    }, $products);

    $products_new = [];
    foreach ($products as $index => $product) {
      $products_new[] = [
        "id" => $product["id"],
        "slug" => $product["slug"],
        "price" => $product["price"],
        "in_cart" => $product["in_cart"],
        "in_wishlist" => $product["in_wishlist"],
        "product_name" => $product["prod_name"],
        "brand" => $product["brand"],
        "short_name" => str_limit($product["prod_name"],30),
        "OEM" => $product["OEM"],
        "brand_code" => $product["brand_code"],
        "images" => $product["images"] ? explode('|ARRAY_DIVIDER|',$product["images"]) : null,
      ];
    }

    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Success"),
      [
        "codes" => $products_codes,
        "count" => (int)$count,
        "products" => $products_new
      ]
    );
  }

  /*
  * Products by group
  *
  */
  function getProductList($params){
    $group_id = $params["group_id"] ? $this->db->escape($params["group_id"]) : null;
    $limit = $params["limit"] ? $this->db->escape_str($params["limit"]) : null;
    $offset = $params["offset"] ? $this->db->escape_str($params["offset"]) : 0;
    $user = $params["user"] ? $this->db->escape_str($params["user"]) : null;
    $user_token = $params["user_token"] ? $this->db->escape($params["user_token"]) : null;
    $carbrands = $params["carbrands"];
    $brands = $params["brands"];

    if (!$group_id) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        "Group ID cannot be empty"
      );
    }


    $carbrands = is_array($carbrands) ?
                    "'".implode("','",$carbrands)."'" :
                        "'".implode("','",array_map('trim', explode(',', $carbrands)))."'";
    $brands = is_array($brands) ?
                    "'".implode("','",$brands)."'" :
                        "'".implode("','",array_map('trim', explode(',', $brands)))."'";

    $user_id = null;
    if ($user) {
      $exist_user = $this->db->select("id")->from("sa_users")
                        ->where('token',$user)->where('deleted_at',null)
                          ->limit(1)->get();
      $user_id = $exist_user->num_rows() ? $exist_user->row()->id : null;
    }

    $cart_query = ($user_id || $user_token) ?
                    " (SELECT quantity FROM `sa_cart` crt
                       WHERE crt.`product_id` = prod.`id`
                       ".($user_id ? " AND crt.`user_id` = $user_id " : ($user_token ? " AND crt.`user_key` = $user_token " : ""))."
                       LIMIT 1) " : " NULL ";
    $wishlist_query = ($user_id || $user_token) ?
                    " (SELECT quantity FROM `sa_wishlist` wsh
                       WHERE wsh.`product_id` = prod.`id`
                       ".($user_id ? " AND wsh.`user_id` = $user_id " : ($user_token ? " AND wsh.`user_key` = $user_token " : ""))."
                       LIMIT 1) " : " NULL ";



    $limit_query = " LIMIT 12 ";
    if (is_numeric($limit) && is_numeric($offset) && $offset > 0) {
      $limit_query = " LIMIT $limit OFFSET $offset ";
    }elseif(is_numeric($limit)){
      $limit_query = $limit > 0 ? " LIMIT $limit " : "";
    }


    $min_query = $params["min"] ? " AND `SALE_PRICE` >= {$params['min']} " : "";
    $max_query = $params["max"] ? " AND `SALE_PRICE` <= {$params['max']} " : "";

    $carbrands_query = strlen($carbrands) > 2 ? " AND TRIM(SPECODE2) IN ($carbrands) " : "";
    $brands_query = strlen($brands) > 2 ? " AND (TRIM(SPECODE) IN ($brands) OR TRIM(`brand_id`) IN ($brands)) " : "";

    $sort_by_query = " ORDER BY prod.`id` DESC ";

    if ($params["sort_by"] === 'price_low_to_high') {
      $sort_by_query = " ORDER BY prod.`SALE_PRICE` ASC ";
    }elseif($params["sort_by"] === 'price_high_to_low'){
      $sort_by_query = " ORDER BY prod.`SALE_PRICE` DESC ";
    }
    // elseif($params["sort_by"] === 'rated'){
       // $sort_by_query = " ORDER BY prod.`SALE_PRICE` DESC ";
    // }

    $sql = "SELECT prod.`id`,prod.`LOGICALREF` as system_id,
                   prod.`slug`,prod.`SPECODE2` as parent,
                   CASE
                    WHEN prod.`brand_id` IS NOT NULL
                    THEN (SELECT cat.`name`
                          FROM `sa_category` cat
                          WHERE cat.`id` = prod.`brand_id`
                          AND cat.`type` = 'brand'
                          AND cat.`deleted_at` IS NULL
                          LIMIT 1) ELSE prod.`SPECODE` END
                   AS brand,
                   prod.`SALE_PRICE` as price,
                   prod.`PRODUCERCODE` as producercode,
                   CASE
                    WHEN prod.`custom_name` IS NOT NULL OR prod.`custom_name` <> ''
                    THEN prod.`custom_name` ELSE prod.`STATENAME` END
                   AS prod_name,
                   prod.`CODE` as brand_code,
                   prod.`OEM`,
                   {$cart_query} as in_cart,
                   {$wishlist_query} as in_wishlist,
                   prod.`status`,img_query.`names` as images
            FROM `sa_products` prod
            LEFT JOIN (
              SELECT imgs.`product_id`,
                     GROUP_CONCAT(DISTINCT imgs.`name` SEPARATOR '|ARRAY_DIVIDER|') as names
              FROM `sa_product_images` as imgs
              WHERE imgs.`deleted_at` IS NULL
              GROUP BY imgs.`product_id`,imgs.`deleted_at`) img_query ON img_query.`product_id` = prod.`id`
            WHERE  prod.`deleted_at` IS NULL
            AND group_id = $group_id
            {$carbrands_query} {$brands_query}
            {$min_query} {$max_query}
            {$sort_by_query} {$limit_query}";
    // var_dump($sql);die;

    $products_query = $this->db->query($sql);
    $products = $products_query->result_array();
    $sql_count = "SELECT COUNT(*) as `count` FROM `sa_products` prod
            WHERE  prod.`deleted_at` IS NULL
            AND group_id = $group_id
            {$carbrands_query} {$brands_query}
            {$min_query} {$max_query}
            {$sort_by_query}";
    $count = $this->db->query($sql_count)->num_rows() ? $this->db->query($sql_count)->row()->count : 0;

    $products_codes = array_map(function($i) {
      return ['code' => $i['brand_code'],'oem' => $i['OEM']];
    }, $products);

    $products_new = [];
    foreach ($products as $index => $product) {
      $products_new[] = [
        "id" => $product["id"],
        "slug" => $product["slug"],
        "price" => $product["price"],
        "in_cart" => $product["in_cart"],
        "in_wishlist" => $product["in_wishlist"],
        "product_name" => $product["prod_name"],
        "brand" => $product["brand"],
        "short_name" => str_limit($product["prod_name"],30),
        "OEM" => $product["OEM"],
        "brand_code" => $product["brand_code"],
        "images" => $product["images"] ? explode('|ARRAY_DIVIDER|',$product["images"]) : null,
      ];
    }

    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Success"),
      [
        "codes" => $products_codes,
        "count" => (int)$count,
        "products" => $products_new
      ]
    );
  }


  function getByCodes($params){
    $codes = $params["codes"];
    if (!$codes) {
      $msg = $this->lang->line("Missed parameter");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $codes = is_array($codes) ? $codes : explode(',',$codes);

    $keyword_query = "AND (prod.OEM IN (SELECT rsub.`OEM` FROM `sa_products` rsub WHERE rsub.`CODE` IN ('".implode("','",$codes)."') )
                                  OR prod.`OEM` IN ('".implode("','",$codes)."') ) ";

    $sql = "SELECT prod.`id`,
                   prod.`LOGICALREF` as system_id,
                   prod.`STOK_BAKU` as stok_baku,
                   prod.`STOK_GANCA` as stok_ganca,
                   prod.`PRODUCERCODE` as producercode,
                   prod.`SALE_PRICE` as price,
                   CASE
                    WHEN prod.`custom_name` IS NOT NULL OR prod.`custom_name` <> ''
                    THEN prod.`custom_name` ELSE prod.`STATENAME` END
                   AS prod_name,
                   prod.`slug`,
                   CASE
                    WHEN prod.`brand_id` IS NOT NULL
                    THEN (SELECT cat.`name`
                          FROM `sa_category` cat
                          WHERE cat.`id` = prod.`brand_id`
                          AND cat.`type` = 'brand'
                          AND cat.`deleted_at` IS NULL
                          LIMIT 1) ELSE prod.`SPECODE` END
                   AS brand,
                   prod.`CODE` as brand_code,
                   prod.`OEM`,
                   prod.`SPECODE2` as parent
            FROM `sa_products` prod
            WHERE  prod.`deleted_at` IS NULL
            {$keyword_query}";
    $prod_query = $this->db->query($sql);
    $prods = $prod_query->result_array();

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),$prods);
  }


  function getCarBrands($params){
    $carbrand_query = $this->db->select("TRIM(SPECODE2) as name")
                                  ->from("sa_products")
                                    ->where("SPECODE2 is NOT NULL", NULL, FALSE)
                                      ->order_by("SPECODE2","asc")
                                        ->group_by("SPECODE2")
                                          ->get();
    $carbrands = $carbrand_query->result_array();
    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),$carbrands);
  }


  function getBrands($params){
    $carbrand_query = $this->db->select("CASE
                                           WHEN ct.`name` IS NOT NULL
                                           THEN TRIM(ct.`name`)
                                           ELSE TRIM(prod.`SPECODE`) END
                                          AS name")
                                  ->from("sa_products prod")
                                    ->join("sa_category ct", "ct.id = prod.brand_id", "left")
                                    ->where("prod.`SPECODE` IS NOT NULL", NULL, FALSE)
                                      ->order_by("`name`","asc")
                                        ->group_by("prod.`SPECODE`,prod.`brand_id`")
                                          ->get();
    $carbrands = $carbrand_query->result_array();
    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),$carbrands);
  }


  function addOrDeleteHomeProduct($params){
    $user = $params["user"];
    $product = $params["product"];
    $status = $params["status"];

    if (!$product || !$user) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter"),
        []
      );
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $exist_product = $this->db->select("id")->from("sa_products")
                        ->where("id",$product)->where("deleted_at",null)
                          ->limit(1)->get();
    if (!$exist_product->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NOT_FOUND,
        $this->lang->line("Product not found"),
        []
      );
    }

    $user_id = $u["body"]["id"];

    $this->db->where("id",$product);
    $this->db->update("sa_products",["home_product" => $status]);

    $this->db->insert("sa_updates",[
      "user_id" => $user_id,
      "other_id" => $product,
      "title" => "home_product_changed",
      "details" => "Product status changed to '".$status."'"
    ]);

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Home products status changed"),[]);
  }


  /*
  * Updating product name
  */
  function updateProductName($params){
    $user = $params["user"];
    $product = $params["product"];
    $name = htmlentities($params["name"]);

    if (!$product || !$user || !$name) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter"),
        []
      );
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $exist_product = $this->db->select("CASE WHEN `custom_name` IS NOT NULL OR `custom_name` <> '' THEN `custom_name` ELSE `STATENAME` END as `name`,id")->from("sa_products")
                        ->where("id",$product)->where("deleted_at",null)
                          ->limit(1)->get();
    if (!$exist_product->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NOT_FOUND,
        $this->lang->line("Product not found"),
        []
      );
    }

    $user_id = $u["body"]["id"];
    $prod_name = $exist_product->row()->name;

    $this->db->where("id",$product);
    $this->db->update("sa_products",[
      "custom_name" => $name,
      "custom_slug" => slugify($name)."-".(($product + 4545) * 8745)
    ]);

    $this->db->insert("sa_updates",[
      "user_id" => $user_id,
      "other_id" => $product,
      "title" => "product_name_changed",
      "details" => "Product name changed to '".$name."' from '".$prod_name."'"
    ]);

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Product name changed"),[]);

  }


  /*
  * Updating product description
  */
  // function updateDescription($params){
  //   $user = $params["user"];
  //   $product = $params["product"];
  //   $type = $params["type"];
  //   $value = $params["value"];
  //
  //   if (!$product || !$user || !$value) {
  //     return rest_response(
  //       Status_codes::HTTP_CONFLICT,
  //       $this->lang->line("Missed parameter"),
  //       []
  //     );
  //   }
  //
  //   $u = isAdmin($user);
  //   if (!$u["status"]) return $u["body"];
  //
  //   $exist_product = $this->db->select("CASE WHEN `custom_name` IS NOT NULL OR `custom_name` <> '' THEN `custom_name` ELSE `STATENAME` END as `name`,id")->from("sa_products")
  //                       ->where("id",$product)->where("deleted_at",null)
  //                         ->limit(1)->get();
  //   if (!$exist_product->num_rows()) {
  //     return rest_response(
  //       Status_codes::HTTP_NOT_FOUND,
  //       $this->lang->line("Product not found"),
  //       []
  //     );
  //   }
  //
  //   $user_id = $u["body"]["id"];
  //
  //   $update_list = $type !== "short" ? ["description" => $value] : ["short_description" => $value];
  //
  //   $this->db->where("id",$product);
  //   $this->db->update("sa_products",$update_list);
  //
  //   $this->db->insert("sa_updates",[
  //     "user_id" => $user_id,
  //     "other_id" => $product,
  //     "title" => $type !== "short" ? "product_short_description_changed" : "product_description_changed",
  //     "details" => "Product description changed"
  //   ]);
  //
  //   return rest_response(Status_codes::HTTP_OK,$this->lang->line("Product detail changed"),[]);
  //
  // }


  function getAllGroups($params){
    $group_id = (int)$params["group_id"] ? $this->db->escape_str($params["group_id"]) : null;

    $group_query = $group_id ? " group_id = $group_id " : " id > 0 ";

    $category_query = $this->db->select("id,type,parent_id,name,slug,group_id")
                                ->from("sa_category")
                                  ->where("status","1")
                                    ->where("deleted_at",null)
                                      ->where($group_query, NULL, FALSE)
                                        ->order_by("type asc,id asc")
                                          ->get();

    $categories = $category_query->result_array();

    $categories_list = [];

    foreach ($categories as $key => $category) {
      $categories_list[$category["type"]][] = [
        "id" => $category["id"],
        // "group_id" => $category["group_id"],
        // "parent_id" => $category["parent_id"],
        "name" => $category["name"],
        "slug" => $category["slug"],
      ];
    }

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),$categories_list);
  }

  //
  // function addNewProduct($params){
  //   $product = $params["product"];
  //   $name = $params["name"];
  //   $user = $params["user"];
  //   $type = "local";
  //
  //   $brand_code = $params["brand_code"];
  //   $oem = $params["oem"];
  //   $order = $params["order"];
  //   $group_id = $params["group_id"];
  //   $category_id = $params["category_id"];
  //   $second_category_id = $params["second_category_id"];
  //   $description = $params["description"];
  //   $short_description = $params["short_description"];
  //   $home_product = $params["home_product"];
  //   $quantity = $params["quantity"];
  //   $price = $params["price"];
  //   $status = $params["status"];
  //
  //   $brand = $params["brand"];
  //   $carbrand = $params["carbrand"];
  //
  //   if (!$name || !$user) {
  //     return rest_response(
  //       Status_codes::HTTP_CONFLICT,
  //       $this->lang->line("Missed parameter"),
  //       []
  //     );
  //   }
  //
  //   $u = isAdmin($user);
  //   if (!$u["status"]) return $u["body"];
  //
  //
  //   $insert_list = [
  //     "CODE" => $brand_code,
  //     "OEM" => $oem,
  //     "custom_name" => $name,
  //     "custom_slug" => slugify($name)."-".md5(microtime()),
  //     "creator_id" => $u["body"]["id"],
  //     "SALE_PRICE" => $price,
  //     "status" => $status,
  //     "quantity" => $quantity,
  //     "short_description" => $short_description,
  //     "description" => $description,
  //     "group_id" => $group_id,
  //     "first_category_id" => $category_id,
  //     "second_category_id" => $second_category_id,
  //     "order" => $order,
  //     "home_product" => $home_product,
  //     "brand_id" => $brand,
  //     "type" => $type,
  //     "SPECODE2" => $carbrand,
  //   ];
  //
  //   if ($product) {
  //     $exist_product = $this->db->select("id")
  //                                 ->from("sa_products")
  //                                   ->where("id",$product)
  //                                     ->where("type","local")
  //                                       ->where("deleted_at",null)
  //                                         ->get();
  //     if(!$exist_product->num_rows()){
  //       return rest_response(Status_codes::HTTP_NOT_FOUND,$this->lang->line("Product not found"),[]);
  //     }
  //     $message = $this->lang->line("Product details changed");
  //     $update_list = [
  //       "CODE" => $brand_code,
  //       "OEM" => $oem,
  //       "custom_name" => $name,
  //       "custom_slug" => slugify($name)."-".(($product + 4545) * 8745),
  //       "SALE_PRICE" => $price,
  //       "status" => $status,
  //       "quantity" => $quantity,
  //       "short_description" => $short_description,
  //       "description" => $description,
  //       "group_id" => $group_id,
  //       "first_category_id" => $category_id,
  //       "second_category_id" => $second_category_id,
  //       "order" => $order,
  //       "home_product" => $home_product,
  //       "brand_id" => $brand,
  //       "SPECODE2" => $carbrand,
  //     ];
  //     $this->db->where("id",$product);
  //     $this->db->update("sa_products",$update_list);
  //   }else{
  //     $message = $this->lang->line("Product added");
  //     $insert_list = [
  //       "CODE" => $brand_code,
  //       "OEM" => $oem,
  //       "custom_name" => $name,
  //       "custom_slug" => slugify($name)."-".md5(microtime()),
  //       "creator_id" => $u["body"]["id"],
  //       "SALE_PRICE" => $price,
  //       "status" => $status,
  //       "quantity" => $quantity,
  //       "short_description" => $short_description,
  //       "description" => $description,
  //       "group_id" => $group_id,
  //       "first_category_id" => $category_id,
  //       "second_category_id" => $second_category_id,
  //       "order" => $order,
  //       "home_product" => $home_product,
  //       "brand_id" => $brand,
  //       "type" => $type,
  //       "SPECODE2" => $carbrand,
  //     ];
  //     $this->db->insert("sa_products",$insert_list);
  //     $product = $this->db->insert_id();
  //   }
  //
  //
  //
  //   $custom_slug = slugify($name)."-".(($product + 4545) * 8745);
  //
  //   $sql = "UPDATE `sa_products` AS t_one,
  //           (SELECT `order` FROM `sa_products` ORDER BY `order` DESC LIMIT 1) AS t_two
  //           SET t_one.`order` = t_two.`order` + 1,
  //           t_one.`custom_slug` = '$custom_slug'
  //           WHERE `id` = $product";
  //   $this->db->query($sql);
  //
  //   return rest_response(
  //     Status_codes::HTTP_CREATED,
  //     $message,
  //     [
  //       "id" => $product,
  //       "slug" => $custom_slug,
  //     ]
  //   );
  // }
  //

  /*
  * Məhsulun kateqoriyasını + ikinci kateqoriyasını + brendini düzəltmək
  *
  */
  function updateProductDetails($params){
    $user = $params["user"];
    $list = $params["list"];


    if (!$list || !$user) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter"),
        []
      );
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $query_list = [];
    foreach ($list as $key => $item) {
      $query_list[] = "  ";
    }

    $ids = array_map(function($i){return $i["product"];},$list);
    $brand_ids = array_map(function($i){return $i["brand_id"];},$list);
    $group_ids = array_map(function($i){return $i["group_id"];},$list);
    $first_category_ids = array_map(function($i){return $i["category_id"];},$list);
    $second_category_ids = array_map(function($i){return $i["second_category_id"];},$list);


    $updateArray = [];

    for($i = 0; $i < sizeof($ids); $i++){
      if ($ids[$i] && isset($group_ids[$i]) && isset($brand_ids[$i])
            && isset($first_category_ids[$i]) && isset($second_category_ids[$i])) {
        $updateArray[] = [
          "id" => $ids[$i],
          "group_id" => $group_ids[$i],
          "brand_id" => $brand_ids[$i],
          "first_category_id" => $first_category_ids[$i],
          "second_category_id" => $second_category_ids[$i],
        ];
      }
    }
    $updateArray ? $this->db->update_batch('sa_products',$updateArray, 'id') : "";


    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Product details changed"),
      $updateArray
    );

  }

}
