<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News_model extends CI_Model{

  function __construct(){
    parent::__construct();
  }

  private $local_seperator = "|NEWS_LIST_DIVIDER|";
  private $local_sub_seperator = "|NEWS_SUB_LIST_DIVIDER|";

  function getList($params){
    $keyword = $params["keyword"] ? $this->db->escape_str($params["keyword"]) : null;
    $type = $params["type"] ? $this->db->escape_str($params["type"]) : null;
    $lang = $params["lang"];
    $offset = (int)$params["offset"] ?: 0;
    $limit = (int)$params["limit"] ?: 5;
    $version = $params["version"] === "full";
    $user = $params["user"];
    $is_admin = false;

    if ($user) {
      $u = isAdmin($user);
      if (!$u["status"]) {
        $is_admin = false;
      }else{
        $is_admin = $version;
      }
    }
    // sa_news_images

    $partial_sql = "SELECT `id`,`title`,`slug`,`details`,`date`,
                            (SELECT nsi.`image`
                             FROM `sa_news_images` nsi
                             WHERE nsi.`news_id` = ns.`id`
                             AND nsi.`deleted_at` IS NULL
                              ORDER BY `order` DESC
                              LIMIT 1) as images
                      ".($is_admin ? ",`status`" : "")."
                    FROM `sa_news` ns
                    WHERE `type` = '$type'
                    ".($keyword ? "AND (`title` LIKE '%$keyword%' OR `details` LIKE '%$keyword%')" : "")."
                    AND `deleted_at` IS NULL
                    ".(!$is_admin ? " AND `status` = '1' AND `lang` = '$lang' " : "")."
                    ORDER BY `created_at` DESC";
    $sql = "$partial_sql
            LIMIT $limit OFFSET $offset";

    $news_query = $this->db->query($sql);
    $news = $news_query->result_array();

    $news_count_query = $this->db->query($partial_sql);
    $news_count = $news_count_query->result_array();

    $new_list = [];
    foreach ($news as $key => $item) {
      $images = [];
      $imgs = $item["images"] ? explode($this->local_sub_seperator,$item["images"]) : [];
      foreach ($imgs as $img) {
        $images[] = [
          "small" => explode($this->local_seperator,$img)[0],
          "large" => explode($this->local_seperator,$img)[1]
        ];
      }
      $sublist = [
        "id" => $item["id"],
        "title" => $item["title"],
        "slug" => $item["slug"],
        "details" => strip_tags($item["details"]),
        "date" => $item["date"] ? date("Y-m-d",strtotime($item["date"])) : null,
        "images" => $images,
      ];
      if (isset($item["status"])) {
        $sublist = $sublist +["status" => ($item["status"] === "1" ? true : false)];
      }
      $new_list[] = $sublist;
    }

    return rest_response(
      Status_codes::HTTP_OK,
      $this->lang->line("Success"),
      [
        "count" => count($news_count),
        "list" => $new_list
      ]
    );
  }

  function addNew($params){
    $params["image_link"] = filter_var($params["image_link"], FILTER_VALIDATE_URL) ? $params["image_link"] : NULL;
    $user = $params["user"];
    $type = $params["type"];
    $title = $params["title"];
    $details = trim($params["details"]);
    // $cover_image = $params["cover_image"];
    $date = $params["date"];
    $status = $params["status"];
    $images = $params["images"];
    $tags = $params["tags"];
    $lang = $params["lang"] && in_array($params["lang"],['az','en','ru','tr']) ? $params["lang"] : "az";

    if (!$user || !$type || !$title) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $insert_list = [
      "user_id" => $u["body"]["id"],
      "type" => $type,
      "title" => $title,
      "slug" => slugify(substr($title,0,150))."-".crc32(uniqid()),
      "details" => $details,
      "date" => $date,
      "image_link" => $params["image_link"],
      "lang" => $lang,
      "status" => $status,
    ];



    $this->db->insert("sa_news",$insert_list);
    $news_id = $this->db->insert_id();


    if ($images) {
      $images = is_array($images) ? $images : explode(",",$images);
      $sizes = [
        "small,540",
        "large,960"
      ];
      $images_data = updateCDN($images,$path = "news/gallery",$sizes);
      if (!isset($images_data["id"])) {
        $insert_images_batch = [];
        foreach ($images_data as $key => $file) {
          $images = [];
          foreach ($file["folder"] as $item) {
            $images[] = $file["host"].$file["path"].$item."/".$file["file"];
          }
          $insert_images_batch[] = [
            "news_id" => $news_id,
            "order" => $key,
            "image" => implode($this->local_seperator,$images)
          ];
        }
        $insert_images_batch ? $this->db->insert_batch("sa_news_images",$insert_images_batch) : "";
      }
    }


    $tags = is_array($tags) ? $tags : explode(",",$tags);
    $insert_tags_batch = [];
    foreach ($tags as $key => $tag) {
      $insert_tags_batch[] = [
        "news_id" => $news_id,
        "lang" => $lang,
        "name" => $tag
      ];
    }
    $insert_tags_batch ? $this->db->insert_batch("sa_news_tags",$insert_tags_batch) : "";


    return rest_response(
      Status_codes::HTTP_CREATED,
      $type === "promotion" ? $this->lang->line("Promotion added successfully") : $this->lang->line("News added successfully"),
      [
        "id" => $news_id,
        "slug" => $insert_list["slug"]
      ]
    );
  }

  function update($params){
    $params["image_link"] = filter_var($params["image_link"], FILTER_VALIDATE_URL) ? $params["image_link"] : NULL;
    $user = $params["user"];
    $news = $params["news"];
    $title = strip_tags(trim($params["title"]));
    $details = trim($params["details"]);
    $status = $params["status"];

    $tags = $params["tags"] ? (is_array($params["tags"]) ? $params["tags"] : explode(",",$params["tags"])) : [];
    $images = $params["images"] && is_array($params["images"]) ? $params["images"] : [];
    $exist_images = $params["exist_images"] && is_array($params["exist_images"]) ? $params["exist_images"] : [];
    $date = $params["date"];

    if (!$user || !$news || !$title) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }

    $exist_news = $this->db->select("id,TRIM(title) as title,type,slug,lang")
                            ->from("sa_news")
                              ->where("id",$news)
                                ->where("deleted_at",null)
                                  ->get();

    if(!$exist_news->num_rows()){
      return rest_response(
        Status_codes::HTTP_NOT_FOUND,
        $this->lang->line("News not found")
      );
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $update_list = [
      "image_link" => $params["image_link"],
      "details" => $details,
      "status" => $status,
    ];

    $exist_news_row = $exist_news->row();
    if ($exist_news_row->title !== $title) {
      $update_list = $update_list + ["title" => $title,"slug" => slugify(substr($title,0,150))."-".((int)$news*9999999)];
    }

    $this->db->where("id",$news);
    $done = $this->db->update("sa_news",$update_list);

    $exist_images_query = "";
    foreach ($exist_images as $key => $item) {
      $item = $this->db->escape_str($item);
      $exist_images_query .= " AND image NOT LIKE '%$item%' ";
    }
    $removed_images_sql = "UPDATE `sa_news_images` SET `deleted_at` = '$date'
                           WHERE news_id = $news
                           $exist_images_query";
    $this->db->query($removed_images_sql);

    if ($images) {
      $images = is_array($images) ? $images : explode(",",$images);
      $sizes = [
        "small,540",
        "large,960"
      ];
      $images_data = updateCDN($images,$path = "news/gallery",$sizes);
      if (!isset($images_data["id"])) {
        $insert_images_batch = [];
        foreach ($images_data as $key => $file) {
          $images = [];
          foreach ($file["folder"] as $item) {
            $images[] = $file["host"].$file["path"].$item."/".$file["file"];
          }
          $insert_images_batch[] = [
            "news_id" => $news,
            "order" => $key,
            "image" => implode($this->local_seperator,$images)
          ];
        }
        $insert_images_batch ? $this->db->insert_batch("sa_news_images",$insert_images_batch) : "";
      }
    }


    // tags will be updated if there is any change
    if ($tags) {
      $lang = $exist_news_row->lang;
      $insert_tags = [];
      foreach ($tags as $key => $item) {
        $insert_tags[] = [
          "name" => $item,
          "news_id" => $news,
          "lang" => $lang,
        ];
      }
      $removed_tags_sql = "DELETE FROM `sa_news_tags`
                           WHERE news_id = $news";
      $this->db->query($removed_tags_sql);
      $this->db->insert_batch("sa_news_tags",$insert_tags);
    }



    if ($done) {
      $this->db->insert("sa_updates",[
        "user_id" => $u["body"]["id"],
        "other_id" => $news,
        "title" => "news_updated",
        "details" => "News updated"
      ]);
    }

    return rest_response(
      Status_codes::HTTP_OK,
      $exist_news_row->type === "promotion" ? $this->lang->line("Promotion updated") : $this->lang->line("News updated"),
      [
        "slug" => isset($update_list["slug"]) ? $update_list["slug"] : $exist_news_row->slug
      ]
    );
  }

  function changeStatus($params){
    $user = $params["user"];
    $news = $params["news"];
    $status = (string)$params["status"] === "0" ? "0" : "1";

    if (!$user || !$news) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }
    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $exist_news = $this->db->select("id,TRIM(title) as title,type,slug,lang")
                            ->from("sa_news")
                              ->where("id",$news)
                                ->where("deleted_at",null)
                                  ->get();

    if(!$exist_news->num_rows()){
      return rest_response(
        Status_codes::HTTP_NOT_FOUND,
        $this->lang->line("News not found")
      );
    }

    $exist_news_row = $exist_news->row();

    $this->db->where("id",$news);
    $done = $this->db->update("sa_news",["status" => $status]);

    if ($done) {
      $this->db->insert("sa_updates",[
        "user_id" => $u["body"]["id"],
        "other_id" => $news,
        "title" => "news_status_updated",
        "details" => "News status updated"
      ]);
    }

    return rest_response(Status_codes::HTTP_OK,$exist_news_row->type === "promotion" ? $this->lang->line("Promotion updated") : $this->lang->line("News updated"));
  }

  function delete($params){
    $user = $params["user"];
    $news = $params["news"];
    $date = $params["date"];

    if (!$user || !$news) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }

    $u = isAdmin($user);
    if (!$u["status"]) return $u["body"];

    $exist_news = $this->db->select("id,type")
                            ->from("sa_news")
                              ->where("id",$news)
                                ->where("deleted_at",null)
                                  ->get();

    $type = $exist_news->row()->type;
    if(!$exist_news->num_rows()){
      return rest_response(
        Status_codes::HTTP_NOT_FOUND,
        $this->lang->line("News not found")
      );
    }

    $this->db->where("id",$news);
    $done = $this->db->update("sa_news",["deleted_at" => $date]);


    if ($done) {
      $this->db->insert("sa_updates",[
        "user_id" => $u["body"]["id"],
        "other_id" => $news,
        "title" => "news_deleted",
        "details" => "News deleted"
      ]);
    }


    return rest_response(
      Status_codes::HTTP_OK,
      $type === "promotion" ? $this->lang->line("Promotion deleted") : $this->lang->line("News deleted")
    );
  }

  function details($params){
    $slug = $params["slug"];
    $id = $params["id"];
    $user = $params["user"];
    $lang = $params["lang"];
    $version = $params["version"] === "full";

    $is_admin = false;

    if ($user) {
      $u = isAdmin($user);
      if (!$u["status"]) {
        $is_admin = false;
      }else{
        $is_admin = $version;
      }
    }

    if (!$slug && !$id) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        $this->lang->line("Missed parameter")
      );
    }

    $item_select = $id ? " `id` = $id " : " `slug` = '$slug' ";


    $status_query = $is_admin ? " ns.`status`," : "";
    $this->db->query("SET SESSION group_concat_max_len = 1000000");
    $sql = "SELECT `id`,`title`,`slug`,`details`,`image_link`,`date`,$status_query
                  (SELECT GROUP_CONCAT(nsi.`image` separator '$this->local_sub_seperator')
                   FROM `sa_news_images` nsi
                   WHERE nsi.`news_id` = ns.`id`
                   AND nsi.`deleted_at` IS NULL) as images,
                  (SELECT GROUP_CONCAT(nst.`name` separator '$this->local_sub_seperator')
                   FROM `sa_news_tags` nst
                   WHERE nst.`news_id` = ns.`id`
                   AND nst.`deleted_at` IS NULL) as tags
            FROM `sa_news` ns
            WHERE $item_select
            ".(!$is_admin ? " AND `lang` = '$lang' " : "")."
            AND `deleted_at` IS NULL
            ".(!$is_admin ? "AND `status` = '1'" : "")."
            LIMIT 1";
    $exist_news = $this->db->query($sql);

    if(!$exist_news->num_rows()){

      if (!$is_admin) {
        $sql_belongto_query = $this->db->query("SELECT `id`,`belong_id` FROM `sa_news` WHERE `deleted_at` IS NULL AND $item_select");

        if ($sql_belongto_query->num_rows()) {
          $belongto = $sql_belongto_query->row_array();
          if ($belongto["belong_id"]) {
            $belongids_query = " AND (`belong_id` IN ({$belongto["id"]},{$belongto["belong_id"]}) OR `id` IN ({$belongto["id"]},{$belongto["belong_id"]}))";
          } else {
            $belongids_query = " AND (`belong_id` IN ({$belongto["id"]}) OR `id` IN ({$belongto["id"]}))";
          }
          $lang_news_query = "SELECT ns.`slug`
                              FROM `sa_news` ns
                              WHERE ns.`deleted_at` IS NULL
                              AND `lang` = '$lang'
                              AND `status` = '1'
                              $belongids_query";
          $true_slug = $this->db->query($lang_news_query);
          if ($true_slug->num_rows()) {
            $true_slug = $true_slug->row_array();
            return rest_response(
              Status_codes::HTTP_PARTIAL_CONTENT,
              $this->lang->line("News not found"),
              [
                "slug" => $true_slug["slug"]
              ]
            );
          }
        }
      }


      return rest_response(
        Status_codes::HTTP_NOT_FOUND,
        $this->lang->line("News not found")
      );
    }

    $news = (array)$exist_news->row();

    $images = [];
    $imgs = $news["images"] ? explode($this->local_sub_seperator,$news["images"]) : [];
    foreach ($imgs as $img) {
      $arr = explode($this->local_seperator,$img);
      $images[] = [
        "small" => isset($arr[0]) ? $arr[0] : null,
        "large" => isset($arr[1]) ? $arr[1] : null
      ];
    }

    $new_list = [
      "id" => $news["id"],
      "title" => $news["title"],
      "slug" => $news["slug"],
      "details" => $news["details"],
      "image_link" => $news["image_link"],
      "date" => $news["date"],
      "images" => $images,
      "tags" => $news["tags"] ? explode($this->local_sub_seperator,$news["tags"]) : []
    ];

    $new_list += isset($news["status"]) ? ["status" => $news["status"] === "1"] : [];

    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),$new_list);
  }

}
