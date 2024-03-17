<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
class Wishlist extends MY_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('product/Wishlist_model','model');
  }

  function addWishlist(){
    $user = $this->input->post('user_token');
    $user_cookie_key = $this->input->post('user_cookie_key');
    $product = $this->input->post('product');
    $quantity = $this->input->post('quantity');
    $response = $this->model->addWishlist($user,$user_cookie_key,$product,$quantity);
    $this->response($response);
  }

  function deleteWishlist(){
    $user = $this->input->get('user');
    $user_cookie_key = $this->input->get('user_cookie_key');
    $wishlist = $this->input->get('wishlist');
    $response = $this->model->deleteWishlist($user,$user_cookie_key,$wishlist,$this->date);
    $this->response($response);
  }

  function getWishlist(){
    $user = $this->input->get('user_token');
    $user_cookie_key = $this->input->get('user_cookie_key');
    $limit = $this->input->get('limit');
    $response = $this->model->getWishlist($user,$user_cookie_key,$limit);
    $this->response($response);
  }

  function updateQuantity(){
    $user = $this->input->post('user_token');
    $user_cookie_key = $this->input->post('user_cookie_key');
    $wishlist = $this->input->post('wishlist');
    $quantity = $this->input->post('quantity');
    $response = $this->model->updateQuantity($user,$user_cookie_key,$wishlist,$quantity);
    $this->response($response);
  }

}
