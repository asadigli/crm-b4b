<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mainproducts_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  public function index($params){
    $brand_codes_sql = "SELECT
                            `mapr_brand_code`
                        FROM `".local_table_name("b4b_dashboard_products")."`
                        WHERE `mapr_delete` = '".STATUS_NO."'
                        ORDER BY `mapr_position` ASC";

    $brand_codes_query = $this->local_db->query($brand_codes_sql);

    if (!$brand_codes_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Product not found")
      );
    }
    $brand_codes_arr = $brand_codes_query->result_array();


    $brand_codes = array_map(function($i) { return $i["mapr_brand_code"]; },$brand_codes_arr);

    $query = $this->local_db->query("SELECT
                                          prod.`name`,
                                          prod.`brand_name`,
                                          prod.`brand_code`,
                                          prod.`cleaned_brand_code`,
                                          prod.`OEM` as oem,
                                          prod.`cleaned_OEM` as cleaned_oem,
                                          prod.`sale_price`,
                                          prod.`currency`,
                                          dash.`mapr_photo_url` as photo
                                    FROM `".local_table_name("cached_products")."` prod
                                    LEFT JOIN `".local_table_name("b4b_dashboard_products")."` dash ON dash.`mapr_brand_code` = prod.`brand_code`
                                    WHERE prod.`deleted_at` IS NULL
                                    AND prod.`brand_code` IN ('".implode("','",$brand_codes)."')
                                    ");

  if (!$query->num_rows()) {
    return rest_response(
      Status_codes::HTTP_NO_CONTENT,
      lang("Product not found")
    );
  }

  $products = $query->result_array();

  foreach ($products as $key => $product) {
    $products[$key]["photo"] = "http://test.loc/main-products/" . $product["photo"];
  }

  return rest_response(
    Status_codes::HTTP_OK,
    lang("Success"),
    $products
  );
  }
}
