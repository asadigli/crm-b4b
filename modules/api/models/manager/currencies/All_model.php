<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  function index($params){
    $cached_currencies_query = $this->local_db->query("SELECT
                                                        `id`,
                                                        `remote_id`,
                                                        `name`,
                                                        `short_name`,
                                                        `main_name`,
                                                        `value`
                                                       FROM `".local_table_name("cached_currencies")."`
                                                       WHERE `deleted_at` IS NULL");
    if (!$cached_currencies_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Currency not found")
      );
    }

    $cached_currencies = $cached_currencies_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $cached_currencies
    );
  }

  public function list($params)
  {
    $currencies_sql = "SELECT
                        currencies.`id`,
                        currencies.`name`,
                        currencies.`main_name`
                      FROM `".local_table_name("cached_currencies")."` currencies
                      WHERE currencies.`deleted_at` IS NULL
                      ";
    $currencies_query = $this->local_db->query($currencies_sql);
    if(!$currencies_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Currency not found")
      );
    }
    $currencies = $currencies_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $currencies
    );
  }
}
