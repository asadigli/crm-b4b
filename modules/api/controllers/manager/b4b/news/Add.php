<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  public function __construct(){
    checkHeaders();
    parent::__construct();
  }

  public function index(){
    $this->load->model("manager/b4b/news/Add_model", "model");
    $auth_user = checkAdmin(null,true);

    $params = [
      "creator_id" => $auth_user["id"],
      "title" => $this->input->post("title") ?: null,
      "body" => $this->input->post("body") ?: null,
      "start_date" => $this->input->post("start_date") ?: null,
      "end_date" => $this->input->post("end_date") ?: null,
      "image" => $this->input->post("image") ?: null,
      "type" => $this->input->post("type") ?: null,
      "is_active" => $this->input->post("is_active") ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "is_popup" => $this->input->post("is_popup") ? STATUS_ACTIVE : STATUS_DEACTIVE,
    ];

    if (!$params["title"] && !$params["image"]) {
      validateArray(["title" => null,"image" => null],["title","image"]);
    }

    $params["type"] = $params["type"] && isset(special_codes("pop_up.types")[$params["type"]]) ? special_codes("pop_up.types")[$params["type"]] : null;

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
      $params["image"] = $path;
    }

    $res = $this->model->index($params);
    return json_response($res);
  }

}
