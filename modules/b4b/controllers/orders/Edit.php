<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true);
  }

  public function confirmApprove()
  {
    $params = [
      "cart_ids" => $this->custom_input->put("cart_ids"),
      // "warehouse_id" => $this->custom_input->put("warehouse_id"),
      "group_id" => $this->custom_input->put("group_id"),
      "entry_comment" => $this->custom_input->put("entry_comment"),
    ];

    $this->load->model("orders/Edit_model", "model");
    $res = $this->model->confirmApprove($params);
    return json_response($res);
  }
}
