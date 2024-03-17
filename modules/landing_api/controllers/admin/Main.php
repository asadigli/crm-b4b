<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('admin/Main_model','model');
  }

  function getConfigs(){
    $user = $this->input->get('user');
    $res = $this->model->getConfigs($user);
    $this->response($res);
  }

  function updateConfig(){
    $params = [
      "user" => $this->input->post("user"),
      "config_name" => $this->input->post("config_name"),
      "config_value" => $this->input->post("config_value")
    ];
    $res = $this->model->updateConfig($params);
    $this->response($res);
  }

  function homeProducts(){
    $user = $this->input->get('user');
    $data = $this->model->homeProducts($user);
    $this->response($data);
  }

  function checkProdCode(){
    $code = $this->input->get('code');
    $data = $this->model->checkProdCode($code);
    $this->response($data);
  }

  function addHP_product(){
    $params = [
      "user" => $this->input->post("user"),
      "code" => $this->input->post("code"),
      "product" => $this->input->post("product"),
      "reset" => $this->input->post("reset"),
    ];
    $data = $this->model->addHP_product($params);
    $this->response($data);
  }

  function updateOrder(){
    $user = $this->input->post('user');
    $product = $this->input->post('product');
    $order = $this->input->get('order');
    $data = $this->model->updateOrder($code);
    $this->response($data);
  }

  function updateProductOrders(){
    $params = [
      "user" => $this->custom_input->put('user'),
      "list" => $this->custom_input->put('list'),
    ];
    $data = $this->model->updateProductOrders($params);
    $this->response($data);
  }

}
