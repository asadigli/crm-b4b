<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
class Cart extends MY_Controller{

  function __construct()
  {
    parent::__construct();
    $this->load->model('product/Cart_model','model');
  }

  function add(){
    $user = $this->input->post('user_token');
    $user_cookie_key = $this->input->post('user_cookie_key');
    $product = $this->input->post('product');
    $quantity = $this->input->post('quantity');
    $res = $this->model->add($user,$user_cookie_key,$product,$quantity);
    $this->response($res);
  }

  function updateQuantity(){
    $user = $this->input->post('user_token');
    $user_cookie_key = $this->input->post('user_cookie_key');
    $cart = $this->input->post('cart');
    $quantity = $this->input->post('quantity');
    $response = $this->model->updateQuantity($user,$user_cookie_key,$cart,$quantity);
    $this->response($response);
  }

  function addFromWishlist(){
    $user = $this->input->post('user_token');
    $user_cookie_key = $this->input->post('user_cookie_key');
    $wishlist = $this->input->post('wishlist');
    $quantity = $this->input->post('quantity');
    $response = $this->model->addFromWishlist($user,$user_cookie_key,$wishlist,$quantity);
    $this->response($response);
  }

  function delete(){
    $cart = $this->input->get('cart');
    $user = $this->input->get('user');
    $user_cookie_key = $this->input->get('user_cookie_key');
    $response = $this->model->delete($user,$user_cookie_key,$cart,$this->date);
    $this->response($response);
  }

  function getList(){
    $user = $this->input->get('user_token');
    $user_cookie_key = $this->input->get('user_cookie_key');
    $limit = $this->input->get('limit');
    $response = $this->model->getList($user,$user_cookie_key,$limit);
    $this->response($response);
  }

}
