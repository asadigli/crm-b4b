<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct(){
    parent::__construct();
    checkHeaders();
  }

  public function index(){
    checkAdmin();
    $params = [
      "end_date" => trim(str_replace("'", "\"", $this->input->get("end_date"))),
      "start_date" => trim(str_replace("'", "\"", $this->input->get("start_date"))),
      "keyword" => $this->input->get("keyword") ?: null,
      "brand_id" => (int)$this->input->get("brand_id"),
      "entry_id" => (int)$this->input->get("entry_id"),
    ];
    $params["keyword"] = trim(str_replace(["'", "\""], "", $params["keyword"]));

    validateArray($params, ["start_date", "end_date"]);

    $this->load->model("manager/products/price_offers/All_model", "model");
    $res = $this->model->index($params);
    return json_response($res);
  }

}
