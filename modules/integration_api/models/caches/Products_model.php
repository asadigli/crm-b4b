<?php
defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('memory_limit','1200006M');
ini_set('max_execution_time', 30); //300 seconds = 5 minutes

class Products_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->remote_db  = $this->load->database("remote_db",true);
    $this->local_db   = $this->load->database("local_db",true);
  }

  function refreshProducts($params) {
    $cache_types = $this->config->item("cache_types");

    $date_sql_query = "";
    $product_price_query = "";

    $currency = get_currency();
    $currency_by_id_list = [];
    $currency_by_name_list = [];

    foreach ($currency as $key => $item) {
      $currency_by_id_list[$item["remote_id"]] = $item;
      $currency_by_name_list[$item["main_name"]] = $item;
    }

    if($params["validate_limit_hour"]){
      $params["start_date"]     = $params["limit_hour"] ? date("Y-m-d",strtotime("-".($params["limit_hour"] * 60)." minute",time())) : $params["start_date"];
      $params["end_date"]       = $params["limit_hour"] ? date("Y-m-d",strtotime("+1 day",strtotime(date("Y-m-d H:i:s")))) : date("Y-m-d",strtotime("+1 day",strtotime($params["end_date"])));
      if((!$params["limit_hour"] || !is_numeric($params["limit_hour"])) && (!$params["start_date"] || !$params["end_date"])) {
        return rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("Date param is mandatory")
        );
      }

      $product_price_query            = " OR (product.[LOGICALREF] IN (SELECT
                                                  product_prices.CARDREF
                                                FROM
                                                  ".remote_table_name("product_prices")." product_prices
                                                WHERE (product_prices.[CAPIBLOCK_EXTCREATEDDATE] >= '{$params["start_date"]}'
                                                                                      AND product_prices.[CAPIBLOCK_EXTCREATEDDATE] <= '{$params["end_date"]}') OR (product_prices.[CAPIBLOCK_EXTMODIFIEDDATE] >= '{$params["start_date"]}'
                                                                                                      AND product_prices.[CAPIBLOCK_EXTMODIFIEDDATE] <= '{$params["end_date"]}')) )";
      $date_sql_query                 = " AND (([CAPIBLOCK_EXTCREATEDDATE] >= '{$params["start_date"]}' AND [CAPIBLOCK_EXTCREATEDDATE] <= '{$params["end_date"]}') OR
                                                     ([CAPIBLOCK_EXTMODIFIEDDATE] >= '{$params["start_date"]}' AND [CAPIBLOCK_EXTMODIFIEDDATE] <= '{$params["end_date"]}') OR
                                                      (stock_baku.[LASTTRDATE] >= '".date("Y-m-d 00:00:00",strtotime("-1 day",strtotime($params["start_date"])))."' AND stock_baku.[LASTTRDATE] <= '{$params["end_date"]}') OR
                                                        (stock_baku_2.[LASTTRDATE] >= '".date("Y-m-d 00:00:00",strtotime("-1 day",strtotime($params["start_date"])))."' AND stock_baku_2.[LASTTRDATE] <= '{$params["end_date"]}') OR
                                                        (stock_ganja.[LASTTRDATE] >= '".date("Y-m-d 00:00:00",strtotime("-1 day",strtotime($params["start_date"])))."' AND stock_ganja.[LASTTRDATE] <= '{$params["end_date"]}')
                                                          $product_price_query)  ";
    }



    $ava_products_sql = "SELECT
                      			product.[LOGICALREF],
                      			product.[CODE],
                      			product.[NAME],
                      			product.[PRODUCERCODE],
                      			product.[STATENAME],
                      			product.[SPECODE],
                      			product.[SPECODE2],
                      			product.[SPECODE3],
                      			product.[SPECODE4],
                      			product.[SPECODE5],
                            product.[ACTIVE],
                            buying_price.[amount] AS BUYING_PRICE,
                            prices.[sale_price] AS SALE_PRICE,
                            prices.[currency_id] AS CURRENCY_ID,
                            stock_baku.[ONHAND] AS STOK_BAKU,
                      			stock_baku_2.[ONHAND] AS STOK_BAKU_2,
                      			stock_ganja.[ONHAND] AS STOK_GANCA,
                      			(SELECT
                      					SUM(total)AS total_sales
                      				FROM
                      					(
                      						SELECT
                      							SUM(AMOUNT) AS total
                      						FROM
                      							".remote_table_name("invoices_details")." invoices
                      						WHERE
                      							invoices.[STOCKREF] = product.[LOGICALREF]
                      						AND invoices.[TRCODE] IN(8, 12, 51)
                      						AND DATE_ >= Dateadd(
                      							MONTH,
                      							Datediff(
                      								MONTH,
                      								0,
                      								DATEADD(m, - 12, CURRENT_TIMESTAMP)
                      							),
                      							0
                      						)
                      						GROUP BY
                      							DATEPART(YEAR, DATE_),
                      							DATEPART(MONTH, DATE_)
                      					)AS total_sales
                      			) AS SALE_AMOUNT
                      		FROM
                      			".remote_table_name("products")." product
                          LEFT JOIN ".remote_table_name("product_quantities_based")." stock_baku  ON stock_baku.STOCKREF = product.LOGICALREF AND stock_baku.INVENNO = '0'
                          LEFT JOIN ".remote_table_name("product_quantities_based")." stock_baku_2  ON stock_baku_2.STOCKREF = product.LOGICALREF AND stock_baku_2.INVENNO = '1'
                          LEFT JOIN ".remote_table_name("product_quantities_based")." stock_ganja ON stock_ganja.STOCKREF = product.LOGICALREF AND stock_ganja.INVENNO = '5'
                          OUTER APPLY (
                            SELECT
                              TOP 1 product_prices.[PRICE] as sale_price,
                                    product_prices.[CURRENCY] as currency_id
                            FROM
                              ".remote_table_name("product_prices")." product_prices
                            WHERE
                              product_prices.CARDREF = product.LOGICALREF
                            AND product_prices.PTYPE = 2
                            ORDER BY
                              product_prices.LOGICALREF DESC) AS prices
                          OUTER APPLY (
                            SELECT
                              TOP 1 product_prices.PRICE as amount
                            FROM
                              ".remote_table_name("product_prices")." product_prices
                            WHERE
                              product_prices.CARDREF = product.LOGICALREF
                            AND product_prices.PTYPE = 1
                            ORDER BY
                              product_prices.LOGICALREF DESC) AS buying_price
                      		WHERE 1 > 0
                          $date_sql_query";
                          // var_dump($ava_products_sql);die;

                          // OUTER APPLY (
                          //   SELECT
                          //     SUM(CASE WHEN product_quantities.INVENNO = 0 THEN product_quantities.ONHAND ELSE 0 END) AS baku,
                          //     SUM(CASE WHEN product_quantities.INVENNO = 5 THEN product_quantities.ONHAND ELSE 0 END) AS ganca
                          //   FROM
                          //     ".remote_table_name("product_quantities")." product_quantities
                          //   WHERE product_quantities.STOCKREF = product.LOGICALREF
                          // ) as stock

    $ava_products_query = $this->remote_db->query($ava_products_sql);

    $ava_products = $ava_products_query->result_array();


    $ava_products_list = [];
    $ava_product_codes = [];
    $product_codes = [];

    $ava_currencies = $this->config->item("ava_currencies");

    foreach ($ava_products as $key => $item) {
      $spec_code3 = trim(preg_replace("/^[a-zA-Z0-9 .\-]+$/i", "", $item["SPECODE3"]));//cleaned_text($item["SPECODE3"]);
      $oem = $spec_code3 ?: cleaned_text($item["NAME"]);
      $product_token = md5($spec_code3 . ID_SPRTR . $item["LOGICALREF"] . ID_SPRTR . special_codes("products.resources.ava"));

      $ava_products_list[] = [
        "remote_id" => $item["LOGICALREF"],
        "stock_baku" => $item["STOK_BAKU"],
        "stock_baku_2" => $item["STOK_BAKU_2"],
        "stock_ganja" => $item["STOK_GANCA"],
        "brand_name" => trim(str_replace(["'", "\"",";","\\"], " ", $item["SPECODE"])),
        "brand_code"  => trim(str_replace(["'", "\"",";","\\"], " ", $item["CODE"])),
        "cleaned_brand_code" => cleaned_text($item["CODE"]),
        "OEM" => $oem,
        "remote_is_active" => (string)$item["ACTIVE"] === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
        "cleaned_OEM" => $oem,
        "sale_amount" => $item["SALE_AMOUNT"],
        "name" => trim(str_replace(["'", "\"",";","\\"], " ", $item["STATENAME"])),
        "model" => trim(str_replace(["'", "\"",";","\\"], " ", $item["PRODUCERCODE"])),
        "buying_price"  => $item["BUYING_PRICE"],
        "sale_price"  => $item["SALE_PRICE"],
        "description" => trim(str_replace(["'", "\"",";","\\"], " ", $item["SPECODE2"])),
        "remote_currency_id" => $item["CURRENCY_ID"],
        "currency_id" => isset($currency_by_id_list[$item["CURRENCY_ID"]]) ? $currency_by_id_list[$item["CURRENCY_ID"]]["id"] : (isset($currency_by_name_list[CURRENCY_EUR]) ? $currency_by_name_list[CURRENCY_EUR]["id"] : NULL),
        "currency" => isset($currency_by_id_list[$item["CURRENCY_ID"]]) ? $currency_by_id_list[$item["CURRENCY_ID"]]["main_name"] : (isset($currency_by_name_list[CURRENCY_EUR]) ? $currency_by_name_list[CURRENCY_EUR]["main_name"] : NULL),
        "resource" => special_codes("products.resources.ava"),
        "token" => $product_token,
        "deleted_at" => NULL,
      ];

      foreach ($item as $sub_key => $sub_item) {
        if(in_array($sub_key, ["CODE", "NAME", "SPECODE3", "SPECODE4", "SPECODE5"]) && cleaned_text($sub_item)) {
          $product_codes[] = [
            "token" => md5($item["LOGICALREF"] . "-" . $sub_key),
            "product_token" => $product_token,
            "remote_product_id" => $item["LOGICALREF"],
            "code" => trim(str_replace(["'", "\"",";","\\"], " ", $sub_item)),
            "cleaned_code" => cleaned_text($sub_item),
            "source" => $sub_key
          ];
        }
      }
    }

    if(!$ava_products_list){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No products data")
      );
    }

    $cached_info_data = $ava_products_list;
    if ($cached_info_data) {
      if(!$params["validate_limit_hour"]) {
        $this->local_db->query("UPDATE `".local_table_name($cache_types[$params["type"]]["table"])."`
                                  SET `deleted_at` = '".$params["deleted_at"]."'
                                  WHERE `deleted_at` IS NULL
                                  AND `resource` = '".special_codes("products.resources.ava")."'
                                ");
      }

      $cached_info_insert_sql = insert_dublicate_key(local_table_name($cache_types[$params["type"]]["table"]), $cached_info_data);
      // $this->local_db->query("UPDATE `".local_table_name($cache_types[$params["type"]]["table"])."`
      //             SET `deleted_at` = '{$params["deleted_at"]}'
      //             WHERE `deleted_at` IS NULL
      //             ");
      $this->local_db->query($cached_info_insert_sql);
      if($product_codes) {
        $product_codes_insert_sql = insert_dublicate_key(local_table_name("cached_product_codes"), $product_codes);
        $this->local_db->query($product_codes_insert_sql);
      }
    }




    $this->local_db->insert(local_table_name("cached_history"), [
      "type" => $params["type"],
      "request_type" => $params["request_type"],
      "creator_ip" => $params["creator_ip"],
      "creator_id" => $params["creator_id"],
      "result_count" => count($cached_info_data),
      "operation_date" => $params["date"],
      "interval_start_date" => $params["start_date"] ? $params["start_date"] : (date("Y-m-d H:i:s", strtotime('-'.$params["limit_hour"].' minutes'))),
      "interval_end_date" => $params["end_date"] ?: now()
    ]);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      lang("Cache refreshed"),
      [
        "count" => count($ava_products),
        "date"  => $params["date"]
      ]
    );
  }
}
