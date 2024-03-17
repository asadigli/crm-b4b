<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificate_model extends CI_Model{

  function __construct(){
    parent::__construct();
  }

  private $image_divider = "|IMAGE_DIVIDER|";

  function addNew($params){
    $user = $params["user"];
    $order = $params["order"] ?: 0;
    $name = $params["name"];
    $status = (string)$params["status"] === "0" ? "0" : "1";
    $description = $params["description"];
    $files = $params["files"];

    if (!$user || !$name) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $insert_list = [
      "name" => $name,
      "order" => $order,
      "description" => $description,
      "status" => $status,
    ];


    $files = is_array($files) ? $files : explode(",",$files);
    $files_data = updateCDN($files,$path = "certificates");

    if (!isset($files_data["id"])) {
      $this->db->insert("sa_certificates",$insert_list);
      $certificate_id = $this->db->insert_id();
      $insert_files_batch = [];
      foreach ($files_data as $key => $file) {
        $images = [];
        foreach ($file["folder"] as $item) {
          $images[] = $file["host"].$file["path"].$item."/".$file["file"];
        }
        $insert_files_batch[] = [
          "certificate_id" => $certificate_id,
          "name" => implode($this->image_divider,$images)
        ];
      }
      $insert_files_batch ? $this->db->insert_batch("sa_certificate_files",$insert_files_batch) : "";
    }

    return rest_response(Status_codes::HTTP_CREATED,$this->lang->line("New certificate added"),$files_data);
  }


  function getList($params){
    $sub_image_divider = "|SUB_IMAGE_DIVIDER|";
    $limit = (int)$params["limit"] ?: 8;
    $offset = (int)$params["offset"] ?: 0;

    $sql = "SELECT
              cc.`id`,cc.`name`,cc.`order`,cc.`description`,
              (SELECT GROUP_CONCAT(`name` separator '$sub_image_divider')
               FROM `sa_certificate_files` scc
               WHERE scc.`certificate_id` = cc.`id`
               AND scc.`deleted_at` IS NULL) as files
            FROM `sa_certificates` cc
            WHERE cc.`deleted_at` IS NULL
            AND cc.`status` = '1'
            ORDER BY cc.`order`,cc.`created_at` DESC
            LIMIT $limit
            OFFSET $offset";
    $certificate_query = $this->db->query($sql);
    $certificates = $certificate_query->result_array();

    $new_list = [];
    foreach ($certificates as $key => $item) {
      $files = $item["files"] ? array_map(function($i){
        return isset(explode($this->image_divider,$i)[0]) ? ["small" => explode($this->image_divider,$i)[0], "large" => explode($this->image_divider,$i)[1]] : [];
      },explode($sub_image_divider,$item["files"])) : [];
      $new_list[] = [
        "id" => $item["id"],
        "name" => $item["name"],
        "order" => $item["order"],
        "description" => $item["description"],
        "files" => $files
      ];
    }

    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Success"),
      $new_list
    );
  }

  function delete($params){
    $user = $params["user"];
    $certificate = $params["certificate"];
    $date = $params["date"];

    if (!$user || !$certificate) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $exist_cert = $this->db->select("id,updated_at")
                            ->from("sa_certificates")
                              ->where("id",$certificate)
                                ->where("deleted_at",null)
                                  ->get();

    if (!$exist_cert->num_rows()) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Certificate not found")
      );
    }

    $cert_row = $exist_cert->row();
    $cert = $cert_row->id;
    $this->db->where("id",$cert);
    $this->db->update("sa_certificates",["deleted_at" => $date]);

    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Certificate deleted successfully")
    );
  }

}
