<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config extends MY_Controller{

  function __construct(){
    parent::__construct();
    $this->load->model('admin/Config_model','model');
    if (!$this->admin) {
      redirect(base_url('/'));
    }
  }
  // 
  // function getList(){
  //   if(!$this->developer){return json_response([]);die;};
  //   $user = $this->input->get("user");
  //   $data = getConfig('footer');
  //   return json_response($data);
  // }
  //
  // function updateFooterView(){
  //   $data = getConfig("footer");
  //   $this->view("admin/configurations/footer_config",["data" => $data]);
  // }
  //
  // function updateFooterAction(){
  //   $path = APPPATH.'config/jsons/footer.json';
  //   $key = $this->input->post('key');
  //   $status = $this->input->post('status');
  //   $datas = getConfig('footer');
  //   $new_data = [];
  //   foreach (array_keys($datas) as $index => $item) {
  //     $children = [];
  //     if ($datas[$item]["children"]) {
  //       foreach (array_keys($datas[$item]["children"]) as $sub_index => $sub_item) {
  //         if ($key === $sub_item) {
  //           $children = array_merge($children,[$sub_item => [
  //             "name" => $datas[$item]["children"][$sub_item]["name"],
  //             "status" => (bool)$status
  //           ]]);
  //         }else{
  //           $children = array_merge($children,[$sub_item => [
  //             "name" => $datas[$item]["children"][$sub_item]["name"],
  //             "status" => $datas[$item]["children"][$sub_item]["status"]
  //           ]]);
  //         }
  //       }
  //     }
  //     if ($key === $item) {
  //       $new_list = [
  //         $item => [
  //           "name" => $datas[$item]["name"],
  //           "status" => (bool)$status,
  //           "children" => $children
  //         ]
  //       ];
  //     }else{
  //       $new_list = [
  //         $item => [
  //           "name" => $datas[$item]["name"],
  //           "status" => $datas[$item]["status"],
  //           "children" => $children
  //         ]
  //       ];
  //     }
  //     $new_data = array_merge($new_data,$new_list);
  //   }
  //   $newJsonString = json_encode($new_data);
  //   count($new_list) ? file_put_contents($path, $newJsonString) : "";
  //   return json_response($new_data);
  // }


  function aboutFaqControl(){
    $title = lang('About & FAQ');
    $this->view("admin/configurations/about_faq", ["title" => $title]);
  }
}
