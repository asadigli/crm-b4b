<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct(){
    checkHeaders();
    parent::__construct();
  }

  public function index($id){
    $this->load->model("manager/b4b/news/Edit_model","model");
    $auth_user = checkAdmin(null,true);
    $role = array_search($auth_user["role"], special_codes("system_users.roles")) ?: null;

    $params = [
      "id" => $id,
      "creator_id" => $auth_user["id"],
      "title" => $this->custom_input->put("title") ?: null,
      "body" => $this->custom_input->put("body") ?: null,
      "start_date" => $this->custom_input->put("start_date") ?: null,
      "end_date" => $this->custom_input->put("end_date") ?: null,
      "path" => $this->custom_input->put("image") ?: null,
      "type" => $this->custom_input->put("type") ?: null,
      "is_active" => $this->custom_input->put("is_active") ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "is_popup" => $this->custom_input->put("is_popup") ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "deleted_image" => $this->custom_input->put("deleted_image"),
    ];

    if (!$params["title"] && !$params["path"]) {
      validateArray(["title" => null,"path" => null],["title","path"]);
    }

    $params["type"] = $params["type"] && isset(special_codes("pop_up.types")[$params["type"]]) ? special_codes("pop_up.types")[$params["type"]] : null;

    if(!$params["deleted_image"]){
      if(isset($params["path"]) && $params["path"]){
        if(!str_starts_with($params["path"], "assets/b4b")){
          if ($params["path"]) {
            $permitted_image_exts = $this->config->item("permitted_image_exts") ?: [];

            $extension = explode("/",substr($params["path"], 0, strpos($params["path"], ';base64')))[1];
            if (!in_array($extension,$permitted_image_exts)) {
              return json_response(rest_response(
                Status_codes::HTTP_BAD_REQUEST,
                lang("Only jpg, jpeg, png extensions acceptable")
              ));
            }

            $path = "assets/b4b/uploads/image_" . md5(microtime()) . "." . $extension;
            Cdn::base64ToJpeg($params["path"],$path);

            $params["path"] = $path;
          }
        }
      }
    }

    $res = $this->model->index($params);
    return json_response($res);
  }

  public function delete($id){
    checkAdmin();
    $this->load->model("manager/b4b/news/Edit_model","model");
    $params = [
      "id" => $id,
      "date" => now(),
    ];

    validateArray($params, ["id"]);

    $res = $this->model->delete($params);
    return json_response($res);

  }

}
