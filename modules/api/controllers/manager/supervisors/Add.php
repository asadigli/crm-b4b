<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct(){
    parent::__construct();
    checkHeaders();
    $this->load->model("manager/supervisors/Add_model", "model");
  }

  function action() {
    $params = [
      "curator_name" => $this->input->post("name") ?: null,
      "curator_ava_name" => $this->input->post("ava_name") ?: null,
      "curator_surname" => $this->input->post("surname") ?: null,
      "curator_mobile" => $this->input->post("phone") ?: null,
      "curator_email" => $this->input->post("email") ?: null,
      "curator_whatsapp" => $this->input->post("whatsapp") ?: null,
      "image" => $this->input->post("image") ?: null,
    ];
    validateArray($params,["curator_name","curator_mobile"]);

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

    $res = $this->model->action($params);
    return json_response($res);
  }

}
