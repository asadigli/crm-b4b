<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
  }

  function index() {
    Auth::check(true);
    Auth::unset();
    redirect(path_local("auth/login"));
  }


  function switchCustomer(){
    Auth::check(true);

    $params = [
      "customer_id"   => $this->input->post("customer_id")
    ];
    validateArray($params,["customer_id"]);
    $result = Auth::switchUser($params["customer_id"],false,true);

    if (isset($result["code"]) && $result["code"] === Status_codes::HTTP_OK) {
      setcookie(Auth::$last_customer, $params["customer_id"], time() + (86400 * 365 * 10), "/");
    }
    if (isset($result["data"])) {
      unset($result["data"]);
    }
    return json_response($result);
  }

}
