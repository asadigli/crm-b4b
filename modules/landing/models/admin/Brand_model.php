<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->landing_db = $this->load->database('landing_db',true);
  }

  public function liveList($params){
    $user   = $params["user"];
    $limit  = (int)$params["limit"] ?: 15;
    $offset = (int)$params["offset"] ?: 0;
    $full_v = (string)$params["version"] === "full" ? true : false;

    if ($user) {
      $u = isAdmin($user);
      if (!$u["status"]) {
        $full_v = false;
      }else{
        $full_v = $full_v;
      }
    }
    // var_dump([$limit,$offset]);die;
    $sql = "SELECT
                id,
                name,
                image
                ".($full_v ? ",description" : "")."
            FROM ".local_table_name("product_brands")."
            WHERE deleted_at IS NULL
            ORDER BY `order`,`id` DESC
            LIMIT $limit OFFSET $offset";
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
        "id" => $brand["id"],
        "name" => $brand["name"],
        "image" => $images,
      ];

      if (isset($brand["description"])) {
        $sub_query["description"] = strip_tags($brand["description"]);
      }
      $new_list[] = $sub_query;
    }

    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Success"),
      $new_list
    );
  }

  public function addNew($params){
    $this->landing_db->insert(local_table_name("product_brands"), $params);

    return rest_response(
      Status_codes::HTTP_CREATED,
      $this->lang->line("New brand added"),
    );
  }

  public function delete($params){
    $brand  = $params["brand"];
    $date   = $params["date"];
    $user   = $params["user"];

    if (!$user || !$brand) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameters")
      );
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $exist_brand_query = $this->landing_db->select("id,name,image,description")
                                    ->from(local_table_name("product_brands"))
                                      ->where("id",$brand)
                                        ->where("deleted_at",null)
                                          ->get();
    if (!$exist_brand_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NOT_FOUND,
        $this->lang->line("Brand not found")
      );
    }

    $this->landing_db->where("id",$brand);
    $this->landing_db->update(
      local_table_name("product_brands"),
      ["deleted_at" => $date]
    );

    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Brand deleted")
    );
  }

  public function inDetail($params){
    $exist_brand_query = $this->landing_db->select("id,
                                                    name,
                                                    image,
                                                    description")
                                          ->from(local_table_name("product_brands"))
                                          ->where("id",$params["id"])
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
      "id" => $brand["id"],
      "name" => $brand["name"],
      "image" => $images,
      "description" => $brand["description"],
    ];

    $brand = $sub_query;
    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Success"),
      $brand,
    );
  }

  public function update($params){
    // fast_dump($params["image"]);
    $exist_brand_query = $this->landing_db->select("id")
                                          ->from(local_table_name("product_brands"))
                                          ->where("id",$params["id"])
                                          ->where("deleted_at",null)
                                          ->get();

    if (!$exist_brand_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NOT_FOUND,
        $this->lang->line("Brand not found")
      );
    }

    $this->landing_db->where("id",$params["id"]);
    $this->landing_db->update(local_table_name("product_brands"),$params);

    return rest_response(
      Status_codes::HTTP_ACCEPTED,
      $this->lang->line("Brand updated")
    );
  }
}
