<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("manager/b4b/entries/Edit_model", "model");

  }

  function index(){
    $params = [
      "creator_id" => headers("userid") ?: null,
      "company_id" => $this->custom_input->put("company_id"),
      "avatar" => $this->custom_input->put("avatar") ?: null,
      "password" => $this->custom_input->put("password") ?: null,
    ];

    validateArray($params,["company_id"]);

    $res = $this->model->index($params);
    return json_response($res);

  }

  function entryLimit($id) {
    $params = [
      "creator_id"  => headers("userid") ?: null,
      "company_id"  => $id,
      "entry_count" => $this->custom_input->put("limit") ?: null,
      "reset"       => $this->custom_input->put("reset") ?: null,
    ];
    validateArray($params,["company_id"]);

    $res = $this->model->entryLimit($params);
    if (isset($res["code"]) && $res["code"] === Status_codes::HTTP_ACCEPTED) {
      // if (file_exists(FCPATH . "/assets/updates/".$params["company_id"].".status_check.json")) {
      //
      // }
      // $fh = fopen(FCPATH . "assets/updates/".md5($params["company_id"]).".status_check.json", 'w') or die("Can't create file");
      // if ($fh) {
      //   file_put_contents(FCPATH . "assets/updates/".md5($params["company_id"]).".status_check.json", json_encode([
      //     "status" => Status_codes::HTTP_FOUND
      //   ]));
      // }

    }
    return json_response($res);
  }

  function delete($id){
    $params = [
      "company_id" => $id
    ];
    validateArray($params,["company_id"]);

    checkCompany($params["company_id"],false,false,true);

    $res = $this->model->delete($params);

    return json_response($res);

  }

  function stockShow($company_id){
    $params = [
      "admin_id" => headers("userid"),
      "company_id" => $company_id,
      "stock_show" => $this->custom_input->put("stock_show") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE
    ];
    validateArray($params,["company_id"]);

      checkCompany($params["company_id"],false,false,true);

    $res = $this->model->stockShow($params);

    return json_response($res);
  }

  function storeActive($company_id){
    $params = [
      "admin_id" => headers("userid"),
      "company_id" => $company_id,
      "is_store_active" => $this->custom_input->put("is_store_active") === STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVE
    ];
    validateArray($params,["company_id"]);

    checkCompany($params["company_id"],false,false,true);

    $res = $this->model->storeActive($params);

    return json_response($res);
  }

  function detail($company_id){
    $params = [
      "admin_id" => headers("userid"),
      "company_id" => $company_id,
      "key" => $this->custom_input->put("key"),
      "value" => $this->custom_input->put("value"),
    ];

    validateArray($params,["company_id","key","value"]);

    checkCompany($params["company_id"],false,false,true);

    $res = $this->model->detail($params);

    return json_response($res);
  }

  function password($company_id){
    $params = [
      "admin_id" => headers("userid"),
      "company_id" => $company_id,
      "email" => $this->custom_input->put("email"),
      "password" => $this->custom_input->put("password"),
    ];

    validateArray($params,["company_id","password"]);

    checkCompany($params["company_id"],false,false,true);
    $res = $this->model->password($params);

    return json_response($res);
  }

  function addCustomer($id){
    $params = [
      "admin_id" => headers("userid"),
      "id" => $id,
      "customers" => $this->custom_input->put("customers"),
    ];
    validateArray($params,["id","customers"]);

    checkCompany($params["id"],false,false,true);

    $params["customers"] = is_array($params["customers"]) ? $params["customers"] : explode(",",$params["customers"]);
    $res = $this->model->addCustomer($params);

    return json_response($res);
  }


  function editProperties($id){

    $params = [
      "id" => $id,
      "person_name" => $this->custom_input->put("person_name"),
      "person_surname" => $this->custom_input->put("person_surname"),
      "company_address" => $this->custom_input->put("address"),
      "company_curator_id" => $this->custom_input->put("curator_id"),
      "company_city_id" => $this->custom_input->put("city_id"),
      "company_depo_id" => $this->custom_input->put("depo_id"),
    ];

    validateArray($params,["id"]);

    checkCompany($params["id"],false,false,true);

    $res = $this->model->editProperties($params);

    return json_response($res);
  }

  public function editIsBlocked($id)
  {

    $params = [
      "id" => (int)$id,
      "is_blocked" => $this->custom_input->put("is_blocked") === STATUS_ACTIVE ?  STATUS_ACTIVE : STATUS_DEACTIVE
    ];
    validateArray($params,["id"]);
    
    checkCompany($params["id"],false,false,true);
    $res = $this->model->editIsBlocked($params);
    return json_response($res);
  }
}
