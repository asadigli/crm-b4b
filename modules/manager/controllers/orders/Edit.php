<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true,["admin","main_admin","warehouseman", "manager"]);
  }

  public function transferOrder($id)
  {
    $params = [
      "id" => $id,
      "group_id" => $this->custom_input->put("group_id"),
      "comment" => $this->custom_input->put("comment"),
      "order_detail_list" => $this->custom_input->put("order_detail_list"),
    ];

    $this->load->model("orders/Edit_model", "model");
    $res = $this->model->transferOrder($params);
    return json_response($res);
  }

  public function editStatus($id)
  {
    $params = [
      "id" => $id,
      "group_id" => $this->custom_input->put("group_id"),
      "status" => $this->custom_input->put("status"),
    ];

    $this->load->model("orders/Edit_model", "model");
    $res = $this->model->editStatus($params);
    return json_response($res);
  }

  public function orderDetailsCopyChecks()
  {

    $params = [
      "copy_check_indexes" => (array)$this->custom_input->put("copy_check_indexes")
    ];

    validateArray($params,["copy_check_indexes"]);

    $copy_check_indexes = [];
    $i = 1;
    foreach ($params["copy_check_indexes"] as $key => $item) {
      $copy_check_indexes[(int)$key] = $i;
      $i++;
    }

    setcookie(Auth::user()."_copy_check_indexes" , json_encode($copy_check_indexes), time() + (86400 * 360), "/");

    if(!isset($_COOKIE[Auth::user()."_copy_check_indexes"])) {
      return json_response(rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("Copy checks not updated")
      ));
    }

    return json_response(rest_response(
        Status_codes::HTTP_ACCEPTED,
        lang("Copy checks updated")
      ));
  }

  public function orderGroupOrder()
  {
    $params = [
      "group_id" => $this->custom_input->put("group_id")
    ];

    setcookie("group_id", $params["group_id"], time() + (86400 * 360), "/");

    if(!isset($_COOKIE["group_id"])) {
      return json_response(
        rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("Group order not updated")
          )
        );
    }

    return json_response(
      rest_response(
        Status_codes::HTTP_ACCEPTED,
        lang("Group order updated")
        )
      );
  }
}
