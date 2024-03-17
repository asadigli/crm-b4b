<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Properties_model extends CI_Model{

  public function __construct() {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  public function brands($params) {
    escapeAllKeys($params,null,$this->local_db);

    $brands_sql = "SELECT
                    `id`,
                    `name`
            			FROM ".local_table_name("cached_product_brands")."
            			WHERE `deleted_at` IS NULL
                  AND `is_active` = '".STATUS_ACTIVE."'
                  AND `is_b2b_active` = '".STATUS_ACTIVE."'
                  AND `name` IS NOT NULL AND LTRIM(RTRIM(`name`)) != ''
            			ORDER BY `name` ASC
                  ";

    $brands_query = $this->local_db->query($brands_sql);
    if (!$brands_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $brands = $brands_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),[
        "count" => count($brands),
        "list" => $brands
      ]
    );
  }

  public function carBrands($params) {
    escapeAllKeys($params,null,$this->local_db);

    $check_carbrands_sql = "SELECT
                          carbrands.`id`,
                          carbrands.`name`
                        FROM `".local_table_name("cached_carbrands")."` carbrands
                        WHERE carbrands.`deleted_at` IS NULL
                        ";
    $check_carbrands_query = $this->local_db->query($check_carbrands_sql);

    if (!$check_carbrands_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $carbrands = $check_carbrands_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => count($carbrands),
        "list" => $carbrands,
      ]
    );
  }

}
