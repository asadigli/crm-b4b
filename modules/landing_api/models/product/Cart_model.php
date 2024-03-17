<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_model extends CI_Model{

  function __construct(){
    parent::__construct();
  }

  private $ctb = 'sa_cart';

  function add($user = null,$user_cookie_key = null,$product = null,$q = null){
    $quantity = $q ? $q : 1;
    $user_id = null;
    if (!$product) {
      $msg = "'product' information is not provided";
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
    }
    if ($user) {
      $user_query = $this->db->select("id")->from("sa_users")->where('token',$user)->get();
      if ($user_query->num_rows() == 0) {
        $msg = "User not found";
        return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
      }
      $product_query = $this->db->select("id")->from("sa_products")->where('id',$product)->where('deleted_at',NULL)->get();
      if ($product_query->num_rows() == 0) {
        $msg = $this->lang->line("Product not found");
        return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
      }
      $user_id = $user_query->row()->id;
    }elseif(!$user && !$user_cookie_key){
      $msg = "'user' information is not provided";
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
    }
    if ($user) {
      $exist = $this->db->select("id")
                          ->from($this->ctb)
                            ->where('product_id',$product)
                              ->where('user_id',$user_id)
                                ->where('deleted_at',NULL)
                                  ->get();
    }else{
      $exist = $this->db->select("id")
                          ->from($this->ctb)
                            ->where('product_id',$product)
                              ->where('user_key',$user_cookie_key)
                                ->where('deleted_at',NULL)
                                  ->get();
    }
    $error_msg = 'Failed';
    if ($exist->num_rows() == 0) {
      $data = array(
        'user_id' => $user_id,
        'user_key' => $user_id ? null : $user_cookie_key,
        'product_id' => $product,
        'quantity' => $quantity
      );
      $done = $this->db->insert($this->ctb,$data);
      $msg = $done ? 'Cart added' : $error_msg;
    }else{
      $data = array('quantity' => $quantity);
      $this->db->where('id',$exist->row()->id);
      $done = $this->db->update($this->ctb,$data);
      $msg = $done ? 'Cart updated' : $error_msg;
    }
    $code = $done ? Status_codes::HTTP_OK : Status_codes::HTTP_INTERNAL_SERVER_ERROR;

    return rest_response($code,$msg,[]);
  }

  function delete($user = null,$user_cookie_key = null,$w = null,$date){
    if ($user) {
      $user_query = $this->db->select("id")->from("sa_users")->where('token',$user)->get();
      if ($user_query->num_rows() == 0) {
        $msg = "User not found";
        return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
      }
      $cart_query = $this->db->select("id")->from($this->ctb)->where("id",$w)->where("user_id",$user_query->row()->id)->where('deleted_at',NULL)->get();
    }elseif(!$user && $user_cookie_key){
      $cart_query = $this->db->select("id")->from($this->ctb)->where("id",$w)->where("user_key",$user_cookie_key)->where('deleted_at',NULL)->get();
    }else{
      $msg = "'user' information is not provided";
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
    }
    if ($cart_query->num_rows() == 0) {
      $msg = "Cart not found";
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
    }
    $this->db->where('id',$cart_query->row()->id);
    $done = $this->db->update($this->ctb,['deleted_at' => $date]);
    $msg = "Cart deleted";
    $code = $done ? Status_codes::HTTP_OK : Status_codes::HTTP_INTERNAL_SERVER_ERROR;
    return rest_response($code,$msg,[]);
  }

  function getList($user = null,$user_cookie_key = null,$limit = 1000){
    $user_id = null;
    if ($user) {
      $user_query = $this->db->select("id")->from("sa_users")->where('token',$user)->get();
      if ($user_query->num_rows() == 0) {
        $msg = "User not found";
        return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
      }
      $user_id = $user_query->row()->id;
      $cart_query = $this->db->select("w.id as `key`,w.product_id,w.quantity,w.created_at as `date`,
                                        p.`PRODUCERCODE` as prod_code,p.`STATENAME` as prod_name,
                                          p.slug as prod_slug,p.`SALE_PRICE` as price")
                                  ->from($this->ctb." as w")
                                    ->join('sa_products p', 'w.product_id = p.id', 'left')->where("w.user_id",$user_id)
                                        ->where('w.deleted_at',NULL)->order_by("w.id","desc")->get();
    }elseif(!$user && $user_cookie_key){
      $cart_query = $this->db->select("w.id as `key`,w.product_id,w.quantity,w.created_at as `date`,
                                        p.`PRODUCERCODE` as prod_code,p.`STATENAME` as prod_name,
                                          p.slug as prod_slug,p.`SALE_PRICE` as price")
                                  ->from($this->ctb." as w")
                                    ->join('sa_products p', 'w.product_id = p.id', 'left')->where("w.user_key",$user_cookie_key)
                                      ->where('w.deleted_at',NULL)->order_by("w.id","desc")->get();
    }else{
      $msg = "'user' information is not provided";
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
    }
    $cart = $cart_query->result();
    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),$cart);
  }

  function addFromWishlist($user = null,$user_cookie_key = null,$wishlist = null,$quantity = null){
    $quantity = $quantity ? $quantity : 1;$user_id = null;
    if (!$wishlist) {
      $msg = "'wishlist' information is not provided";
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
    }

    if ($user) {
      $user_query = $this->db->select("id")->from("sa_users")->where('token',$user)->get();
      if ($user_query->num_rows() == 0) {
        $msg = "User not found";
        return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
      }
      $user_id = $user_query->row()->id;
    }elseif(!$user && !$user_cookie_key){
      $msg = "'user' information is not provided";
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
    }


    if ($user) {
      $wishlist_exist = $this->db->select("id,product_id,quantity")
                                    ->from("wishlist")->where("id",$wishlist)
                                      ->where("user_id",$user_id)
                                        ->where("deleted_at",null)->get();
    }else{
      $wishlist_exist = $this->db->select("id,product_id,quantity")
                                    ->from("wishlist")->where("id",$wishlist)
                                      ->where("user_key",$user_cookie_key)
                                        ->where("deleted_at",null)->get();
    }

    if ($wishlist_exist->num_rows() == 0) {
      $msg = "Wishlist not found ".$user_cookie_key;
      return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
    }

    $product = $wishlist_exist->row()->product_id;

    if ($user) {
      $cart_exist = $this->db->select("id,product_id,quantity")
                                    ->from($this->ctb)->where("product_id",$product)
                                        ->where("user_id",$user_id)
                                          ->where("deleted_at",null)->get();
    }else{
      $cart_exist = $this->db->select("id,product_id,quantity")
                                    ->from($this->ctb)->where("product_id",$product)
                                        ->where("user_key",$user_cookie_key)
                                          ->where("deleted_at",null)->get();
    }

    if ($cart_exist->num_rows() == 0) {
      $data = array(
        'user_id' => $user_id,
        'user_key' => $user_id ? null : $user_cookie_key,
        'product_id' => $product,
        'quantity' => $quantity
      );
      $done = $this->db->insert($this->ctb,$data);
      $msg = $done ? 'Product added to cart' : $error_msg;
      $st = Status_codes::HTTP_OK;
    }elseif($cart_exist->num_rows() > 0 && $cart_exist->row()->quantity == $quantity){
      $msg ='Product is already is in cart';
      $st = Status_codes::HTTP_CONFLICT;
    }else{
      $data = ['quantity' => $quantity];
      $this->db->where('id',$cart_exist->row()->id);
      $done = $this->db->update($this->ctb,$data);
      $msg = $done ? 'Cart updated' : $error_msg;
      $st = Status_codes::HTTP_OK;
    }

    return rest_response($st,$msg,[]);
  }

  function updateQuantity($user = null,$user_cookie_key = null,$cart = null,$quantity = null){
    $quantity = $quantity ? $quantity : 1;
    $user_id = null;
    if (!$cart) {
      $msg = "'cart' information is not provided";
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
    }
    if ($user) {
      $user_query = $this->db->select("id")->from("sa_users")->where('token',$user)->get();
      if ($user_query->num_rows() == 0) {
        $msg = "User not found";
        return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
      }
      $user_id = $user_query->row()->id;
      $cart_exist = $this->db->select("id,product_id,quantity")
                                    ->from($this->ctb)
                                      ->where("id",$cart)->where('user_id',$user_id)
                                        ->where("deleted_at",null)->get();
    }elseif(!$user && $user_cookie_key){
      $cart_exist = $this->db->select("id,product_id,quantity")
                                    ->from($this->ctb)
                                      ->where("id",$cart)->where('user_key',$user_cookie_key)
                                        ->where("deleted_at",null)->get();
    }else{
      $msg = "'user' information is not provided";
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
    }

    if ($cart_exist->num_rows() == 0) {
      $msg = "Cart not found";
      return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
    }
    $wse = $cart_exist->row();
    if ($wse->quantity == $quantity) {
      $msg = 'Cart not updated';
      $st = Status_codes::HTTP_NOT_MODIFIED;
    }else{
      $this->db->where("id",$cart);
      $done = $this->db->update($this->ctb,array('quantity' => $quantity));
      $msg = 'Cart quantity updated';
      $st = $done ? Status_codes::HTTP_OK : Status_codes::HTTP_CONFLICT;
    }
    return rest_response($st,$msg,[]);
  }


}
