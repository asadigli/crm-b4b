<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

  function __construct() {
    parent::__construct();
    Auth::guest(true);
    $this->load->model("auth/Login_model","model");
  }

  function index() {
    $this->view([
      "auth/layouts/header",
      "auth/login",
      "auth/layouts/footer",
    ]);
  }

  function action(){
    $params = [
      "email" => $this->input->post("email"),
      "password" => $this->input->post("password"),
    ];

    $error = false;
    foreach (array_keys($params) as $key => $item) {
      if (!$params[$item]) {
        $error = true;
        Flash::set("error_{$item}",lang(ucfirst($item) . " is required"));
      }
    }
    if ($error) {
      redirect(path_local("auth/login"));
    }
    $res = $this->model->index($params);
    if (isset($res["code"])) {
      switch ($res["code"]) {
        case Status_codes::HTTP_NO_CONTENT:
          Flash::set("error",$res["message"]);
          redirect(path_local("auth/login"));
          break;
        case Status_codes::HTTP_BAD_REQUEST:
          Flash::set("error",$res["message"]);
          redirect(path_local("auth/login"));
          break;
        case Status_codes::HTTP_OK:
            Auth::setSessions($res["data"]);
            $su_roles = $this->config->item("su_roles");
            $redirect_back = isset($su_roles[$res["data"]["role"]]["default_page"]) ? $su_roles[$res["data"]["role"]]["default_page"] : "home";
            redirect(path_local($redirect_back));
          break;

        default:
          Flash::set("wrong",lang("Something went wrong"));
          redirect(path_local("auth/login"));
          break;
      }
    }
  }
}
