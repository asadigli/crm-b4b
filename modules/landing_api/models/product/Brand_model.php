<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand_model extends CI_Model{

  function __construct(){
    parent::__construct();
  }

  private $image_divider = "|IMAGE_DIVIDER|";

  function getAll($params){
    $user = $params["user"];
    $limit = (int)$params["limit"] ?: 15;
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
    $sql = "SELECT id,name,image
                ".($full_v ? ",description" : "")."
            FROM sa_product_brands
            WHERE deleted_at IS NULL
            ORDER BY `order`,`id` DESC
            LIMIT $limit OFFSET $offset";
    $brand_query = $this->db->query($sql);
    $brands = $brand_query->result_array();
    $new_list = [];

    foreach ($brands as $key => $brand) {
      $imgs = $brand["image"] ? explode($this->image_divider,$brand["image"]) : [];
      $images = $imgs ? [
        "small" => isset($imgs[0]) ? $imgs[0] : "---",
        "large" => isset($imgs[1]) ? $imgs[1] : "---"
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
    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),$new_list);
  }

  function addNew($params){
    $order = $params["order"];
    $user = $params["user"];
    $name = strip_tags($params["name"]);
    $image = $params["image"];
    $description = $params["description"];
    $status = $params["status"];

    if (!$user || !$name) {
      return rest_response(Status_codes::HTTP_CONFLICT,$this->lang->line("Missed parameters"),[]);
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    unset($params["image"]);
    unset($params["user"]);

    // $image = is_array($image) ? $image : explode(",",$image);
    $image_data = updateCDN([$image],$path = "product/brands/gallery",[
      "small,540",
      "large,200",
    ]);
    // return $image_data;
    if (!isset($image_data["id"])) {
      $images = [];
      $image = $image_data[0];
      foreach ($image["folder"] as $item) {
        $images[] = $image["host"].$image["path"].$item."/".$image["file"];
      }
      $params = $params + ["user_id" => $u["body"]["id"],"image" => implode($this->image_divider,$images)];
      $this->db->insert("sa_product_brands",$params);
      $insert_id = $this->db->insert_id();
    }

    return rest_response(
      Status_codes::HTTP_CREATED,
      $this->lang->line("New brand added"),
      [
        "id" => $insert_id
      ]
    );
  }

  function update($params){
    $user = $params["user"];
    $brand = $params["brand"];
    $order = $params["order"];
    $name = strip_tags($params["name"]);
    $image = $params["image"];
    $description = $params["description"];
    $status = $params["status"];

    if (!$user || !$name || !$brand) {
      return rest_response(Status_codes::HTTP_CONFLICT,$this->lang->line("Missed parameters"),[]);
    }

    $exist_brand_query = $this->db->select("id")
                                    ->from("sa_product_brands")
                                      ->where("id",$brand)
                                        ->where("deleted_at",null)
                                          ->get();
    if (!$exist_brand_query->num_rows()) {
      return rest_response(Status_codes::HTTP_NOT_FOUND,$this->lang->line("Brand not found"),[]);
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    unset($params["user"]);
    unset($params["brand"]);

    if ($image) {
      $image_data = updateCDN([$image],$path = "product/brands/gallery",[
        "small,540",
        "large,200",
      ]);
      // return $image_data;
      if (!isset($image_data["id"])) {
        $images = [];
        $image = $image_data[0];
        foreach ($image["folder"] as $item) {
          $images[] = $image["host"].$image["path"].$item."/".$image["file"];
        }
        $new_image = ["image" => implode($this->image_divider,$images)];
      }
      if ($new_image) {
        $params = array_merge($params,$new_image);
      }
    }

    $this->db->where("id",$brand);
    $this->db->update("sa_product_brands",$params);

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Brand updated"),[]);
  }

  function details($params){
    $brand = $params["brand"];
    $exist_brand_query = $this->db->select("id,name,image,description")
                                    ->from("sa_product_brands")
                                      ->where("id",$brand)
                                        ->where("deleted_at",null)
                                          ->get();
    if (!$exist_brand_query->num_rows()) {
      return rest_response(Status_codes::HTTP_NOT_FOUND,$this->lang->line("Brand not found"),[]);
    }
    $brand = (array)$exist_brand_query->row();

    $imgs = $brand["image"] ? explode($this->image_divider,$brand["image"]) : [];
    $image = $imgs ? [
      "small" => isset($imgs[0]) ? $imgs[0] : "---",
      "large" => isset($imgs[1]) ? $imgs[1] : "---"
    ] : [];
    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),array_merge($brand,["image" => $image]));
  }

  function delete($params){
    $brand = $params["brand"];
    $date = $params["date"];
    $user = $params["user"];

    if (!$user || !$brand) {
      return rest_response(Status_codes::HTTP_CONFLICT,$this->lang->line("Missed parameters"),[]);
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $exist_brand_query = $this->db->select("id,name,image,description")
                                    ->from("sa_product_brands")
                                      ->where("id",$brand)
                                        ->where("deleted_at",null)
                                          ->get();
    if (!$exist_brand_query->num_rows()) {
      return rest_response(Status_codes::HTTP_NOT_FOUND,$this->lang->line("Brand not found"),[]);
    }

    $this->db->where("id",$brand);
    $this->db->update("sa_product_brands",["deleted_at" => $date]);

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Brand deleted"),[]);
  }

  function ordering($params){
    $user = $params['user'];
    $list = $params['list'];

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $exist_brands_ids = [];
    $sub_query = "";
    foreach ($list as $key => $item) {
      $brand = (int)$item["brand"];
      $order = (int)$item["order"];
      $exist_brands_ids[] = $brand;
      $sub_query .= " WHEN `id` = {$brand} THEN {$order} ";
    }

    if (!count($exist_brands_ids)) {
      return rest_response(
        Status_codes::HTTP_NOT_FOUND,
        $this->lang->line("Brand not found"),
        []
      );
    }

    $sql_query = "UPDATE `sa_product_brands`
                  SET `order` = (CASE $sub_query END)
                  WHERE `id` in (".implode(",",$exist_brands_ids).")";

    $this->db->query($sql_query);

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),[]);
  }

}
