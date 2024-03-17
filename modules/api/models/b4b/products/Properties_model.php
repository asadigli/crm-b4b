<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Properties_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  public function brands($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $brands_sql = "SELECT
                    `id`,
                    `name`
            			FROM ".local_table_name("cached_product_brands")."
            			WHERE `deleted_at` IS NULL
                  AND `is_active` = '".STATUS_ACTIVE."'
                  AND `is_b2b_active` = '".STATUS_ACTIVE."'
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

  public function carBrands($params)
  {
    escapeAllKeys($params,null,$this->local_db);

    $car_brands_sql = "SELECT
                        carbrands.`id`,
                        carbrands.`name`
                      FROM ".local_table_name("cached_carbrands")." carbrands
                      WHERE carbrands.`deleted_at` IS NULL
                      ORDER BY carbrands.`name`";

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

  public function banners($params)
  {
    escapeAllKeys($params,null,$this->local_db);
    $yesterday = date('Y-m-d H:i:s', strtotime($params["date"] . ' -1 day'));

    $banners_sql = "SELECT
								banner.`banner_file_url`,
                banner.`banner_target`,
                banner.`banner_link`,
                banner.`banner_name`,
                banner.`banner_excel`,
                type.`baty_name`
							FROM ".local_table_name("banners")." banner
							LEFT JOIN ".local_table_name("banner_types")." type ON type.`baty_id` = banner.`banner_type_id`
							WHERE banner.`banner_delete` = '".STATUS_NO."'
              AND banner.`banner_position_id`	= '".STATUS_ACTIVE."'
              AND banner.`deleted_at` IS NULL
              AND banner.`banner_end_date` >= '{$yesterday}'
              AND banner.`banner_start_date` <= '{$params["date"]}'
							ORDER BY banner.`banner_start_date` DESC";

    $banners_query = $this->local_db->query($banners_sql);

    if (!$banners_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $banners = $banners_query->result_array();
    $banner_list = [];
    $banner_link = $this->config->item("cdn_banners");
    foreach ($banners as $key => $item) {
      $banner_param_link = explode("?", $item["banner_link"]);
      $banner_param_links = isset($banner_param_link[1]) ? $banner_param_link[1] : "";

      $banner_list[] =[
        "img_path" => $banner_link . $item["banner_file_url"],
        "name" => $item["banner_name"],
        "top_name" => $item["baty_name"],
        "params" => $banner_param_links,
        "target" => $item["banner_target"] === STATUS_YES,
        "excel" => $item["banner_excel"] === STATUS_YES,
      ];
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => count($banner_list),
        "list" => $banner_list,
      ]
    );
  }
}
