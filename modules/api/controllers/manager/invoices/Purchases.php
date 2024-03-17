<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchases extends MY_Controller {

  public function __construct(){
    parent::__construct();
    checkHeaders();
  }

  public function index() {
    checkAdmin();
    $now = now();
    $params = [
      "userid" => (int)headers("userid") ?: null,
      "operation_date" => $now,
      "start_date"    => $this->input->get("start_date")    ?: null,
      "end_date"      => $this->input->get("end_date")      ?: null,
      "offset"        => (int)$this->input->get("offset")   ?: null,
      "brand"         => $this->input->get("brand")         ?: null,
      "brand_code"    => $this->input->get("brand_code")    ?: null,
      "oem_code"      => $this->input->get("oem_code")      ?: null,
      "warehouse"     => $this->input->get("warehouse"),
      "company"       => $this->input->get("company"),
      "export"        => (int)!!$this->input->get("export"),
      "warehouse_list"  => $this->config->item("warehouse_list")
    ];

    $params["warehouse"] = !is_null($params["warehouse"]) && $params["warehouse"] !== "" && in_array((int)$params["warehouse"],array_keys($params["warehouse_list"])) ? $params["warehouse"] : NULL;
    validateArray($params, ["start_date","end_date"]);

    $this->load->model("manager/invoices/Purchases_model","model");
    $res = $this->model->index($params);
    return json_response($res);
  }

  public function details($code) {
    checkAdmin();
    $params = [
      "code" => $code,
    ];

    validateArray($params, ["code"]);

    $this->load->model("manager/invoices/Purchases_model","model");
    $res = $this->model->details($params);
    return json_response($res);
  }


}
