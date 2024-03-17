<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("landing_db", true);
  }

  public function index($params){
    $contact_sql = "SELECT
                      `id`,
                      `name`,
                      `email`,
                      `phone`,
                      `subject`,
                      `body`,
                      `created_at` as `date`
                    FROM sa_contact
                    WHERE `deleted_at` IS NULL
                    AND `type` = '".TYPE_CONTACT."'
                    ORDER BY `id` DESC ";

    $contact_query = $this->local_db->query($contact_sql);

    if(!$contact_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No information")
      );
    }

    $contacts = $contact_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $contacts
    );
  }

  public function request($params){
    $contact_sql = "SELECT
                      `id`,
                      `name`,
                      `company_name`,
                      `email`,
                      `phone`,
                      `address`,
                      `city`,
                      `body`,
                      `created_at` as `date`
                    FROM sa_contact
                    WHERE `deleted_at` IS NULL
                    AND `type` = '".TYPE_PARTNER."'
                    ORDER BY `id` DESC ";

    $contact_query = $this->local_db->query($contact_sql);

    if(!$contact_query->num_rows()){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No information")
      );
    }

    $contacts = $contact_query->result_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $contacts
    );
  }

}
