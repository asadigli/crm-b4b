<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true);
  }

  public function editQuantity($id)
  {
    $params = [
      "id" => $id,
      "quantity" => $this->custom_input->put("quantity"),
    ];

    $this->load->model("cart/Edit_model", "model");
    $res = $this->model->editQuantity($params);

    return json_response($res);
  }

  public function editNote($id){
    $params = [
      "id" => $id,
      "comment" => $this->custom_input->put("comment"),
    ];

    $this->load->model("cart/Edit_model", "model");
    $res = $this->model->editNote($params);

    return json_response($res);
  }


  public function delete($id)
  {
    $params = [
      "id" => $id,
    ];

    $this->load->model("cart/Edit_model", "model");
    $res = $this->model->delete($params);

    return json_response($res);
  }

  public function deleteItems() {
    $cart_ids = $this->custom_input->delete("cart_ids");
    if(count($cart_ids) > 100){
      $ids_cache_key = "b4b_cart_delete_ids_" . md5(Auth::user()) . ".cache";
      $this->cache->save($ids_cache_key, $cart_ids, 5 * 60);
      $cart_ids = $ids_cache_key;
    }
    $params = [
      "cart_ids" => $cart_ids,
    ];

    $this->load->model("cart/Edit_model", "model");
    $res = $this->model->deleteItems($params);
    return json_response($res);
  }
}
