<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  function index() {
    checkCompany();
    $params = [
      "b4buserid"       => headers("b4buserid"),
      "company_device"  => headers("userdevice"),
      "company_ip"      => headers("useraddress"),
      "online_at"       => now(),
      "url_path"        => $this->input->post("url_path"),
      "entry_token"     => $this->input->post("entry_token"),
    ];

    $params_local = $params;
    unset($params_local["online_at"]);
    $key = "online_check." . md5(json_encode($params_local)) . ".cache";

    if ($params["entry_token"]) {
      checkEntryToken($params["entry_token"], null, true, false);
    }

    $data = $this->cache->get($key);
    if ($data) {
      return json_response(rest_response(
        Status_codes::HTTP_CREATED,
        lang("Success")
      ), null, false);
    }
    $this->cache->save($key,now(),50);

    validateArray($params,["b4buserid"]);

    $this->load->model("b4b/onlines/Add_model","model");
    $res = $this->model->index($params);

    return json_response($res,null,false);
  }
}
