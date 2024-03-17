<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model{

  function __construct(){
    parent::__construct();
  }

  private $image_divider = "|PRODUCT_IMAGE_DIVIDER|";

  function updateItem($params) {
    $user = $params["user"];
    $product = $params["product"];
    $group_id = $params["group_id"];
    $brand_id = $params["brand_id"];
    $first_category_id = $params["first_category_id"];
    $second_category_id = $params["second_category_id"];
    $status = (string)$params["status"] === "0" ? "0" : "1";
    $name = $params["name"];
    $oem_code = $params["oem_code"];
    $price = $params["price"];
    $quantity = $params["quantity"];
    $brand_code = $params["brand_code"];
    $short_description = $params["short_description"];
    $description = $params["description"];
    $home_product = (string)$params["home_product"] === "1" ? "1" : "0";
    $images = $params["images"];
    $exist_images = $params["exist_images"];
    $date = $params["date"];

    if (!$user || !$name || !$product) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];


    $exist_query = $this->db->select("id")->from("sa_products")->where("id",$product)->where("deleted_at",null)->get();

    if (!$exist_query->num_rows()) {
      return rest_response(Status_codes::HTTP_NO_CONTENT,$this->lang->line("Product not found"));
    }

    $update_list = [
      "group_id" => $group_id,
      "brand_id" => $brand_id,
      "second_category_id" => $second_category_id,
      "first_category_id" => $first_category_id,
      "status" => $status,
      "custom_name" => $name,
      "custom_slug" => slugify($name)."-".rand(00000000,9999999999),
      "short_description" => $short_description,
      "description" => $description,
      "OEM" => $oem_code,
      "SALE_PRICE" => $price,
      "quantity" => $quantity,
      "CODE" => $brand_code,
      "home_product" => $home_product,
      "type" => "local"
    ];


    $this->db->where("id",$product);
    $this->db->update("sa_products",$update_list);


    $exist_images_query = "";
    foreach ($exist_images as $key => $item) {
      $item = $this->db->escape_str($item);
      $exist_images_query .= " AND `name` NOT LIKE '%$item%' ";
    }
    $removed_images_sql = "UPDATE `sa_product_images`
                           SET `deleted_at` = '$date'
                           WHERE `product_id` = $product
                           $exist_images_query";
    $this->db->query($removed_images_sql);

    if ($images) {
      $images_data = $images && is_array($images) ? updateCDN($images,"product/gallery") : null;

      if (!isset($images_data["id"])) {
        $insert_files_batch = [];
        foreach ($images_data as $key => $image) {
          $images = [];
          foreach ($image["folder"] as $item) {
            $images[] = $image["host"].$image["path"].$item."/".$image["file"];
          }
          $insert_files_batch[] = [
            "order" => $key,
            "product_id" => $product,
            "name" => implode($this->image_divider,$images)
          ];
        }
        $insert_files_batch ? $this->db->insert_batch("sa_product_images",$insert_files_batch) : "";
      }
    }


    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Product details changed"));
  }

  function delete($params){
    $user = $params["user"];
    $product = $params["product"];
    $date = $params["date"];

    if (!$user || !$product) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];


    $exist_query = $this->db->select("id")->from("sa_products")->where("id",$product)->where("deleted_at",null)->get();

    if (!$exist_query->num_rows()) {
      return rest_response(Status_codes::HTTP_NO_CONTENT,$this->lang->line("Product not found"));
    }

    $this->db->where("id",$product);
    $this->db->update("sa_products",["deleted_at" => $date]);

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Product deleted"));
  }

  function addNew($params) {
    $user = $params["user"];
    $group_id = $params["group_id"];
    $brand_id = $params["brand_id"];
    $first_category_id = $params["first_category_id"];
    $second_category_id = $params["second_category_id"];
    $status = (string)$params["status"] === "0" ? "0" : "1";
    $name = $params["name"];
    $oem_code = $params["oem_code"];
    $price = $params["price"];
    $quantity = $params["quantity"];
    $brand_code = $params["brand_code"];
    $short_description = $params["short_description"];
    $description = $params["description"];
    $home_product = (string)$params["home_product"] === "1" ? "1" : "0";
    $images = $params["images"];
    $exist_images = $params["exist_images"];

    if (!$user || !$name) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $insert_list = [
      "group_id" => $group_id,
      "brand_id" => $brand_id,
      "second_category_id" => $first_category_id,
      "first_category_id" => $second_category_id,
      "status" => $status,
      "home_product" => $home_product,
      "custom_name" => $name,
      "custom_slug" => slugify($name)."-".rand(00000000,9999999999),
      "short_description" => $short_description,
      "description" => $description,
      "OEM" => $oem_code,
      "SALE_PRICE" => $price,
      "quantity" => $quantity,
      "CODE" => $brand_code,
      "type" => "local"
    ];


    $this->db->insert("sa_products",$insert_list);
    $product_id = $this->db->insert_id();

    $images_data = $images && is_array($images) ? updateCDN($images,"product/gallery") : null;

    if (!isset($images_data["id"])) {
      $insert_files_batch = [];
      foreach ($images_data as $key => $image) {
        $images = [];
        foreach ($image["folder"] as $item) {
          $images[] = $image["host"].$image["path"].$item."/".$image["file"];
        }
        $insert_files_batch[] = [
          "order" => $key,
          "product_id" => $product_id,
          "name" => implode($this->image_divider,$images)
        ];
      }
      $insert_files_batch ? $this->db->insert_batch("sa_product_images",$insert_files_batch) : "";
    }

    return rest_response(Status_codes::HTTP_CREATED,$this->lang->line("Product added"));
  }

  function deleteProduct($params){

  }

  function addCategory($params){
    $group_id = (int)$params["group_id"];
    $type = in_array($params["type"],['brand','category','second_category']) ? $params["type"] : 'brand';
    $name = trim($params["name"]);
    $status = $params["status"];

    if (!$group_id) {
      $msg = "Group cannot be empty";
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    if (!$name) {
      $msg = "Name cannot be empty";
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $exist_cat = $this->db->select("id")->from("sa_category")
                            ->where("name",$name)->where("group_id",$group_id)
                              ->where("type",$type)->where('deleted_at',null)->get();

    if ($exist_cat->num_rows()) {
      $msg = "Category already created";
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $insert_list = [
      "group_id" => $group_id,
      "type" => $type,
      "name" => $name,
      "slug" => slugify($name).'-'.rand(100000,9999999),
      "status" => $status,
    ];

    $this->db->insert("sa_category",$insert_list);
    $last_id = $this->db->insert_id();

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),["id" => $last_id,"type" => $type]);
  }

  function getList($params){
    $exist_cat = $this->db->select("id,group_id,type,name,slug")
                            ->from("sa_category")
                              ->where("status","1")
                                ->where("deleted_at",null)
                                  ->order_by("name","asc")
                                    ->get();
    $all_categories = $exist_cat->result_array();

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),$all_categories);
  }

  function updateCategory($params){
    $id = $params["id"];
    $name = $params["name"];
    if (!$id) {
      $msg = "Category name and id cannot be empty";
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $this->db->where("id",$id);
    $this->db->update("sa_category",['name' => $name]);

    return rest_response(Status_codes::HTTP_OK,"Category updated",[]);
  }

  function deleteCategory($params){
    $user = $params["user"];
    $category = $params["category"];
    $date = $params["date"];

    if (!$user || !$category) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $exist_query = $this->db->select("id")->from("sa_category")->where("id",$category)->where("deleted_at",null)->get();

    if (!$exist_query->num_rows()) {
      return rest_response(Status_codes::HTTP_NO_CONTENT,$this->lang->line("Category not found"));
    }

    $this->db->where("id",$category);
    $this->db->update("sa_category",["deleted_at" => $date]);

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Category deleted"),[]);
  }

  // function updateProduct($params){
  //   $product = $params["product"];
  //   $brand = $params["brand"];
  //   $subcat = $params["subcat"];
  //   $category = $params["category"];
  //   $description = $params["description"];
  //   $name = $params["name"];
  //   $status = $params["status"];
  //   $images = $params["images"];
  //
  //   if (!$product || !$name) {
  //     $msg = (!$product ? "Product" : "Name")." cannot be empty";
  //     return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
  //   }
  //
  //   if ($images && is_array($images)) {
  //     $img_insert = [];
  //     foreach ($images as $key => $image) {
  //       $img_insert[] = [
  //         'product_id' => $product,
  //         'prod_type' => 'not_from_system',
  //         'name' => $image,
  //       ];
  //     }
  //     count($img_insert) ? $this->db->insert_batch("sa_product_images",$img_insert) : "";
  //   }
  //
  //   $update_list = [
  //     "brand_id" => $brand,
  //     "category_id" => $category,
  //     "subcat_id" => $subcat,
  //     "name" => $name,
  //     "description" => $description,
  //     "status" => $status
  //   ];
  //   $this->db->where("id",$product);
  //   $this->db->update("sa_manual_products",$update_list);
  //   return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),[]);
  // }

  function deleteImage($params){
    if (!$params['image']) {
      $msg = "Image cannot be empty";
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $this->db->where("id",$params['image']);
    $this->db->update("sa_product_images",['deleted_at' => $params['date']]);
    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),[]);
  }

  function get1CBrands($params){
    $brands_query = $this->db->query("SELECT DISTINCT SPECODE as brand FROM `sa_products` WHERE SPECODE IS NOT NULL ORDER BY TRIM(SPECODE) ASC");
    $parents_query = $this->db->query("SELECT DISTINCT SPECODE2 as parent FROM `sa_products` WHERE SPECODE2 IS NOT NULL ORDER BY TRIM(SPECODE2) ASC");
    $brands = $brands_query->result_array();
    $parents = $parents_query->result_array();
    $res = [
      "brands" => array_map(function($i) {return trim($i["brand"]);}, $brands),
      "parents" => array_map(function($i) {return trim($i["parent"]);}, $parents),
    ];
    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),$res);
  }

  function updateHomeList($params){
    $product = $params["product"];
    $user = $params["user"];
    $status = (string)$params["status"] === "1" ? "1" : "0";


    if (!$user || !$product) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $exist_product = $this->db->select("id,home_product")->from("sa_products")->where("id",$product)->where("deleted_at",null)->get();

    if(!$exist_product->num_rows()){
      return rest_response(
        Status_codes::HTTP_NOT_FOUND,
        $this->lang->line("Product not found")
      );
    }

    $product_row = $exist_product->row();

    if ($status !== $product_row->home_product) {
      $this->db->where("id",$product);
      $this->db->update("sa_products",["home_product" => (string)$status]);
    }


    return rest_response(Status_codes::HTTP_OK,!(int)$status ? $this->lang->line("Product removed from home list") : $this->lang->line("Product added to home list"));
  }

  function changeStatus($params){
    $product = $params["product"];
    $user = $params["user"];
    $status = (string)$params["status"] === "1" ? "1" : "0";


    if (!$user || !$product) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $exist_product = $this->db->select("id,status")->from("sa_products")->where("id",$product)->where("deleted_at",null)->get();

    if(!$exist_product->num_rows()){
      return rest_response(
        Status_codes::HTTP_NOT_FOUND,
        $this->lang->line("Product not found")
      );
    }

    $product_row = $exist_product->row();

    if ($status !== $product_row->status) {
      $this->db->where("id",$product);
      $this->db->update("sa_products",["status" => (string)$status]);
    }

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Product status updated"));
  }


}
