<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  function action($params){
    escapeAllKeys($params,null,$this->local_db);

    $sql = "SELECT
                  det.`company_id`,
                  det.`product_name`,
                  det.`brand_code`,
                  det.`brand`,
                  det.`amount`,
                  det.`description`,
                  det.`OEM`,
                  det.`quantity`,
                  acc.`invoice_code`
            FROM ".local_table_name("cached_invoices")." det
            LEFT JOIN ".local_table_name("cached_customer_accounts")." acc ON acc.invoice_id = det.remote_invoice_id
            WHERE det.remote_id = {$params["id"]} ";

    $query = $this->local_db->query($sql);
    if (!$query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Product details not found")
      );
    }

    $res = $query->row_array();

    if ($params["quantity"] > $res["quantity"]) {
      return rest_response(
        Status_codes::HTTP_CONFLICT,
        lang("Quantity_max_warning")
      );
    }

    $insert_list = [
      "reverse_user_id" => $res["company_id"],
      "reverse_invoice" => $res["invoice_code"],
      "reverse_quantity" => $params["quantity"],
      "reverse_name" => $res["product_name"],
      "reverse_code" => $res["brand_code"],
      "reverse_specode3" => $res["OEM"],
      "reverse_specode" => $res["brand"],
      "reverse_specode2" => $res["description"],
      "reverse_price" => $res["amount"],
      "reverse_description" => $params["description"],
    ];

    $this->local_db->insert(local_table_name("b4b_returns"),$insert_list);

    return rest_response(
      Status_codes::HTTP_CREATED,
      lang("Return added")
    );

  }

}
