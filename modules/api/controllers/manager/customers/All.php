<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct() {
    parent::__construct();
    checkHeaders();
  }

  public function index() {
    checkAdmin();
    $now = now();
    $params = [
      "userid"                  => (int)headers("userid") ?: null,
      "operation_date"          => $now,
      "keyword"                 => $this->input->get("keyword"),
      "due_date"                => $this->input->get("due_date"),
      "customer_id"             => $this->input->get("customer_id"),
      "customer_type"           => $this->input->get("customer_type"),
      "status"                  => $this->input->get("status"),
      "city_id"                 => $this->input->get("city_id"),
      "currency_id"             => $this->input->get("currency_id"),
      "search_by_debts"         => $this->input->get("search_by_debts"),
      "offset"                  => $this->input->get("offset"),
      "export"                  => $this->input->get("export") ? 1 : 0,
      "is_inactive_customers"   => $this->input->get("is_inactive_customers") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "inactive_customers"      => (int)$this->input->get("inactive_customers") ?: NULL,
    ];

    $params["search_by_debts"] = in_array($params["search_by_debts"],["is_no_debt","is_negative_debt","is_positive_debt"]) ? $params["search_by_debts"] : "";

    $params["due_date"] = $params["due_date"] ? $params["due_date"] : date("Y-m-d");
    validateDate($params["due_date"]);
    $params["due_date"] = date("Y-m-d", strtotime($params["due_date"] . ' +1 day'));

    $params["customer_type"]  = $params["customer_type"] && in_array((string)$params["customer_type"],["211","311","531"]) ? (string)$params["customer_type"] : NULL;
    $params["status"]         = $params["status"] && in_array($params["status"], ["active","not_active"]) ? $params["status"] : NULL;

    $this->load->model("manager/customers/All_model", "model");
    $res = $this->model->index($params);
    return json_response($res);
  }

  public function list() {
    checkAdmin();
    $params = [];
    $this->load->model("manager/customers/All_model", "model");
    $res = $this->model->list($params);
    return json_response($res);
  }

  public function cityList() {
    checkAdmin();
    $params = [];
    $this->load->model("manager/customers/All_model", "model");
    $res = $this->model->cityList($params);
    return json_response($res);
  }


}
