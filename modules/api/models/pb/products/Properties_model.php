<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Properties_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  public function carBrands($params)
  {
    escapeAllKeys($params,null,$this->local_db);


    $limit_query = $params["p_limit"] ? " LIMIT {$params["p_limit"]}" : NULL;
    $offset_query = $params["p_offset"] ? " OFFSET {$params["p_offset"]}" : NULL;

    $car_brands_sql = "SELECT
                        carbrands.`name`
                      FROM ".local_table_name("cached_carbrands")." carbrands
                      WHERE carbrands.`deleted_at` IS NULL
                      AND carbrands.`name` IS NOT NULL
                      ORDER BY carbrands.`name`
                      $limit_query
                      $offset_query
                      ";

    $car_brands_query = $this->local_db->query($car_brands_sql);

    if (!$car_brands_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $car_brands = $car_brands_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => count($car_brands),
        "list" => $car_brands,
      ]
    );
  }
}
