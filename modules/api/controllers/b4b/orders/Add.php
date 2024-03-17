<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function isApprove()
  {
    $auth_user = checkCompany(null,true);

    $params = [
      "b4buserid" => (int)headers("b4buserid") ?: NULL,
      "remoteb4buserid" => (int)headers("remoteb4buserid") ?: NULL,
      "b4busercurrency" => headers("b4busercurrency") ?: NULL,
      "cart_ids" => $this->input->post("cart_ids") ?: NULL,
      // "warehouse_id" => (int)$this->input->post("warehouse_id") ?: NULL,
      "group_id" => (int)$this->input->post("group_id") ?: NULL,
      "entry_comment" => $this->input->post("entry_comment") ?: "",
      "auth_user" => $auth_user,
      "date" => now(),
      "user_ip" => headers("useraddress") ?: "127.0.0.1",
    ];

    if(!$params["remoteb4buserid"]){
      log_message("error",json_encode($params,true));
    }

    validateArray($params, ["cart_ids", "group_id", "remoteb4buserid"]);

    $this->load->model("b4b/orders/Add_model", "model");
    $res = $this->model->isApprove($params);
    return json_response($res);
  }

}
