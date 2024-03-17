<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function editPrice($id)
  {
    // checkAdmin();
    $params = [
      "custom_main_sale_price" => empty($this->custom_input->put("custom_main_sale_price")) && $this->custom_input->put("custom_main_sale_price") !== '0' ? NULL : $this->custom_input->put("custom_main_sale_price"),
      "id" => (int)$id,
    ];

    validateArray($params, ["id"]);

    $this->load->model("manager/products/Edit_model", "model");
    $res = $this->model->editPrice($params);
    return json_response($res);
  }

  public function discountPrice($id){
    checkAdmin();
    $params = [
      "id" => $id,
      "discount_price" => $this->custom_input->put("discount_price") ?: null,
      "has_discount" => $this->custom_input->put("has_discount") ? STATUS_ACTIVE : STATUS_DEACTIVE,
    ];
    validateArray($params, ["id"]);

    $this->load->model("manager/products/Edit_model", "model");
    $res = $this->model->discountPrice($params);
    return json_response($res);
  }

  public function hidePrice($id){
    checkAdmin();
    $params = [
      "id" => $id,
      "is_b4b_price_hidden" => $this->custom_input->put("is_b4b_price_hidden") ? STATUS_ACTIVE : STATUS_DEACTIVE,
    ];
    validateArray($params, ["id"]);

    $this->load->model("manager/products/Edit_model", "model");
    $res = $this->model->hidePrice($params);
    return json_response($res);
  }

  public function isNewFromWarehouse($id)
  {
    checkAdmin();

    $params = [
      "id" => $id,
      "is_new_from_warehouse" => $this->custom_input->put("is_new_from_warehouse") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
      "new_from_warehouse_start_date" => $this->custom_input->put("new_from_warehouse_start_date") ?: NULL,
      "new_from_warehouse_end_date" => $this->custom_input->put("new_from_warehouse_end_date") ?: NULL,
      "new_from_warehouse_image" => $this->custom_input->put("new_from_warehouse_image") ?: NULL,
    ];

    if ($params["new_from_warehouse_image"]) {
      $permitted_image_exts = $this->config->item("permitted_image_exts") ?: [];
      $extension = explode("/",substr($params["new_from_warehouse_image"], 0, strpos($params["new_from_warehouse_image"], ';base64')))[1];
      if (!in_array($extension,$permitted_image_exts)) {
        return json_response(rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("Only jpg, jpeg, png extensions acceptable")
        ));
      }

      $path = "assets/b4b/uploads/image_" . md5(microtime()) . "." . $extension;
      Cdn::base64ToJpeg($params["new_from_warehouse_image"],$path);
      // Cdn::imgResize($path, $path, 500, 500, $quality = 100,false);
      $params["new_from_warehouse_image"] = $path;
    }

    validateArray($params, ["id"]);

    if($params["is_new_from_warehouse"] === STATUS_ACTIVE) {
      validateArray($params, ["new_from_warehouse_start_date", "new_from_warehouse_end_date"]);
    }

    if(strlen($params["new_from_warehouse_start_date"])) {
      validateDate($params["new_from_warehouse_start_date"]);
    }

    if(strlen($params["new_from_warehouse_end_date"])) {
      validateDate($params["new_from_warehouse_end_date"]);
    }

    $this->load->model("manager/products/Edit_model", "model");
    $res = $this->model->isNewFromWarehouse($params);
    return json_response($res);
  }

}
