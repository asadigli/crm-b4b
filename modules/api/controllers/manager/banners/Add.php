<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct(){
    checkHeaders();
    parent::__construct();
    $this->load->model("manager/banners/Add_model", "model");
  }

  function action() {
    $params = [
      "title" => $this->input->post("title") ?: null,
      "description" => $this->input->post("description") ?: null,
      "url" => $this->input->post("url") ?: null,
      "start_date" => $this->input->post("start_date") ?: null,
      "end_date" => $this->input->post("end_date") ?: null,
      "image" => $this->input->post("image") ?: null,
      "is_active" => $this->input->post("is_active") ? STATUS_ACTIVE : STATUS_DEACTIVE,
    ];
    validateArray($params,["title"]);

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

    $res = $this->model->action($params);
    return json_response($res);
  }

}
