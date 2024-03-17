<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct(){
    checkHeaders();
    parent::__construct();
    $this->load->model("manager/b4b/brand_sliders/Add_model", "model");
  }

  public function index(){
    checkAdmin();
    $params = [
      "title" => $this->input->post("title") ?: null,
      "description" => $this->input->post("description") ?: null,
      "start_date" => $this->input->post("start_date"),
      "end_date" => $this->input->post("end_date"),
      "url" => $this->input->post("url"),
      "image" => $this->input->post("image"),
      "is_active" => $this->input->post("is_active") ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "is_clickable" => $this->input->post("is_clickable") ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "type" => special_codes("b4b_slides.types.brands") ?: null,
    ];
    validateArray($params, ["type"]);

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

}
