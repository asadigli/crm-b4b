<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("landing_db", true);
  }

  public function index($params){
    $this->local_db->insert("sa_contact", [
      "name" => $params["name"],
      "email" => $params["email"],
      "phone" => $params["phone"],
      "subject" => $params["subject"],
      "body" => $params["body"],
      "type" => TYPE_CONTACT
    ]);

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success")
    );
  }

  public function request($params){
    $this->local_db->insert("sa_contact", [
      "name" => $params["name"],
      "company_name" => $params["company_name"],
      "phone" => $params["phone"],
      "city" => $params["city"],
      "email" => $params["email"],
      "address" => $params["address"],
      "body" => $params["body"],
      "type" => TYPE_PARTNER
    ]);

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success")
    );
  }

}
