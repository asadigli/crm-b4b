<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  public function index($params)
  {
    $warehouses_sql = "SELECT
                        `depo_id` as `id`,
                        `depo_name` as `name`
                      FROM `".local_table_name("warehouses")."`
                      WHERE `deleted_at` IS NULL
                      ";
    $warehouses_query = $this->local_db->query($warehouses_sql);
    if(!$warehouses_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No watehouses found")
      );
    }
    $warehouses = $warehouses_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $warehouses
    );
  }
}
