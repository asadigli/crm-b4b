<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wishlist_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  private $wtable = 'sa_wishlist';

  function addWishlist($user = null,$user_cookie_key = null,$product = null,$q = null){
    $quantity = $q ? $q : 1;
    $user_id = null;
    if (!$product) {
      $msg = "'product' information is not provided";
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
    }
    $product_query = $this->db->select("id")->from("sa_products")->where('id',$product)->where('deleted_at',NULL)->get();
    if ($product_query->num_rows() == 0) {
      $msg = $this->lang->line("Product not found");
      return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
    }
    if ($user) {
      $user_query = $this->db->select("id")->from("sa_users")->where('token',$user)->get();
      if ($user_query->num_rows() == 0) {
        $msg = $this->lang->line("User not found");
        return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
      }
      $user_id = $user_query->row()->id;
    }elseif(!$user && !$user_cookie_key){
      $msg = $this->lang->line("User information has not been provided");
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
    }


    if ($user) {
      $exist = $this->db->select("id")->from($this->wtable)
                            ->where('product_id',$product)->where('user_id',$user_id)
                                ->where('deleted_at',NULL)->get();
    }else{
      $exist = $this->db->select("id")->from($this->wtable)
                            ->where('product_id',$product)->where('user_key',$user_cookie_key)
                                ->where('deleted_at',NULL)->get();
    }
    $error_msg = 'Failed';
    if ($exist->num_rows() == 0) {
      $data = array(
        'user_id' => $user_id,
        'user_key' => $user_id ? null : $user_cookie_key,
        'product_id' => $product,
        'quantity' => $quantity
      );
      $done = $this->db->insert($this->wtable,$data);
      $msg = $done ? 'Wishlist added' : $error_msg;
    }else{
      $data = array('quantity' => $quantity);
      $this->db->where('id',$exist->row()->id);
      $done = $this->db->update($this->wtable,$data);
      $msg = $done ? 'Wishlist updated' : $error_msg;
    }
    $code = $done ? Status_codes::HTTP_OK : Status_codes::HTTP_INTERNAL_SERVER_ERROR;
    return rest_response($code,$msg,[]);
  }

  function deleteWishlist($user = null,$user_cookie_key = null,$w = null,$date){
    if ($user) {
      $user_query = $this->db->select("id")->from("sa_users")->where('token',$user)->get();
      if ($user_query->num_rows() == 0) {
        $msg = "User not found";
        return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
      }
      $user_id = $user_query->row()->id;
      $wishlist_query = $this->db->select("id")->from($this->wtable)->where("id",$w)
                                    ->where("user_id",$user_id)->where('deleted_at',NULL)->get();
    }elseif(!$user && $user_cookie_key){
      $wishlist_query = $this->db->select("id")->from($this->wtable)->where("id",$w)
                                    ->where("user_key",$user_cookie_key)->where('deleted_at',NULL)->get();
    }else{
      $msg = "'user' information is not provided";
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
    }

    if ($wishlist_query->num_rows() == 0) {
      $msg = "Wishlist not found";
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
    }
    $this->db->where('id',$wishlist_query->row()->id);
    $done = $this->db->update($this->wtable,['deleted_at' => $date]);
    $msg = "Wishlist deleted";
    $code = $done ? Status_codes::HTTP_OK : Status_codes::HTTP_INTERNAL_SERVER_ERROR;
    return rest_response($code,$msg,[]);
  }

  function getWishlist($user = null,$user_cookie_key = null,$limit = 1000){
    if ($user) {
      $user_query = $this->db->select("id")->from("sa_users")->where('token',$user)->get();
      if ($user_query->num_rows() == 0) {
        $msg = "User not found";
        return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
      }
      $user_id = $user_query->row()->id;
      $wishlist_query = $this->db->select("w.id as `key`,w.product_id,w.quantity,w.created_at as `date`,
                                            p.`PRODUCERCODE` as prod_code,p.`STATENAME` as prod_name,
                                              p.slug as prod_slug,p.`SALE_PRICE` as price")
                                  ->from($this->wtable." as w")
                                    ->join('sa_products p', 'w.product_id = p.id', 'left')
                                      ->where("w.user_id",$user_id)
                                        ->where('w.deleted_at',NULL)
                                          ->get();
    }elseif($user_cookie_key){
      $wishlist_query = $this->db->select("w.id as `key`,w.product_id,w.quantity,w.created_at as `date`,
                                            p.`PRODUCERCODE` as prod_code,p.`STATENAME` as prod_name,
                                              p.slug as prod_slug,p.`SALE_PRICE` as price")
                                  ->from($this->wtable." as w")
                                    ->join('sa_products p', 'w.product_id = p.id', 'left')
                                      ->where("w.user_key",$user_cookie_key)
                                        ->where('w.deleted_at',NULL)
                                          ->get();
    }else{
      $msg = $this->lang->line("User information has not been provided");
      return rest_response(Status_codes::HTTP_METHOD_NOT_ALLOWED,$msg,[]);
    }
    $wishlist = $wishlist_query->result();
    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),$wishlist);

  }

  function updateQuantity($user = null,$user_cookie_key = null,$wishlist = null,$quantity = null){
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
                                    ->from($this->wtable)
                                      ->where("id",$wishlist)->where("user_id",$user_id)
                                          ->where("deleted_at",null)->get();
    }else{
      $wishlist_exist = $this->db->select("id,product_id,quantity")
                                    ->from($this->wtable)
                                      ->where("id",$wishlist)->where("user_key",$user_cookie_key)
                                          ->where("deleted_at",null)->get();
    }

    if ($wishlist_exist->num_rows() == 0) {
      $msg = "Wishlist not found";
      return rest_response(Status_codes::HTTP_NOT_FOUND,$msg,[]);
    }

    $wse = $wishlist_exist->row();

    if ($wse->quantity == $quantity) {
      $msg = 'Wishlist not updated';
      $st = Status_codes::HTTP_NOT_MODIFIED;
    }else{
      $this->db->where("id",$wishlist);
      $done = $this->db->update($this->wtable,array('quantity' => $quantity));
      $msg = 'Wishlist quantity updated';
      $st = $done ? Status_codes::HTTP_OK : Status_codes::HTTP_CONFLICT;
    }
    return rest_response($st,$msg,[]);
  }


}
