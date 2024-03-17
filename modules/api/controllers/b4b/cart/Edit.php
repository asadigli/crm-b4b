<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    checkHeaders();
  }

  public function editQuantity($id)
  {
    checkCompany();
    $params = [
      "b4buserid" => (int)headers("b4buserid") ?: null,
      "remoteb4buserid" => (int)headers("remoteb4buserid") ?: null,
      "id" => (int)$id ?: null,
      "quantity" => (int)$this->custom_input->put("quantity") ?: null
    ];

    validateArray($params, ["id", "quantity"]);

    $this->load->model("b4b/cart/Edit_model", "model");
    $res = $this->model->editQuantity($params);
    return json_response($res);
  }

  public function editNote($id){
    checkCompany();
    $params = [
      "b4buserid" => (int)headers("b4buserid") ?: null,
      "remoteb4buserid" => (int)headers("remoteb4buserid") ?: null,
      "id" => (int)$id ?: null,
      "comment" => $this->custom_input->put("comment") ?: null
    ];

    validateArray($params, ["id"]);

    $this->load->model("b4b/cart/Edit_model", "model");
    $res = $this->model->editNote($params);
    return json_response($res);
  }

  public function delete($id) {
    checkCompany();
    $params = [
      "b4buserid" => (int)headers("b4buserid") ?: null,
      "remoteb4buserid" => (int)headers("remoteb4buserid") ?: null,
      "id" => (int)$id ?: null,
      "deleted_at" => now()
    ];

    validateArray($params, ["id"]);

    $this->load->model("b4b/cart/Edit_model", "model");
    $res = $this->model->delete($params);
    return json_response($res);
  }

  public function deleteItems(){
    $auth_user = checkCompany(null, true);
    $params = [
      "b4buserid" => (int)headers("b4buserid") ?: null,
      "remoteb4buserid" => (int)headers("remoteb4buserid") ?: null,
      "cart_ids" => $this->custom_input->delete("cart_ids") ?: null,
      "deleted_at" => now(),
    ];
    validateArray($params, ["cart_ids"]);
    if(!is_array($params["cart_ids"])){
      $ids_cache_key = $params["cart_ids"];
      $ids = $this->cache->get($ids_cache_key);
      $params["cart_ids"] = $ids;
      validateArray($params, ["cart_ids"]);
      $this->cache->delete($ids_cache_key);
    }
    $this->load->model("b4b/cart/Edit_model", "model");
    $res = $this->model->deleteItems($params);
    return json_response($res);
  }
}
