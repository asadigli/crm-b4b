<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand extends MY_Controller{

  public function __construct(){
    parent::__construct();
    if (!$this->admin) {redirect(base_url('/'));}
  }

  public function brandControl(){
    $title = lang('Brand control');
    $this->view('admin/product/brand/add', [
      'title' => $title
    ]);
  }

  public function brandList(){
    $title = lang('Brand list');
    $this->view('admin/product/brand/list', ['title' => $title]);
  }

  public function liveList(){
    $params = [
      "limit"   => 1000000,
      "offset"  => (int)$this->input->get("offset"),
      "user"    => $this->auth_user,
      "version" => $this->input->get("version")
    ];
    $this->load->model("admin/Brand_model","model");
    $res = $this->model->liveList($params);
    return json_response($res);
  }

  public function addNew(){
    $image = $this->input->post("image");
    if(!empty($image)){
      $image_path = strip_tags(trim($this->input->post("image")));
      $ext = explode("/", substr($image_path, 0, strpos($image_path, ';base64')))[1];

      if (!in_array($ext, ["jpeg", "jpg", "png"])) {
          return json_response(rest_response(
              Status_codes::HTTP_BAD_REQUEST,
              lang("Only jpeg, jpg, png formats are acceptable")
          ));
      }

      $filename = md5((microtime())).".".$ext;
      $small_file_path = "/assets/landing/uploads/images/brands/small/" . $filename;
      $large_file_path = "/assets/landing/uploads/images/brands/large/" . $filename;

      $image = $small_file_path."|".$large_file_path;

      $small_file_path = APPPATH . ".." . $small_file_path;
      $large_file_path = APPPATH . ".." . $large_file_path;

      file_put_contents($small_file_path, file_get_contents($image_path));
      file_put_contents($large_file_path, file_get_contents($image_path));
    }
    $params = [
      "user_id"     => $this->auth_user,
      "name"        => strip_tags(trim($this->input->post("name"))),
      "description" => strip_tags(trim($this->input->post("description")))    ?: NULL,
      "order"       => $this->input->post("order")                            ?: STATUS_DEACTIVE,
      "image"       => $image                                                 ?: NULL,
      "status"      => $this->input->post("status") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
    ];

    validateArray($params, [
      "user_id",
      "name",
    ]);

    $this->load->model("admin/Brand_model","model");
    $res = $this->model->addNew($params);

    return json_response($res);
  }

  public function delete(){
    $params = [
      "brand"   => $this->input->post("brand"),
      "user"    => $this->auth_user,
      "date"    =>  $this->date,
    ];

    $this->load->model("admin/Brand_model","model");
    $res = $this->model->delete($params);
    return json_response($res);
  }

  public function inDetail($id){
    $params = [
      "id"  => $id,
    ];
    $this->load->model("admin/Brand_model","model");
    $res = $this->model->inDetail($params);
    if (!isset($res['code']) || $res['code'] !== 200) {
      $this->redError(404,base_url(),lang("Page not found"));
      die;
    }
    // fast_dump($res);
    $this->view("admin/product/brand/edit", [
      "id"    => $id,
      "data"  => $res["data"]
    ]);
  }

  public function update($id){
    $image = $this->input->post("image");
    if(!empty($image)){
       if (strpos($image, "data:image") === 0) {
           $image_path = strip_tags(trim($this->input->post("image")));
           $ext = explode("/", substr($image_path, 0, strpos($image_path, ';base64')))[1];

           if (!in_array($ext, ["jpeg", "jpg", "png"])) {
               return json_response(rest_response(
                   Status_codes::HTTP_BAD_REQUEST,
                   lang("Only jpeg, jpg, png formats are acceptable")
               ));
           }

           $filename = md5((microtime())).".".$ext;
           $small_file_path = "/assets/landing/uploads/images/brands/small/" . $filename;
           $large_file_path = "/assets/landing/uploads/images/brands/large/" . $filename;

           $image = $small_file_path."|".$large_file_path;

           $small_file_path = APPPATH . ".." . $small_file_path;
           $large_file_path = APPPATH . ".." . $large_file_path;

           file_put_contents($small_file_path, file_get_contents($image_path));
           file_put_contents($large_file_path, file_get_contents($image_path));
        }
    }

    $params = [
      "user_id"     => $this->auth_user,
      "id"          => $id,
      "name"        => $this->input->post("name"),
      "image"       => $image,
      "order"       => $this->input->post("order")                      ?: STATUS_DEACTIVE,
      "description" => $this->input->post("description")                ?: NULL,
      "status"      => $this->input->post("status") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE,
    ];

    if (!strpos($image, "|")) {
      unset($params["image"]);
    }
    
    validateArray($params, [
      "user_id",
      "name"
    ]);

    $this->load->model("admin/Brand_model","model");
    $res = $this->model->update($params);

    return json_response($res);
  }

  public function updateOrder(){
    $this->load->model("admin/Product_model","model");
    $params = [
      "user" => $this->auth_user,
      "list" => $this->input->post("list") ?: null,
    ];
    $res = $this->model->updateBrandOrder($params);
    return json_response($res);
  }
}
