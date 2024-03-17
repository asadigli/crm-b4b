<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }


  function index($params){
    escapeAllKeys($params,null,$this->local_db);
    $b4b_online_time = $this->config->item("b4b_online_time");
    $keyword_query = "";
    if ($params["keyword"] && trim($params["keyword"])) {
      $keyword_sub_query = [];
      foreach (explode(",",$params["keyword"]) as $key => $item) {
        if ($item && trim($item)) {
          $keyword = "'%{$item}%'";
          $keyword_sub_query[] = " (entry.`company_name` LIKE $keyword
                                      OR entry.`company_email` LIKE $keyword
                                      OR entry.`company_phone` LIKE $keyword
                                      OR city.`city_name` LIKE $keyword
                                      OR depo.`depo_name` LIKE $keyword
                                      OR entry.`person_name` LIKE $keyword
                                      OR entry.`person_surname` LIKE $keyword
                                      OR curator.`curator_name` LIKE $keyword

                                      OR (SELECT
                                          GROUP_CONCAT(CONCAT(customer.`name`,'||',customer.`remote_id`,'||',currency.`main_name`) SEPARATOR '-|||-')
                                      FROM `".local_table_name("b4b_entry_customers")."` entry_customer
                                      LEFT JOIN `".local_table_name("cached_customers")."`customer ON entry_customer.`customer_id` = customer.`id`
                                      LEFT JOIN `".local_table_name("cached_currencies")."` currency ON currency.`id` = customer.`currency_id`
                                      WHERE entry_customer.`entry_id` = entry.`company_id`
                                      AND entry_customer.`deleted_at` IS NULL) LIKE $keyword


                                      ".(is_numeric($item) ? " OR
                                      entry.`company_id` IN
                                    (SELECT `entry_id`
                                     FROM `".local_table_name("b4b_entry_customers")."` b4b_entry_customers
                                     WHERE b4b_entry_customers.`deleted_at` IS NULL
                                     AND b4b_entry_customers.`remote_customer_id` = {$item})) " : ")");
        }
      }
      $keyword_query = $keyword_sub_query ? " AND (".implode(" OR ",$keyword_sub_query).") " : "";
    }


    $limit_query = "";
    if ($params["limit"] && $params["offset"] && (int)$params["limit"] > 0 && (int)$params["offset"] > 0) {
      $limit_query = " LIMIT {$params["limit"]} OFFSET {$params["offset"]} ";
    }elseif($params["limit"] && (int)$params["limit"] > 0){
      $limit_query = " LIMIT {$params["limit"]} ";
    }

    $blocks_query = "";
    if ($params["search_by_blocks"]) {
      if ($params["search_by_blocks"] === "by_is_blocked") {
          $blocks_query = "AND entry.`is_blocked` = '".STATUS_ACTIVE."' ";
      } elseif ($params["search_by_blocks"] === "by_is_not_blocked") {
          $blocks_query = "AND entry.`is_blocked` = '".STATUS_DEACTIVE."' ";
      }
    }


    $head_sql = "SELECT
                        entry.`company_id` as id,
                        entry.`company_name` as name,
                        entry.`company_email` as email,
                        entry.`company_avatar` as avatar,
                        entry.`company_phone` as phone,
                        CONCAT(curator.`curator_name`,' ',curator.`curator_surname`) as curator,
                        entry.`company_curator_id` as curator_id,
                        curator.`curator_ava_name` as curator_ava_name,
                        entry.`person_name` as person_name,
                        entry.`person_surname` as person_surname,
                        city.`city_name` as city,
                        entry.`company_city_id` as city_id,
                        depo.`depo_name` as warehouse,
                        entry.`company_address` as address,
                        entry.`company_depo_id` as warehouse_id,
                        entry.`__company_ava_id` as ava_id,
                        entry.`company_ins_date` as added_date,
                        (SELECT
                            GROUP_CONCAT(CONCAT(customer.`name`,'||',customer.`remote_id`,'||',currency.`main_name`,'||',customer.`code`,'||',customer.`id`) SEPARATOR '-|||-')
                        FROM `".local_table_name("b4b_entry_customers")."` entry_customer
                        LEFT JOIN `".local_table_name("cached_customers")."`customer ON entry_customer.`customer_id` = customer.`id`
                        LEFT JOIN `".local_table_name("cached_currencies")."` currency ON currency.`id` = customer.`currency_id`
                        WHERE entry_customer.`entry_id` = entry.`company_id`
                        AND entry_customer.`deleted_at` IS NULL) as customer_details,
                        (SELECT
                            CONCAT(
                              SUM(CASE WHEN `status` IN ('".ETS_IDLE."','".ETS_RESERVED."') THEN 1 ELSE 0 END),'||',
                              SUM(CASE WHEN `status` IN ('".ETS_RESERVED."') THEN 1 ELSE 0 END),'||',
                              SUM(CASE WHEN `status` IN ('".ETS_RESERVED."') AND `device` IS NULL THEN 1 ELSE 0 END),'||',
                              SUM(CASE WHEN `status` IN ('".ETS_RESERVED."') AND `device` = 'web' THEN 1 ELSE 0 END),'||',
                              SUM(CASE WHEN `status` IN ('".ETS_RESERVED."') AND `device` = 'mobile' THEN 1 ELSE 0 END),'a1a',
                              GROUP_CONCAT(CASE WHEN `status` IN ('".ETS_RESERVED."') AND `device` = 'web' THEN `company_last_ip` ELSE 0 END SEPARATOR 'c3c'),'b2b',
                              GROUP_CONCAT(CASE WHEN `status` IN ('".ETS_RESERVED."') AND `device` = 'mobile' THEN `company_last_ip` ELSE 0 END SEPARATOR 'c3c'),'b2b',
                              GROUP_CONCAT(CASE WHEN `status` IN ('".ETS_RESERVED."') AND `device` IS NULL THEN `company_last_ip` ELSE 0 END SEPARATOR 'c3c')
                            ) as result
                         FROM `".local_table_name("b4b_entry_tokens")."`
                         WHERE `entry_id` = entry.`company_id`
                         AND `deleted_at` IS NULL) as entry_limits,
                        (SELECT CONCAT(COUNT(`entry_token`),'||',MAX(`online_at`),'||',MAX(`company_ip`)) as result
                         FROM `".local_table_name("b4b_onlines")."`
                         WHERE `online_at` > date_sub(now(), INTERVAL $b4b_online_time SECOND)
                         AND `company_id` = entry.`company_id`
                         GROUP BY `company_id`) as last_online,
                         entry.`price_show` as price_show,
                         entry.`stock_show` as stock_show,
                         entry.`is_store_active` as is_store_active,
                         entry.`is_blocked` as is_blocked
                         ";

    $entries_sql = " FROM `".local_table_name("b4b_companies")."` entry
                    LEFT JOIN `".local_table_name("b4b_curators")."` curator ON curator.`curator_id` = entry.`company_curator_id`
                    LEFT JOIN `".local_table_name("cities")."` city ON city.`city_id` = entry.`company_city_id`
                    LEFT JOIN `".local_table_name("warehouses")."` depo ON depo.`depo_id` = entry.`company_depo_id`
                    WHERE entry.`deleted_at` IS NULL
                    $keyword_query
                    $blocks_query
                    ";
                    // log_message('ERROR',$head_sql . $entries_sql);
    // var_dump($head_sql . $entries_sql . "ORDER BY last_online DESC,entry.`company_id` DESC ".  $limit_query);die;
    // CAST(RIGHT(REPLACE(REPLACE(REPLACE(last_online,'-',''),' ',''),':',''), 14) AS UNSIGNED) DESC,
    $order_by_query = $params["sort_by"] === "by_is_online" ? " ORDER BY CAST(RIGHT(REPLACE(REPLACE(REPLACE(last_online,'-',''),' ',''),':',''), 14) AS UNSIGNED) DESC, entry.`company_id` DESC " : " ORDER BY entry.`company_id` DESC  ";
    $entries_query = $this->local_db->query($head_sql . $entries_sql . $order_by_query .  $limit_query);

    if (!$entries_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Entry not found"),
        [
          "count" => 0
        ]
      );
    }
    $entries = $entries_query->result_array();
    // return $entries;
    foreach ($entries as $key => $entry) {
      $customers = [];
      if ($entry["customer_details"]) {
        $customer_details = explode("-|||-",$entry["customer_details"]);
        foreach ($customer_details as $sub_key => $sub_value) {
          $customers[$sub_key] = [
            "id" => $sub_value ? (isset(explode("||",$sub_value)[4]) ? explode("||",$sub_value)[4] : NULL) : NULL,
            "ava_code" => $sub_value ?  (isset(explode("||",$sub_value)[3]) ? explode("||",$sub_value)[3] : NULL) : NULL,
            "currency_name" => $sub_value ?  (isset(explode("||",$sub_value)[2]) ? explode("||",$sub_value)[2] : NULL) : NULL,
            "ava_id" => $sub_value ? (isset(explode("||",$sub_value)[1]) ? explode("||",$sub_value)[1] : NULL) : NULL,
            "name" => $sub_value ?  (isset(explode("||",$sub_value)[0]) ? explode("||",$sub_value)[0] : NULL) : NULL,
          ];
        }
      }
      $entries[$key]["warehouse"] = $entry["warehouse_id"] ? [
        "name" => $entry["warehouse"],
        "id" => $entry["warehouse_id"]
      ] : [];

      $entries[$key]["curator"] = $entry["curator_id"] ? [
        "name" => $entry["curator"],
        "id" => $entry["curator_id"],
      ] : [];

      $entries[$key]["city"] = $entry["city_id"] ? [
        "name" => $entry["city"],
        "id" => $entry["city_id"],
      ] : [];

      $entries[$key]["customers"] = $customers;
      $entries[$key]["price_show"]      = $entry["price_show"] === STATUS_ACTIVE;
      $entries[$key]["stock_show"]      = $entry["stock_show"] === STATUS_ACTIVE;

      $entries[$key]["stock_show"]      = $entry["stock_show"] === STATUS_ACTIVE;
      $entries[$key]["is_store_active"] = $entry["is_store_active"] === STATUS_ACTIVE;
      $entries[$key]["is_blocked"]      = $entry["is_blocked"] === STATUS_ACTIVE;

      $entry_limits                     = array_filter(explode("||",explode('a1a',$entry["entry_limits"])[0]));
      $entries[$key]["entry_limit"]     = count($entry_limits) ? (int)$entry_limits[0] : 0;

      // $entries[$key]["entry_limitg_ips_old"] = $entry["entry_limits"];
      $entries[$key]["entry_limit_ips"] = @explode("b2b",explode("a1a",$entry["entry_limits"])[1]) ?: [];
      $entry_limit_ips = [];
      foreach ($entries[$key]["entry_limit_ips"] as $sub_key => $sub_item) {
        switch ($sub_key) {
          case 0:
            $new_sub_key = "web";
            break;

          case 1:
            $new_sub_key = "mobile";
            break;

          default:
            $new_sub_key = "unknown";
            break;
        }
        $entry_limit_ips[$new_sub_key] = explode("c3c",$sub_item) != 0 ? array_filter(explode("c3c",$sub_item)) : [];
      }
      if (!isset($entry_limit_ips["web"]) || !is_array($entry_limit_ips["web"])) {
        $entry_limit_ips["web"] = [];
      }
      if (!isset($entry_limit_ips["mobile"]) || !is_array($entry_limit_ips["mobile"])) {
        $entry_limit_ips["mobile"] = [];
      }
      if (!isset($entry_limit_ips["unknown"]) || !is_array($entry_limit_ips["unknown"])) {
        $entry_limit_ips["unknown"] = [];
      }
      $entries[$key]["entry_limit_ips"] = $entry_limit_ips;

      $entries[$key]["used_limit"]      = count($entry_limits) ? [
        "all"       => @(int)$entry_limits[1],
        "unknown"   => @(int)$entry_limits[2],
        "web"       => @(int)$entry_limits[3],
        "mobile"    => @(int)$entry_limits[4],
      ] : [];


      $entries[$key]["last_online"]     = $entry["last_online"] ? explode("||",$entry["last_online"])[1] : NULL;
      $entries[$key]["online_count"]    = $entry["last_online"] ? (int)explode("||",$entry["last_online"])[0] : NULL;
      unset($entries[$key]["entry_limits"]);
      unset($entries[$key]["customer_details"]);
      unset($entries[$key]["city_id"]);
      unset($entries[$key]["warehouse_id"]);
      unset($entries[$key]["curator_id"]);
    }

    $count_query = $this->local_db->query("SELECT count(1) as total_count" . $entries_sql);

    $total_count = $count_query->row_array();
    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "entries" => $entries,
        "count" => $total_count["total_count"],
        "limit" => $params["limit"]
      ]
    );

  }

  function properties($params){
    $cities_query = $this->local_db->query("SELECT
                                          `city_id` as id,
                                          `city_name` as name
                                      FROM `".local_table_name("cities")."`
                                      WHERE `deleted_at` IS NULL");

    $cities = [];
    if ($cities_query->num_rows()) {
      $cities = $cities_query->result_array();

    }

    $warehouses_query = $this->local_db->query("SELECT
                                          `depo_id` as id,
                                          `depo_name` as name
                                      FROM `".local_table_name("warehouses")."`
                                      WHERE `deleted_at` IS NULL");

    $warehouses = [];
    if ($warehouses_query->num_rows()) {
      $warehouses = $warehouses_query->result_array();

    }
    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "cities" => $cities,
        "warehouses" => $warehouses
      ]
    );

  }

}
