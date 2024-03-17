<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct() {
    parent::__construct();
    checkHeaders();
    $this->load->model("manager/supervisors/All_model", "model");
  }

  function index() {
    $params = [
      "mode" => $this->input->get("mode")
    ];
    $res = $this->model->index($params);
    return json_response($res);
  }

  function edit() {
    $params = [
      "id" => $this->custom_input->put("id") ?: null,
      "curator_name" => $this->custom_input->put("name") ?: null,
      "curator_ava_name" => $this->custom_input->put("ava_name") ?: null,
      "curator_surname" => $this->custom_input->put("surname") ?: null,
      "curator_mobile" => $this->custom_input->put("phone") ?: null,
      "curator_email" => $this->custom_input->put("email") ?: null,
      "curator_whatsapp" => $this->custom_input->put("whatsapp") ?: null,
      "image" => $this->custom_input->put("image") ?: null,
    ];
    validateArray($params,["curator_name","curator_mobile","id"]);

    if ($params["image"]) {
      $permitted_image_exts = $this->config->item("permitted_image_exts") ?: [];
      $extension = explode("/",substr($params["image"], 0, strpos($params["image"], ';base64')))[1];
      $path = FCPATH . "assets/api/temp/image_" . md5(microtime()) . "." . $extension;
      Cdn::base64ToJpeg($params["image"],$path);
      Cdn::imgResize($path, $path, 500, 500, $quality = 100,false);

      $base64 = Cdn::getBase64FromImage($path);

      if (file_exists($path)) {
        unlink($path);
      }

      $params["image"] = encode_blob($base64);
    }

    $res = $this->model->edit($params);
    return json_response($res);
  }

}
