<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true);
  }

  public function isApprove()
  {
    $params = [
      "cart_ids" => $this->input->post("cart_ids"),
      // "warehouse_id" => $this->input->post("warehouse_id"),
      "group_id" => $this->input->post("group_id"),
      "entry_comment" => $this->input->post("entry_comment"),
    ];

    $this->load->model("orders/Add_model", "model");
    $res = $this->model->isApprove($params);
    return json_response($res);
  }
}
