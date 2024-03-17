<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Home extends MY_Controller{

  function __construct()
  {
    parent::__construct();
  }


  function errorPage(){
		$this->redError(404,NULL,lang('Page_not_found'));
	}

  function sendOrder(){
    $this->load->model("Form_model","model");
    $params = [
      "company_city" => $this->input->post("comp_city"),
      "company_name" => $this->input->post("comp_name"),
      "company_phone" => $this->input->post("comp_phone"),
      "company_email" => $this->input->post("comp_email"),
      "company_address" => $this->input->post("comp_address"),
      "user_email" => $this->input->post("comp_email"),
      "user_firstname" => $this->input->post("person_name"),
      "user_lastname" => $this->input->post("person_surname"),
      "user_mobile" => $this->input->post("comp_phone"),
      "comment" => $this->input->post("comment"),
      "sender_ip" => $_SERVER["REMOTE_ADDR"],
    ];

    foreach (array_keys($params) as $key => $item) {
      if (!$params[$item]) {
        return json_response($params[$item] . " cannot be empty");die;
      }
    }

    $data = $this->model->sendOrder($params);
    return json_response($data);
  }

  function sendContactMessage(){
    $this->load->model("Form_model","model");
    $params = [
      "message_body" => $this->input->post("message_body"),
      "message_title" => $this->input->post("message_title"),
      "person_email" => $this->input->post("person_email"),
      "person_name" => $this->input->post("person_name"),
      "person_number" => $this->input->post("person_number"),
    ];

    foreach (array_keys($params) as $key => $item) {
      if (!$params[$item]) {
        return json_response($params[$item] . " cannot be empty");die;
      }
    }

    $text = "<table style='width:100%'>";
    $text .=    "<tr style='border-bottom: 1px solid ##d0d0d0bd;'><td style='min-width: 60px;font-weight: 700;'>Şəxsin adı</td><td>{$params["person_name"]}</td></tr>";
    $text .=    "<tr style='border-bottom: 1px solid ##d0d0d0bd;'><td style='min-width: 60px;font-weight: 700;'>Əlaqə</td><td>{$params["person_number"]}</td></tr>";
    $text .=    "<tr style='border-bottom: 1px solid ##d0d0d0bd;'><td style='min-width: 60px;font-weight: 700;'>E-poçt</td><td>{$params["person_email"]}</td></tr>";
    $text .=    "<tr style='border-bottom: 1px solid ##d0d0d0bd;'><td style='min-width: 60px;font-weight: 700;'>Başlıq</td><td>{$params["message_title"]}</td></tr>";
    $text .=    "<tr style='border-bottom: 1px solid ##d0d0d0bd;'><td style='min-width: 60px;font-weight: 700;'>Mesaj</td><td>{$params["message_body"]}</td></tr>";
    $text .=    "<tr style='border-bottom: 1px solid ##d0d0d0bd;'><td style='min-width: 60px;font-weight: 700;'>IP</td><td>".(isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "")."</td></tr>";
    $text .= "</table>";

    $body = [
      "name_from" => "B2C Əlaqə",
      "mail_from" => $this->config->item("PWD_reset_email"),
      "mail_to" => $this->config->item("contact_emails"),
      "subject" => "B2C - ".$params["message_title"],
      "message" => $text,
      "source" => $this->config->item("project_name")
    ];

    $data = $this->model->sendContactMessage($body);
    return json_response($data);
  }

  function certificateList(){
    $page = [
      "title" => lang("Certificates")
    ];
    $this->load->view("pages/certificates",["page" => $page]);
  }

  function get_menu(){
    $this->load->model('Menu_model','my_menu_model');
    $data = $this->my_menu_model->menu();
    return json_response($data);
  }

  function index(){
    // $datas = $this->model->getCarBrands();
    $this->load->view('home');
  }

  function getCarBrands(){
    $this->load->model('product/Carbrand_model','model');
    $datas = $this->model->getCarBrands();
    return json_response($datas);
  }

  function getBrandYears(){
    $brand = $this->input->get('brand');
    $this->load->model('product/Carbrand_model','model');
    $datas = $this->model->getBrandYears($brand);
    return json_response($datas);
  }

  function getBrandModels(){
    $brand = $this->input->get('brand');
    $year = $this->input->get('year');
    $this->load->model('product/Carbrand_model','model');
    $datas = $this->model->getBrandModels($brand,$year);
    return json_response($datas);
  }

  function getBrandEngine(){
    $model = $this->input->get('model');
    $year = $this->input->get('year');
    $this->load->model('product/Carbrand_model','model');
    $datas = $this->model->getBrandEngine($model,$year);
    return json_response($datas);
  }

  function auto_update_catalog(){
    $token = $this->input->get('token');
    if ($token !== '8vKj30OBckzxYCF1Rw6xmcow') {
      return false;
    }
    $this->load->model('Menu_model','my_menu_model');
    $datas = $this->my_menu_model->auto_update_catalog();
    return true;
  }

  function get_currencies(){
    $this->load->model('Menu_model','my_menu_model');
    $data = $this->my_menu_model->getCurrencies();
    return json_response($data);
  }

}
