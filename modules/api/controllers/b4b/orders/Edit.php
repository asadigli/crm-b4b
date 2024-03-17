<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function confirmApprove()
  {
    $auth_user = checkCompany(NULL, true);

    $params = [
      "b4buserid" => (int)headers("b4buserid") ?: NULL,
      "remoteb4buserid" => (int)headers("remoteb4buserid") ?: NULL,
      "b4busercurrency" => headers("b4busercurrency") ?: NULL,
      "cart_ids" => $this->custom_input->put("cart_ids") ?: NULL,
      // "warehouse_id" => (int)$this->custom_input->put("warehouse_id") ?: NULL,
      "group_id" => (int)$this->custom_input->put("group_id") ?: NULL,
      "entry_comment" => $this->custom_input->put("entry_comment") ? htmlentities($this->custom_input->put("entry_comment")) : " ",
      "date" => now(),
      "auth_user" => $auth_user,
      "user_ip" => headers("useraddress") ?: "127.0.0.1",
    ];

    validateArray($params, ["cart_ids", "group_id", "b4busercurrency"]);

    $this->load->model("b4b/orders/Edit_model", "model");
    $res = $this->model->confirmApprove($params);
    return json_response($res);
  }
}
