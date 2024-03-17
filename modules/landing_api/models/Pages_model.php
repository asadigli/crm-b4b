<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages_model extends CI_Model{

  function __construct(){
    parent::__construct();
  }

  function getList($params){
    $user = $params["user"];
    $version = $params["version"];
    $type = $params["type"];
    $lang = $params["lang"];
    $can_seen_full = false;

    if ($user && $version === "full") {
      $u = isAdmin($user);
      $can_seen_full = $u["status"];
    }

    if ($type === "about") {
      $data_query = $this->db->select($can_seen_full ? "id,status,title,details" : "id,title,details")->from("sa_static_pages")
                        ->where("type","about")
                          ->where("deleted_at",null)
                            ->where(!$can_seen_full ? " status = '1' " : " id > 0 ",NULL,FALSE)
                              ->where("lang",$lang)
                                ->order_by("id","desc")
                                  ->limit(1)->get();
      if (!$data_query->num_rows()) {
        return rest_response(Status_codes::HTTP_NOT_FOUND,$this->lang->line("Information not found"));
      }
      $data = $data_query->row();
    }else{
      $data_query = $this->db->select($can_seen_full ? "id,status,title,details" : "id,title,details")->from("sa_static_pages")
                        ->where("type","faq")
                          ->where("deleted_at",null)
                            ->where(!$can_seen_full ? " status = '1' " : " id > 0 ",NULL,FALSE)
                              ->order_by("order","desc")
                                ->where("lang",$lang)
                                  ->get();
      if (!$data_query->num_rows()) {
        return rest_response(Status_codes::HTTP_NOT_FOUND,$this->lang->line("Information not found"));
      }
      $data = $data_query->result_array();
    }

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),$data);
  }

  function addNew($params){
    $user = $params["user"];
    $type = $params["type"];
    $order = (int)$params["order"] ? (int)$params["order"] : 0;
    $title = strip_tags($params["title"]);
    $details = $params["details"];
    $lang = $params["lang"];
    $status = (string)$params["status"] === "0" ? "0" : "1";

    if (!$type || !$user || !$title) {
      return rest_response(Status_codes::HTTP_CONFLICT,$this->lang->line("Missed parameters"));
    }

    if (!in_array($params["type"],["about","faq"])) {
      return rest_response(Status_codes::HTTP_CONFLICT,$this->lang->line("Unknown type"));
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $insert_list = [
      "type" => $type,
      "order" => $order,
      "title" => $title,
      "details" => $details,
      "status" => $status,
      "lang" => $lang,
    ];

    $this->db->insert("sa_static_pages",$insert_list);

    return rest_response(
      Status_codes::HTTP_CREATED,
      $this->lang->line("New static information added")
    );
  }

  function update($params){
    $user = $params["user"];
    $page = $params["page"];
    $order = (int)$params["order"] ? (int)$params["order"] : 0;
    $title = strip_tags($params["title"]);
    $details = $params["details"];
    $status = (string)$params["status"] === "0" ? "0" : "1";

    if (!$user || !$title || !$page) {
      return rest_response(Status_codes::HTTP_CONFLICT,$this->lang->line("Missed parameters"));
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $page_exist = $this->db->select("id")->from("sa_static_pages")->where("id",$page)->where("deleted_at",null)->get();

    if (!$page_exist->num_rows()) {
      return rest_response(Status_codes::HTTP_NOT_FOUND,$this->lang->line("Information not found"));
    }

    $update_list = [
      "order" => $order,
      "title" => $title,
      "details" => $details,
      "status" => $status
    ];

    $this->db->where("id",$page);
    $this->db->update("sa_static_pages",$update_list);

    return rest_response(
      Status_codes::HTTP_CREATED,
      $this->lang->line("Infomration updated")
    );
  }

  function delete($params){
    $user = $params["user"];
    $page = $params["page"];
    $date = $params["date"];

    if (!$user || !$page) {
      return rest_response(Status_codes::HTTP_CONFLICT,$this->lang->line("Missed parameters"));
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $page_exist = $this->db->select("id")->from("sa_static_pages")->where("id",$page)->where("deleted_at",null)->get();

    if (!$page_exist->num_rows()) {
      return rest_response(Status_codes::HTTP_NOT_FOUND,$this->lang->line("Information not found"));
    }

    $update_list = [
      "deleted_at" => $date
    ];

    $this->db->where("id",$page);
    $this->db->update("sa_static_pages",$update_list);

    return rest_response(
      Status_codes::HTTP_CREATED,
      $this->lang->line("Infomration updated")
    );
  }

}
