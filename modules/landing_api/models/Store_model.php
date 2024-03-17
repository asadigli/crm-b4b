<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store_model extends CI_Model{

  function __construct(){
    parent::__construct();
  }

  protected $week_days = [
    "monday",
    "tuesday",
    "wednesday",
    "thursday",
    "friday",
    "saturday",
    "sunday"
  ];

  protected $detail_table = "sa_store_details";

  /*
  * Updating store infos from SYSTEM
  */
  function updateStores($date){
    $stores_res = callARM_API('GET','stores/all_stores',[]);
    if (!isset($stores_res["code"]) || $stores_res["code"] !== 200) {
      return rest_response(Status_codes::HTTP_CONFLICT,'failed',[]);
    }
    $stores = $stores_res["data"];
    $insert_title = "INSERT INTO `sa_stores` (`id_from_service`,`ava_id`,`name`,`slug`,`phone`,`email`,`city`,`status`,`updated_at`,`deleted_at`) VALUES ";
    $store_ids = array_map(function($i) {return $i["id"];}, $stores);
    $this->db->query("UPDATE `sa_stores` SET `deleted_at` = '{$date}' WHERE `id` > 0 AND `deleted_at` IS NULL AND `type` = 'system'");
    $exist_stores = $this->db->query("SELECT `id`,`id_from_service`
                                      FROM `sa_stores`
                                      WHERE `id_from_service` IN ('".implode("','",$store_ids)."')
                                      AND `type` = 'system'");
    $exist_stores_ids = array_map(function($i) {return $i["id_from_service"];}, $exist_stores->result_array());
    // return $exist_stores_ids;
    $store_insert_list = [];
    $store_update_list = [];
    foreach ($stores as $index => $store) {
      if ($store["id"]) {
        $slug = slugify($store["name"])."-".rand(100000000,9999999999);
        if (!in_array($store["id"],$exist_stores_ids)) {
          $store_insert_list[] = " ('".trim($store["id"])."',".isNullSQL($store["ava_id"]).",".isNullSQL($store["name"]).",
                                      ".isNullSQL($slug).",".isNullSQL($store["phone"]).",".isNullSQL($store["email"]).",".isNullSQL($store["city"]).",'1',NULL,NULL)";
        }else{
          $store_update_list[] = " ('".trim($store["id"])."',".isNullSQL($store["ava_id"]).",".isNullSQL($store["name"]).",
                                      ".isNullSQL($slug).",".isNullSQL($store["phone"]).",".isNullSQL($store["email"]).",".isNullSQL($store["city"]).",'1','{$date}',NULL)";
        }
      }
    }

    $query = $insert_title.implode(",",$store_insert_list).";";
    $update_query = $insert_title.implode(",",$store_update_list)." ON DUPLICATE KEY UPDATE `id_from_service`=VALUES(`id_from_service`), `ava_id`=VALUES(`ava_id`),`name`=VALUES(`name`),`slug`=`slug`, `phone`=VALUES(`phone`),`email`=VALUES(`email`),
                                                  `city`=VALUES(`city`),`status`=VALUES(`status`),`updated_at`=VALUES(`updated_at`),`deleted_at`=VALUES(`deleted_at`);";

    count($store_insert_list) ? $this->db->query($query) : "";
    count($store_update_list) ? $this->db->query($update_query) : "";

    // $afftectedRows=$this->db->affected_rows();
    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Success"),
      [
        "added" => count($store_insert_list),
        "updated" => count($store_update_list)
      ]
    );
  }

  /*
  * Getting all stores if it not deleted or status is active (1)
  */
  function getList($params){
    $keyword = $params["keyword"];
    $city = $params["city"];
    $region = $params["region"];
    $carbrand = $params["carbrand"];
    $storetag = $params["storetag"];

    $limit = $params["limit"];
    $offset = $params["offset"];

     // && $offset >= $limit
    if (is_numeric($limit) && is_numeric($offset) && $offset > 0) {
      $limit_query = " LIMIT $limit OFFSET $offset ";
    }elseif(is_numeric($limit)){
      $limit_query = $limit > 0 ? " LIMIT $limit " : "";
    }else{
      $limit_query = " LIMIT 12 ";
    }


    $keyword_query = $keyword ? " AND CASE WHEN sa.`custom_name` IS NOT NULL OR sa.`custom_name` <> '' THEN sa.`custom_name` ELSE sa.`name` END LIKE '%$keyword%' " : "";
    $city_query = $city ? " AND `city` = '{$city}' " : "";


    $storetag_query = "";$storetag_sub_query = "";
    if ($storetag) {
      $storetags = !is_array($storetag) ? explode(",",$storetag) : $storetag;
      $storetags = array_filter(array_unique($storetags));
      $ids = array_filter(array_map(function($st){ return is_numeric($st) ? $st : ""; },$storetags));
      $storetag_sub_query = $ids ? " LEFT JOIN (SELECT det.store_id
                                                FROM `sa_store_details` as det
                                                WHERE det.`type` = 'storetags'
                                                AND det.`value` IN (".implode(',',$ids).")
                                                AND det.`deleted_at` IS NULL
                                                GROUP BY det.store_id) filter_storetags
                                     ON filter_storetags.store_id = sa.id  " : "";
      $storetag_query = $ids ? " AND filter_storetags.`store_id` IS NOT NULL " : "";
    }

    $region_sub_query = "";$region_query = "";
    if ($region) {
      $regions = !is_array($region) ? explode(",",$region) : $region;
      $regions = array_filter(array_unique($regions));
      $ids = array_filter(array_map(function($r){ return is_numeric($r) ? $r : ""; },$regions));
      $region_sub_query = $ids ? " LEFT JOIN (SELECT det.store_id
                                              FROM `sa_store_details` as det
                                              WHERE det.`type` = 'region'
                                              AND det.`value` IN (".implode(',',$ids).")
                                              AND det.`deleted_at` IS NULL
                                              GROUP BY det.store_id) filter_regions
                                     ON filter_regions.store_id = sa.id  " : "";
      $region_query = $ids ? " AND filter_regions.`store_id` IS NOT NULL " : "";
    }



    $carbrand_sub_query = "";$carbrand_query = "";
    if ($carbrand) {
      $carbrands = !is_array($carbrand) ? explode(",",$carbrand) : $carbrand;
      $carbrands = array_filter(array_unique($carbrands));
      $ids = array_filter(array_map(function($c){ return is_numeric($c) ? $c : ""; },$carbrands));
      $carbrand_sub_query = $ids ? " LEFT JOIN (SELECT det.store_id
                                                FROM `sa_store_details` as det
                                                WHERE det.`type` = 'carbrand'
                                                AND det.`deleted_at` IS NULL
                                                AND det.`value` IN (".implode(',',$ids).")
                                                GROUP BY det.store_id) filter_carbrands
                                     ON filter_carbrands.store_id = sa.id  " : "";
      $carbrand_query = $ids ? " AND filter_carbrands.`store_id` IS NOT NULL " : "";
    }

    $sql_query = "SELECT sa.`id`,CASE WHEN sa.`custom_slug` IS NOT NULL AND TRIM(sa.`custom_slug`) <> '' THEN sa.`custom_slug` ELSE sa.`slug` END AS slug,sa.`avatar`,
                        CASE WHEN sa.`custom_name` IS NOT NULL OR sa.`custom_name` <> '' THEN sa.`custom_name` ELSE sa.`name` END AS `name`,
                         carbrands.`list` as carbrands,
                         regions.`list` as regions,sa.`city`
                  FROM `sa_stores` sa
                  LEFT JOIN (
                    SELECT det.`store_id`, GROUP_CONCAT(DISTINCT det.`value` SEPARATOR '|ARRAY_DIVIDER|') AS list
                    FROM `sa_store_details` as det
                    WHERE det.`type` = 'carbrand'
                    GROUP BY det.`store_id`) carbrands ON carbrands.`store_id` = sa.`id`
                  LEFT JOIN (
                    SELECT det.`store_id`, GROUP_CONCAT(DISTINCT det.`value` SEPARATOR '|ARRAY_DIVIDER|') AS list
                    FROM `sa_store_details` as det
                    WHERE det.`type` = 'region'
                    GROUP BY det.`store_id`) regions ON regions.`store_id` = sa.`id`
                  {$carbrand_sub_query}
                  {$region_sub_query}
                  {$storetag_sub_query}
                  WHERE sa.`deleted_at` IS NULL
                  AND CONVERT(SUBSTRING_INDEX(sa.`status`,'-',-1),UNSIGNED INTEGER) = 1 {$city_query} {$keyword_query}
                  {$region_query} {$carbrand_query} {$storetag_query}
                  ORDER BY sa.`order`,sa.`id` DESC
                  $limit_query";
    $stores_query = $this->db->query($sql_query);

    $stores_count_query = $this->db->query("SELECT COUNT(sa.`id`) as cnt FROM `sa_stores` sa {$carbrand_sub_query}
                                            {$region_sub_query} {$storetag_sub_query}
                                            WHERE sa.`deleted_at` IS NULL AND CONVERT(SUBSTRING_INDEX(sa.`status`,'-',-1),UNSIGNED INTEGER) = 1 {$city_query} {$keyword_query}
                                            {$region_query} {$carbrand_query} {$storetag_query}");
    $stores = $stores_query->result_array();


    $store_count = $stores_count_query->row(); // number of stores

    $new_list = [];
    $carbrands = carbrand_list();
    $regions = regions_list();

    foreach ($stores as $key => $store) {
      $store_regions = [];
      $store_carbrands = [];
      foreach (explode("|ARRAY_DIVIDER|",$store["regions"]) as $reg) {
        if (isset($regions[$reg])) $store_regions[] = $regions[$reg];
      }

      foreach (explode("|ARRAY_DIVIDER|",$store["carbrands"]) as $cb) {
        if(isset($carbrands[$cb])) $store_carbrands[] = $carbrands[$cb];
      }
      $avatar = $store["avatar"] !== "store-default-avatar.png" ?
                        rtrim($this->config->item("cdn_cisct_url"), "/ ").$store["avatar"] :
                          "store-default-avatar.png";
      $new_list[] = [
        "id" => $store["id"],
        "name" => $store["name"],
        "slug" => $store["slug"],
        "image" => $avatar,
        "city" => $store["city"],
        "regions" => $store_regions,
        "carbrands" => $store_carbrands
      ];
    }

    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Success"),
      [
        "count" => $store_count->cnt,
        "stores" => $new_list
      ]
    );

  }

  /*
  * store tag list
  */
  function tagList($params){
    $lang = $params["lang"] ?: "az";
    $tags_query = $this->db->select("id,name,slug")
                        ->from("sa_store_tags")
                          ->where("deleted_at",null)
                            ->where("lang",$lang)
                              ->order_by("order","desc")
                                ->get();
    $tags = $tags_query->result_array();
    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Success"),
      $tags
    );
  }

  /*
  * add new detail to store (one by one)
  */
  function addDetail($params){
    $possible_types = ['image','sn_linkedin','sn_twitter','sn_facebook','sn_instagram','sn_whatsapp','phone','address','email','map_longitude','map_latitude','region','area','storetags','carbrand','website','youtube'];
    // description
    $user = $params["user"];
    $type = $params["type"];
    $value = htmlentities($params["value"]);
    $store_id = $params["store_id"];

    if (!$user || !$type || !$value || !$store_id) {
      $msg = $this->lang->line("Missed parameters");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    if (!in_array($type,$possible_types)) {
      $msg = $this->lang->line("Unknow detail type");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $exist_store = $this->db->select("*")->from("sa_stores")->where("id",$store_id)->where("deleted_at",null)->get();
    if (!$exist_store->num_rows()) {
      $msg = $this->lang->line("Store not found");
      return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
    }
    $exist_store_details = $this->db->select("*")
                                      ->from("sa_store_details")
                                        ->where("store_id",$store_id)
                                          ->where("type",$type)
                                            ->where("deleted_at",null)
                                              ->get();

    $exist_details_list = $exist_store_details->result_array();
    $exist_details = [];
    foreach ($exist_details_list as $key => $detail) {
      $exist_details[] = $detail["value"];
    }

    $validated_values = [];
    if (!is_array($value)) {
      if ($type === "email") {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
          $msg = $this->lang->line("Invalid email type");
          return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
        }
      }elseif(in_array($type,['sn_facebook','sn_instagram','sn_linkedin','sn_twitter','sn_youtube','sn_whatsapp']) && isset(parse_url($value)['host']) &&  preg_replace('#^(http(s)?://)?w{3}\.#', '$1', parse_url($value)['host']) !== substr($type,3).'.com'){
        $msg = sprintf($this->lang->line("Invalid url for _"),ucwords(substr($type,3)));
        return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
      }elseif($type === "image"){
        $body = [
          "source" => $this->config->item("cdn_source_name"),
          "folder" => $this->config->item("store_gallery_img_path"),
          "files" => [$value]
        ];
        $img_names = callDataService('GET',null,$body,$this->config->item("cdn_cisct_url")."services/cdn/upload_media");

        if (!isset($img_names["code"]) || $img_names["code"] !== 200) {
          return rest_response(Status_codes::HTTP_NOT_IMPLEMENTED,$this->lang->line("Internal server error"),[]);
        }
        $value = $img_names["data"][0]["file_url"];
      }
      $validated_values = [$value];
    }else{
      foreach (array_filter($value) as $item) {
        if ($type === "email") {
          if (filter_var($item, FILTER_VALIDATE_EMAIL)) {
            $validated_values[] = $item;
          }
        }elseif(in_array($type,['sn_facebook','sn_instagram','sn_linkedin','sn_twitter','sn_youtube','sn_whatsapp']) && preg_replace('#^(http(s)?://)?w{3}\.#', '$1', parse_url($value)['host']) === substr($type,3).'.com'){
          $validated_values[] = $item;
        }elseif($type === "image"){
          $body = [
            "source" => $this->config->item("cdn_source_name"),
            "folder" => $this->config->item("store_gallery_img_path"),
            "files" => $value
          ];
          $img_names = callDataService('GET',null,$body,$this->config->item("cdn_cisct_url")."services/cdn/upload_media");

          if (!isset($img_names["code"]) || $img_names["code"] !== 200) {
            return rest_response(Status_codes::HTTP_NOT_IMPLEMENTED,$this->lang->line("Internal server error"),[]);
          }

          $validated_values = array_map(function($i){
            return $i["file_url"];
          },$img_names["data"]);
        }else{
          $validated_values[] = $item;
        }
      }

      if (!count($validated_values)) {
        if ($type === "email") {
          $msg = $this->lang->line("Invalid email type");
          return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
        }elseif(in_array($type,['sn_facebook','sn_instagram','sn_linkedin','sn_twitter','sn_youtube','sn_whatsapp'])){
          $msg = $this->lang->line("Invalid url");
          return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
        }
      }

    }



    if (is_array($validated_values) && count($validated_values)) {
      $insert_list = [];
      foreach ($validated_values as $item) {
        if (!in_array($item,$exist_details)) {
          $insert_list[] = [
            "store_id" => $store_id,
            "type" => $type,
            "value" => $item
          ];
        }
      }
      $insert_list ? $this->db->insert_batch("sa_store_details",$insert_list) : "";
    }else{
      if (!in_array($validated_values[0],$exist_details)) {
        $insert_list = [
          "store_id" => $store_id,
          "type" => $type,
          "value" => $validated_values[0]
        ];
        $this->db->insert("sa_store_details",$insert_list);
      }
    }

    $details_list_query = $this->db->select("id,type,value")
                                    ->from("sa_store_details")
                                      ->where("store_id",$store_id)
                                        ->where("deleted_at",null)
                                          ->where("type",$type)
                                            ->get();

    $details_list = $details_list_query->result_array();
    $response_body = [];
    if ($type === "image") {
      foreach ($details_list as $img) {
        $response_body[] = [
          "id" => $img["id"],
          "type" => "image",
          "value" => rtrim($this->config->item("cdn_cisct_url"), "/ ").$img["value"]
        ];
      }
    }elseif ($type === "carbrand") {
      $carbrands = carbrand_list();
      foreach ($details_list as $brand) {
        if (isset($carbrands[$brand["value"]])) {
          $response_body[] = [
            "id" => $brand["id"],
            "type" => "carbrand",
            "value" => $carbrands[$brand["value"]]
          ];
        }
      }
    }elseif ($type === "region") {
      $regions = regions_list();
      foreach ($details_list as $region) {
        if (isset($regions[$region["value"]])) {
          $response_body[] = [
            "id" => $region["id"],
            "type" => "region",
            "value" => $regions[$region["value"]]
          ];
        }
      }
    }elseif($type === "storetags"){
      $storetags_query = $this->db->select("id,name")->from("sa_store_tags")->where("deleted_at",null)->get();
      $storetags = $storetags_query->result_array();
      $store_tags_list = [];
      foreach ($storetags as $key => $tag) {
        $store_tags_list = $store_tags_list + [$tag["id"] => $tag["name"]];
      }
      foreach ($details_list as $tag) {
        if (isset($store_tags_list[$tag["value"]])) {
          $response_body[] = [
            "id" => $tag["id"],
            "type" => "storetags",
            "value" => $store_tags_list[$tag["value"]],
          ];
        }
      }
    }else{
      $response_body = $details_list;
    }

    return rest_response(
      Status_codes::HTTP_CREATED,
      $this->lang->line("Detail added to store"),
      $response_body
    );
  }

  /*
  * change store name
  */
  function changeName($params){
    $user = $params["user"];
    $store_id = $params["store_id"];
    $name = htmlentities($params["name"]);

    if (!$user || !$name || !$store_id) {
      $msg = $this->lang->line("Missed parameters");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $exist_store = $this->db->select("*")->from("sa_stores")
                              ->where("id",$store_id)
                                ->where("deleted_at",null)
                                  ->get();
    if (!$exist_store->num_rows()) {
      $msg = $this->lang->line("Store not found");
      return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
    }


    $update_list = [
      "custom_slug" => slugify($name).'-'.((int)$store_id + 11)*3432,
      "custom_name" => $name,
    ];

    $this->db->where("id",$store_id);
    $this->db->update("sa_stores",$update_list);

    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Store detail updated"),
      [
        "custom_slug" => $update_list["custom_slug"]
      ]
    );
  }

  /*
  * update store detail (one by one)
  */
  function updateDetail($params){
    $possible_types = ['sn_linkedin','sn_twitter','sn_facebook','sn_instagram','sn_whatsapp','phone','address','email','map_longitude','map_latitude','region','area','storetags','carbrand','website','youtube'];
    // description
    $user = $params["user"];
    $type = $params["type"];
    $value = htmlentities($params["value"]);
    $store_id = $params["store_id"];
    $detail_id = $params["detail_id"];

    if (!$user || !$type || !$value || !$store_id || !$detail_id) {
      $msg = $this->lang->line("Missed parameters");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    if (!in_array($type,$possible_types)) {
      $msg = $this->lang->line("Unknow detail type");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $exist_store = $this->db->select("*")->from("sa_store_details")
                              ->where("store_id",$store_id)
                                ->where("id",$detail_id)
                                  ->where("deleted_at",null)
                                    ->get();
    if (!$exist_store->num_rows()) {
      $msg = $this->lang->line("Detail not found");
      return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
    }


    if ($type === "email") {
      if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        $msg = $this->lang->line("Invalid email type");
        return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
      }
    }elseif(in_array($type,['sn_facebook','sn_instagram','sn_linkedin','sn_twitter','sn_youtube','sn_whatsapp']) && isset(parse_url($value)['host']) && preg_replace('#^(http(s)?://)?w{3}\.#', '$1', parse_url($value)['host']) !== substr($type,3).'.com'){
      $msg = sprintf($this->lang->line("Invalid url for _"),ucwords(substr($type,3)));
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }


    $update_list = [
      "store_id" => $store_id,
      "type" => $type,
      "value" => $value
    ];

    $this->db->where("id",$detail_id);
    $this->db->update("sa_store_details",$update_list);

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Store detail updated"),[]);
  }

  /*
  * update store business hours
  */
  function updateBusinessHours($params){
    $user = $params["user"];
    $store_id = $params["store_id"];
    $week_day = $params["week_day"];

    if (!$user || !$store_id || !$week_day) {
      $msg = $this->lang->line("Missed parameters");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    if (count($week_day) < 7) {
      $msg = $this->lang->line("Not all week days");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $exist_store = $this->db->select("*")->from("sa_stores")
                              ->where("id",$store_id)
                                  ->where("deleted_at",null)
                                    ->get();
    if (!$exist_store->num_rows()) {
      $msg = $this->lang->line("Store not found");
      return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
    }

    $insert_list = [];

    foreach ($week_day as $key => $item) {
      if (!isset($item["status"]) ||
            !isset($item["start_time"]) ||
                !isset($item["finish_time"])) {
        $msg = $this->lang->line("Unknown week data");
        return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
      }
      $insert_list[] = [
        "store_id" => $store_id,
        "week_day" => $this->week_days[$key],
        "start_at" => $item["start_time"],
        "finish_at" => $item["finish_time"],
        "status" => (string)$item["status"],
      ];
    }

    $this->db->where("store_id",$store_id);
    $this->db->delete("sa_store_business_hours");

    $this->db->insert_batch("sa_store_business_hours",$insert_list);

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Store business hour updated"),[]);
  }

  /*
  * update store description
  */
  function updateStoreDescription($params){
    $user = $params["user"];
    $store_id = $params["store_id"];
    $description = $params["description"];
    $type = "description";

    if (!$user || !$description || !$store_id) {
      $msg = $this->lang->line("Missed parameters");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];


    $exist_store = $this->db->select("*")->from("sa_stores")
                              ->where("id",$store_id)
                                  ->where("deleted_at",null)
                                    ->get();
    if (!$exist_store->num_rows()) {
      $msg = $this->lang->line("Store not found");
      return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
    }

    $exist_store_desc = $this->db->select("*")->from($this->detail_table)
                                  ->where("store_id",$store_id)
                                    ->where("type",$type)
                                      ->where("deleted_at",null)
                                        ->limit(1)
                                          ->get();

    if (!$exist_store_desc->num_rows()) {
      $insert_list = [
        "store_id" => $store_id,
        "type" => $type,
        "value" => $description
      ];

      $this->db->insert($this->detail_table,$insert_list);
    }else{
      $update_list = [
        "store_id" => $store_id,
        "type" => $type,
        "value" => $description
      ];

      $this->db->where("id",$exist_store_desc->row()->id);
      $this->db->update($this->detail_table,$update_list);
    }


    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Store detail updated"),[]);


  }

  /*
  * delete store detail
  */
  function deleteDetail($params){
    $user = $params["user"];
    $store_id = $params["store_id"];
    $detail_id = $params["detail_id"];
    $date = $params["date"];

    if (!$user || !$store_id || !$detail_id) {
      $msg = $this->lang->line("Missed parameters");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $exist_store = $this->db->select("*")->from("sa_store_details")
                              ->where("store_id",$store_id)
                                ->where("id",$detail_id)
                                  ->where("deleted_at",null)
                                    ->get();
    if (!$exist_store->num_rows()) {
      $msg = $this->lang->line("Detail not found");
      return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
    }


    $updated_list = [
      "deleted_at" => $date
    ];

    $this->db->where("id",$detail_id);
    $this->db->update("sa_store_details",$updated_list);

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Store detail deleted"),[]);
  }

  /*
  * Getting all stores if it not deleted or status is active (1)
  * for admin access
  */
  function getListFullData($params){
    $user = $params["user"];
    $city = $params["city"];
    $limit = $params["limit"];
    $offset = $params["offset"];


    if (!$user) {
      $msg = $this->lang->line("Missed parameters");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }


    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    if (is_numeric($limit) && is_numeric($offset) && $offset > 0 && $offset >= $limit) {
      $limit_query = " LIMIT $limit OFFSET $offset ";
    }elseif(is_numeric($limit)){
      $limit_query = $limit > 0 ? " LIMIT $limit " : "";
    }else{
      $limit_query = " LIMIT 12 ";
    }


    $city_query = $city ? " AND `city` = '{$city}' " : "";
    $stores_query = $this->db->query("SELECT sa.`id`,sa.`id_from_service`,
                                              sa.`ava_id`,
                                              CASE WHEN sa.`custom_name` IS NOT NULL OR sa.`custom_name` <> '' THEN sa.`custom_name` ELSE sa.`name` END AS `name`,
                                              CASE WHEN sa.`custom_slug` IS NOT NULL OR sa.`custom_slug` <> '' THEN sa.`custom_slug` ELSE sa.`slug` END AS `slug`,
                                              images.`name` as image,sa.`city`,
                                              sa.`status`,sa.`created_at`
                                      FROM `sa_stores` sa
                                      LEFT JOIN (SELECT `name`,`store_id`
                                                  FROM `sa_store_images`
                                                  WHERE `deleted_at` IS NULL
                                                  AND CONVERT(SUBSTRING_INDEX(`status`,'-',-1),UNSIGNED INTEGER) = 1
                                                  ORDER BY `order` DESC
                                                  LIMIT 1) AS images
                                      ON images.`store_id` = sa.`id`
                                      WHERE sa.`deleted_at` IS NULL
                                      {$city_query}
                                      ORDER BY sa.`order`,sa.`id` DESC
                                      $limit_query");
    $stores_count_query = $this->db->query("SELECT COUNT(sa.`id`) as cnt FROM `sa_stores` sa WHERE sa.`deleted_at` IS NULL {$city_query}");
    $stores = $stores_query->result_array();
    $store_count = $stores_count_query->row();
    return rest_response(Status_codes::HTTP_OK, $this->lang->line("Success"), ["count" => $store_count->cnt,"stores" => $stores]);
  }

  /*
  * Store details
  */
  function storeDetails($slug = null,$id = null){
    if (!$slug && !$id) {
      return rest_response(Status_codes::HTTP_CONFLICT,$this->lang->line("Slug cannot be empty"),[]);
    }

    $slug_escaped = $this->db->escape($slug);
    $id_escaped = $this->db->escape_str($id);


    $slug_query = $slug ? " AND (`slug` = $slug_escaped OR `custom_slug` = $slug_escaped) " : "";
    $id_query = $id ? " AND id = $id_escaped " : "";
    $sql_query = "SELECT
                    `id`,`id_from_service`,`ava_id`,`avatar`,
                    CASE WHEN `custom_name` IS NOT NULL OR `custom_name` <> '' THEN `custom_name` ELSE `name` END AS `name`,
                    CASE WHEN `custom_slug` IS NOT NULL OR `custom_slug` <> '' THEN `custom_slug` ELSE `slug` END AS `slug`,
                    `city`,`phone`,`email`,`description`
                  FROM `sa_stores` sa
                  WHERE `deleted_at` IS NULL
                  AND CONVERT(SUBSTRING_INDEX(sa.`status`,'-',-1),UNSIGNED INTEGER) = 1 {$slug_query} {$id_query}
                  ORDER BY `order` DESC
                  LIMIT 1";
    // var_dump($sql_query);die;
    $stores_query = $this->db->query($sql_query);
    if (!$stores_query->num_rows()) {
      return rest_response(Status_codes::HTTP_CONFLICT,$this->lang->line("Store not found"),[]);
    }
    $stores = (array)$stores_query->row();

    $store_id = $stores["id"];

    $store_details_query = $this->db->query("SELECT sd.`id`,sd.`type`,sd.`extra_value`,
                                                    CASE WHEN sd.`type` = 'storetags' THEN CONCAT(st.`name`,'|NAME_AND_ID_SEPARATOR|',st.`id`) ELSE sd.`value` END AS `value`,
                                                    sd.`verified`
                                             FROM `sa_store_details` sd
                                             LEFT JOIN `sa_store_tags` st ON (sd.`type` = 'storetags' AND sd.`value` = st.`id`)
                                             WHERE sd.`store_id` = {$store_id}
                                             AND sd.`deleted_at` IS NULL
                                             AND CASE WHEN sd.`type` = 'storetags' THEN st.`deleted_at` ELSE sd.`deleted_at` END IS NULL
                                             ORDER BY sd.`order` ASC");
    $store_details = $store_details_query->result_array();

    $business_hours_query = $this->db->query("SELECT sb.`id`,sb.`week_day`,TIME_FORMAT(sb.`start_at`, '%H:%i') as `start_at`,
                                                      TIME_FORMAT(sb.`finish_at`, '%H:%i') as `finish_at`,sb.`status`
                                               FROM `sa_store_business_hours` sb
                                               WHERE sb.`store_id` = {$store_id}
                                               AND sb.`deleted_at` IS NULL
                                               ORDER BY CASE
                                                    WHEN sb.`week_day` = 'monday' THEN 1
                                                    WHEN sb.`week_day` = 'tuesday' THEN 2
                                                    WHEN sb.`week_day` = 'wednesday' THEN 3
                                                    WHEN sb.`week_day` = 'thursday' THEN 4
                                                    WHEN sb.`week_day` = 'friday' THEN 5
                                                    WHEN sb.`week_day` = 'saturday' THEN 6
                                                    ELSE 7
                                               END ASC");

    $business_hours_list = $business_hours_query->result_array();
    $business_hours = [];

    foreach ($business_hours_list as $key => $b) {
      $business_hours = $business_hours + [
        array_search($b["week_day"],$this->week_days) => [
          "start_at" => $b["start_at"],
          "finish_at" => $b["finish_at"],
          "status" => $b["status"] ? true : false,
        ]
      ];
    }


    $details = [
      "email" => new stdClass(),
      "phone" => new stdClass(),
      "image" => new stdClass(),
      "address" => new stdClass(),
      "social_networks" => [
        "linkedin" => new stdClass(),
        "twitter" => new stdClass(),
        "facebook" => new stdClass(),
        "instagram" => new stdClass(),
        "whatsapp" => new stdClass()
      ],
      "region" => new stdClass(),
      "website" => new stdClass(),
      "area" => new stdClass(),
      "storetags" => new stdClass(),
      "carbrand" => new stdClass(),
      "business_hours" => $business_hours ?: new stdClass(),
      "description" => new stdClass()
    ];

    $all_types = array_map(function($d){return $d["type"];},$store_details);

    $carbrands = [];$regions = [];
    if (in_array("carbrand",$all_types)) {
      $carbrands = carbrand_list();
    }

    if (in_array("region",$all_types)) {
      $regions = regions_list();
    }



    foreach ($store_details as $key => $detail) {
      if (in_array($detail["type"],["sn_linkedin","sn_twitter","sn_facebook","sn_instagram","whatsapp"])) {
        $value = $detail["value"];
        $details["social_networks"][substr($detail["type"], 3)] =
            (array)$details["social_networks"][substr($detail["type"], 3)] + ["id" => $detail["id"],"val" => $value];
      }elseif($detail["type"] === "carbrand"){
        $value = isset($carbrands[$detail["value"]]) ? $carbrands[$detail["value"]] : null;
        $details[$detail["type"]] = array_merge((array)$details[$detail["type"]],[["id" => $detail["id"],"val" => $value]]);
      }elseif($detail["type"] === "region"){
        $value = isset($regions[$detail["value"]]) ? $regions[$detail["value"]] : null;
        $details[$detail["type"]] = array_merge((array)$details[$detail["type"]],[["id" => $detail["id"],"val" => $value]]);
      }elseif($detail["type"] === "area"){

      }elseif($detail["type"] === "image"){
        $value = rtrim($this->config->item("cdn_cisct_url"), "/ "). $detail["value"];
        $details[$detail["type"]] = array_merge((array)$details[$detail["type"]],[["id" => $detail["id"],"val" => $value]]);
      }elseif($detail["type"] === "storetags"){
        $list = explode("|NAME_AND_ID_SEPARATOR|",$detail["value"]);
        if (isset($list[1]) && isset($list[0])) {
          $value = ["id" => $list[1],"name" => $list[0]];
          $details[$detail["type"]] = array_merge((array)$details[$detail["type"]],[["id" => $detail["id"],"val" => $value]]);
        }
      }else{
        $value = $detail["value"];
        $details[$detail["type"]] = array_merge((array)$details[$detail["type"]],[["id" => $detail["id"],"val" => $value]]);
      }
    }

    $avatar = $stores["avatar"] === "store-default-avatar.png" ? $stores["avatar"] :
                  rtrim($this->config->item("cdn_cisct_url"), "/ ").$stores["avatar"];


    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Success"),
      array_merge($stores,["avatar" => $avatar]) + ["details" => $details]
    );
  }

  /*
  * Change avatar
  */
  function updateAvatar($params){
    $user = $params["user"];
    $store_id = $params["store_id"];
    $image = $params["image"];

    if (!$user || !$store_id || !$image) {
      $msg = $this->lang->line("Missed parameters");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $exist_store = $this->db->select("*")->from("sa_stores")->where("id",$store_id)->where("deleted_at",null)->get();
    if (!$exist_store->num_rows()) {
      $msg = $this->lang->line("Store not found");
      return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
    }

    $body = [
      "source" => $this->config->item("cdn_source_name"),
      "folder" => $this->config->item("store_gallery_img_path"),
      "files" => [$image]
    ];
    $img_names = callDataService('GET',null,$body,$this->config->item("cdn_cisct_url")."services/cdn/upload_media");

    if (!isset($img_names["code"]) || $img_names["code"] !== 200 || !isset($img_names["data"][0]["file_url"])) {
      return rest_response(Status_codes::HTTP_NOT_IMPLEMENTED,$this->lang->line("Internal server error"),[]);
    }


    $avatar = $img_names["data"][0]["file_url"];
    $this->db->where("id",$store_id);
    $this->db->update("sa_stores",["avatar" => $avatar]);

    return rest_response(Status_codes::HTTP_CREATED,$this->lang->line("Store avatar updated"),["new_image" => rtrim($this->config->item("cdn_cisct_url"), "/ ").$avatar]);
  }

  /*
  * Change store status
  */
  function updateStatus($params){
    $user = $params["user"];
    $store_id = $params["store_id"];
    $status = $params["status"];

    if (!$user || !$store_id) {
      $msg = $this->lang->line("Missed parameters");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $exist_store = $this->db->select("status")->from("sa_stores")->where("id",$store_id)->where("deleted_at",null)->get();
    if (!$exist_store->num_rows()) {
      $msg = $this->lang->line("Store not found");
      return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
    }

    if ($exist_store->row()->status !== $status) {
      $update_list = [
        "status" => $status
      ];
      $this->db->where("id",$store_id);
      $this->db->update("sa_stores",$update_list);
    }


    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Store status updated"));
  }

  /*
  * Region list from AVA
  */
  function regionList(){
    $stores_query = $this->db->query("SELECT DISTINCT `city`
                                      FROM `sa_stores` sa
                                      WHERE `deleted_at` IS NULL
                                      AND CONVERT(SUBSTRING_INDEX(sa.`status`,'-',-1),UNSIGNED INTEGER) = 1
                                      AND `city` IS NOT NULL
                                      ORDER BY `city` ASC");
    $stores = $stores_query->result_array();
    $regions = array_map(function($i){ return $i["city"];},$stores);
    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),$regions);
  }

  /*
  *  Creating new store
  */
  function createStore($params){
    $user = $params["user"];
    $name = $params["name"];
    $city = $params["city"];
    $phone = $params["phone"];
    $email = $params["email"];
    $status = $params["status"];
    $description = $params["description"];
    $type = "local";

    if (!$user || !$name || !$city || !$phone) {
      $msg = $this->lang->line("Missed parameters");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $msg = $this->lang->line("Invalid email format");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $escaped_phone = $this->db->escape(trim($phone));
    $exist_store_sql = "SELECT st.`id`
                        FROM `sa_stores` st
                        LEFT JOIN `sa_store_details` std ON std.`store_id` = st.`id`
                              AND std.`deleted_at` IS NULL AND std.`type` = 'phone'
                        WHERE (st.`phone` = $escaped_phone OR std.`value` = $escaped_phone)
                        AND st.`deleted_at` IS NULL";
    $exist_store = $this->db->query($exist_store_sql);
    if ($exist_store->num_rows()) {
      $msg = $this->lang->line("Store already created");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];
    $user_id = $u["body"]["id"];


    $list = [
      "creator_id" => $user_id,
      "custom_name" => $name,
      "custom_slug" => slugify($name)."-".md5(microtime()),
      "city" => $city,
      "type" => $type,
      "description" => $description,
      "phone" => $phone,
      "email" => $email,
    ];


    $this->db->insert("sa_stores",$list);
    $store = $this->db->insert_id();

    $insert_array = [];
    if ($phone) {
      $insert_array[] = [
        "type" => "phone",
        "value" => $phone,
        "store_id" => $store
      ];
    }
    if ($email) {
      $insert_array[] = [
        "type" => "email",
        "value" => $email,
        "store_id" => $store
      ];
    }
    if ($description) {
      $insert_array[] = [
        "type" => "description",
        "value" => $description,
        "store_id" => $store
      ];
    }

    $insert_array ? $this->db->insert_batch("sa_store_details",$insert_array) : "";

    $custom_slug = slugify($name)."-".(($store + 4545) * 8745);

    $sql = "UPDATE `sa_stores` AS t_one,
            (SELECT `order` FROM `sa_stores` ORDER BY `order` DESC LIMIT 1) AS t_two
            SET t_one.`order` = t_two.`order` + 1,
            t_one.`custom_slug` = '$custom_slug'
            WHERE `id` = $store";
    $this->db->query($sql);



    return rest_response(
      Status_codes::HTTP_CREATED,
      $this->lang->line("Store created"),
      [
        "id" => $store,
        "slug" => $custom_slug,
      ]
    );
  }

}
