<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct(){
    checkHeaders();
    parent::__construct();
    $this->load->model("manager/banners/All_model", "model");
  }

  function index() {
    $params = [];
    $res = $this->model->index($params);
    return json_response($res);
  }

  function edit() {
    $params = [
      "id" => $this->custom_input->put("id") ?: null,
      "title" => $this->custom_input->put("title") ?: null,
      "description" => $this->custom_input->put("description") ?: null,
      "url" => $this->custom_input->put("url") ?: null,
      "start_date" => $this->custom_input->put("start_date") ?: null,
      "end_date" => $this->custom_input->put("end_date") ?: null,
      "is_active" => $this->custom_input->put("is_active") ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "image" => $this->custom_input->put("image") ?: null,
    ];
    validateArray($params,["title","id"]);

    if ($params["image"]) {
      $extension = explode('/', @mime_content_type($params["image"]))[1];
      if (!in_array($extension,["webp","svg"])) {
        $path = FCPATH . "assets/api/temp/image_" . md5(microtime()) . "." . $extension;
        Cdn::base64ToJpeg($params["image"],$path);
        Cdn::imgResize($path, $path, 500, 500, $quality = 100,false);

        $base64 = Cdn::getBase64FromImage($path);

        if (file_exists($path)) {
          unlink($path);
        }

        $params["image"] = encode_blob($base64);
      } else {
        $params["image"] = encode_blob($params["image"]);
      }

    }

    $res = $this->model->edit($params);
    return json_response($res);
  }

  function delete($id) {
    $params = [
      "id" => $id,
      "date" => now(),
    ];
    $res = $this->model->delete($params);
    return json_response($res);
  }


}
