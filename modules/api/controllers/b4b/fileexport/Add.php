<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function addToHistory()
  {
    checkCompany();
    $params = [
      "entry_id" => headers("b2buserid"),
      "name" => trim(str_replace(["'", "\""], "", $this->input->post("name"))),
      "key" => $this->input->post("key"),
      "keyword" => $this->input->post("keyword"),
      "params" => $this->input->post("params"),

      "operation_date" => now(),
    ];

    $params["params"] = encode_blob(json_encode($params["params"]));
    validateArray($params, ["name", "key"]);

    $this->load->model("b4b/fileexport/Add_model", "model");
    $res = $this->model->addToHistory($params);
    return json_response($res);
  }
}
