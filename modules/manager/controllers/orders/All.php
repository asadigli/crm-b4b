<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct(){
    parent::__construct();
    Auth::check(true,["admin","main_admin","warehouseman", "manager"],"profile");

    if (!Auth::allowedOrderGroups()) {
      redirect(path_local("profile"));
    }
  }

  public function index()
  {
    $params = [
      "end_date" => $this->input->get("end_date"),
      "start_date" => $this->input->get("start_date"),
      "folder_id" => $this->input->get("folder_id"),
      "keyword" => $this->input->get("keyword"),
      "status" => $this->input->get("status"),
      "no_date_filter" => $this->input->get("no_date_filter"),
      "group_id" => $this->input->get("group_id") ?: (isset($_COOKIE["group_id"]) ? $_COOKIE["group_id"] : NULL),
      "copy_check_indexes" => isset($_COOKIE[Auth::user()."_copy_check_indexes"]) ? json_decode($_COOKIE[Auth::user()."_copy_check_indexes"], true) : [],
    ];

    $this->extraCSS = [
      "../globals/css/libs/jquery-ui.css",
    ];

    $this->extraJS = [
      "../globals/js/libs/jquery-ui.min.js",
      "js/helpers.js",
      "js/pages/orders/index.js",
      "js/pages/orders/folders_modal.js",
    ];

    $order_groups_ids = array_map(function($i) {
      return (int)$i["id"];
    },Auth::allowedOrderGroups());

    $order_properties_cache_key = "order_properties.cache";
    $order_properties = $this->cache->get($order_properties_cache_key);

    if(!$order_properties) {
      $order_properties = [];
      $this->load->model("orders/All_model", "model");
      $order_statuses_res = $this->model->statuses([]);
      $order_groups_res = $this->model->order_groups([]);

      if(isset($order_statuses_res["code"]) && isset($order_groups_res["code"])
              && $order_groups_res["code"] === Status_codes::HTTP_OK && $order_statuses_res["code"] === Status_codes::HTTP_OK){
        $order_properties = [
          "statuses"      => $order_statuses_res["data"],
          "order_groups"  => $order_groups_res["data"],
        ];
        $this->cache->save($order_properties_cache_key, $order_properties, 3600 * 24);
      }
    }

    $this->page_title = lang("Orders");
    $this->view([
      "layouts/header",
      "orders/index",
      "layouts/footer"
    ],[
      "order_groups"      => isset($order_properties["order_groups"]) ? $order_properties["order_groups"] : [],
      "order_statuses"    => isset($order_properties["statuses"]) ? $order_properties["statuses"] : [],
      "url_params"        => $params,
      "order_groups_ids"  => $order_groups_ids
    ]);
  }

  public function live()
  {
    $params = [
      "end_date" => $this->input->get("end_date"),
      "start_date" => $this->input->get("start_date"),
      "keyword" => $this->input->get("keyword"),
      "folder_id" => $this->input->get("folder_id"),
      "status" => $this->input->get("status"),
      "no_date_filter" => $this->input->get("no_date_filter"),
      "group_id" => $this->input->get("group_id"),
      "offset" => $this->input->get("offset"),
    ];

    $this->load->model("orders/All_model", "model");
    $res = $this->model->index($params);

    return json_response($res);
  }
}
