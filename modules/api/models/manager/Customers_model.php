<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",true);
  }

  function index(){
    $sql = "SELECT
                  `id`,
                  `remote_id`,
                  `name`
            FROM ".local_table_name("cached_customers")."
            WHERE `deleted_at` IS NULL
            AND `remote_is_active` = '".STATUS_ACTIVE."'";
    $query = $this->local_db->query($sql);

    if (!$query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No result")
      );
    }

    $customers = $query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $customers
    );
  }

}
