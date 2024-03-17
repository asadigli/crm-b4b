<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    // $this->load->database();
  }

  function index(){
    $sdl_query = $this->db->select("value")
                            ->from("store_details")
                             ->where("user_id",3)
                              ->where("deleted_at",null)
                                ->where("type","slug")
                                  ->order_by('id','desc')
                                    ->limit(1)->get();
    $store_slug = $sdl_query->row();
    $this->response($store_slug);
  }

}
