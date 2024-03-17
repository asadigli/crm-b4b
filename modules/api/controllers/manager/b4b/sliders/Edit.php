<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct(){
    checkHeaders();
    parent::__construct();
    $this->load->model("manager/b4b/sliders/Edit_model", "model");
  }

  public function index(){
    checkAdmin();
    $params = [
      "id" => $this->custom_input->put("id"),
      "title" => $this->custom_input->put("title") ?: null,
      "description" => $this->custom_input->put("description") ?: null,
      "start_date" => $this->custom_input->put("start_date"),
      "end_date" => $this->custom_input->put("end_date"),
      "url" => $this->custom_input->put("url"),
      "image" => $this->custom_input->put("image"),
      "is_active" => $this->custom_input->put("is_active") ? STATUS_ACTIVE : STATUS_DEACTIVE,
    ];
    validateArray($params, ["id"]);




    if ($params["image"]) {
      $permitted_image_exts = $this->config->item("permitted_image_exts") ?: [];
      $extension = explode("/",substr($params["image"], 0, strpos($params["image"], ';base64')))[1];
      if (!in_array($extension,$permitted_image_exts)) {
        return json_response(rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("Only jpg, jpeg, png extensions acceptable")
        ));
      }

      $path = "assets/b4b/uploads/image_" . md5(microtime()) . "." . $extension;
      Cdn::base64ToJpeg($params["image"],$path);
      // Cdn::imgResize($path, $path, 500, 500, $quality = 100,false);
      $params["image"] = $path;
    }

    $res = $this->model->index($params);
    return json_response($res);
  }

  public function delete($id){
    checkAdmin();
    $params = [
      "id" => $id,
      "date" => now(),
    ];
    $res = $this->model->delete($params);
    return json_response($res);
  }

}
