<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand_model extends CI_Model{
  public function __construct(){
    parent::__construct();
    $this->landing_db = $this->load->database('landing_db',true);
  }

  public function liveList($params)
  {
    $full_v = (string)$params["version"];

    if ($params["user"]) {
      $u = isAdmin($params["user"]);
      if (!$u["status"]) {
        $full_v = false;
      }else{
        $full_v = $full_v;
      }
    }

    $sql = "SELECT
                id,
                name,
                image
                ".($full_v ? ",description" : "")."
            FROM ".local_table_name("product_brands")."
            WHERE deleted_at IS NULL
            ORDER BY `order`,`id` DESC
            LIMIT ".$params["limit"]." OFFSET ".$params["offset"]."";

    $brand_query = $this->landing_db->query($sql);
    $brands = $brand_query->result_array();
    $new_list = [];

    foreach ($brands as $key => $brand) {
      $image = explode("|",$brand["image"]);
      $images = $image ? [
        "small" => isset($image[0]) ? $image[0] : "---",
        "large" => isset($image[1]) ? $image[1] : "---"
      ] : [];

      $sub_query = [
        "id"    => $brand["id"],
        "name"  => $brand["name"],
        "image" => $images,
      ];

      $new_list[] = $sub_query;
    }
    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Success"),
      $new_list
    );
  }

  public function brandDetails($params)
  {
    $exist_brand_query = $this->landing_db->select("id,name,image,description")
                                          ->from(local_table_name("product_brands"))
                                          ->where("id",$params["brand"])
                                          ->where("deleted_at",null)
                                          ->get();
    if (!$exist_brand_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NOT_FOUND,
        $this->lang->line("Brand not found")
      );
    }

    $brand = (array)$exist_brand_query->row();

    $image = explode("|",$brand["image"]);
    $images = $image ? [
      "small" => isset($image[0]) ? $image[0] : "---",
      "large" => isset($image[1]) ? $image[1] : "---"
    ] : [];

    $sub_query = [
      "id"          => $brand["id"],
      "name"        => $brand["name"],
      "image"       => $images,
      "description" => $brand["description"],
    ];

    $brand = $sub_query;
    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Success"),
      $brand
    );
  }
}
