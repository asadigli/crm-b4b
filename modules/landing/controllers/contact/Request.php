<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends MY_Controller{

  function __construct(){
    parent::__construct();
  }

  function captchaIsValid($key_public){
    try {

      $url = 'https://www.google.com/recaptcha/api/siteverify';
      $data = [
        'secret'   => $this->config->item('captcha_secret_key'),
        'response' => $key_public,
        'remoteip' => $_SERVER['REMOTE_ADDR']
      ];

      $options = [
        'http' => [
          'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
          'method'  => 'POST',
          'content' => http_build_query($data)
        ]
      ];

      $context  = stream_context_create($options);
      $result = file_get_contents($url, false, $context);

      return json_decode($result)->success;
    } catch (Exception $e) {
      return null;
    }
  }
  /*
  *
  *
  */

  function send_request() {
    $params["captcha_response"] = trim($this->input->post('g-recaptcha-response'));
    if ($params["captcha_response"]) {
      if($this->captchaIsValid($params["captcha_response"])){
        $params = [
          "name" => $this->input->post("person_name") . " " . $this->input->post("person_surname"),
          "company_name" => $this->input->post("comp_name"),
          "phone" => $this->input->post("comp_phone"),
          "city" => $this->input->post("comp_city"),
          "email" => $this->input->post("comp_email"),
          "address" => $this->input->post("comp_address"),
          "body" => $this->input->post("comment"),
        ];
        $this->load->model("contact/Add_model", "contact_model");
        $data = $this->contact_model->request($params);

        $this->sendEmail([
          "message_title" => "Yeni 'təklif al'",
          "message" => [
            "name" => $this->input->post("person_name"),
            "surname" => $this->input->post("person_surname"),
            "company_name" => $this->input->post("comp_name"),
            "company_phone" => $this->input->post("comp_phone"),
            "company_city" => $this->input->post("comp_city"),
            "company_email" => $this->input->post("comp_email"),
            "company_address" => $this->input->post("comp_address"),
            "comment" => $this->input->post("comment"),
          ]
        ]);
        if (isset($data["code"]) && $data["code"] === 200) {
            $this->session->set_flashdata("message","Sent");
            $this->session->set_flashdata("type","success");
        } else {
            $this->session->set_flashdata("message","Failed");
            $this->session->set_flashdata("type","danger");
        }
        return json_response($data);
      }
    } else {
      $this->session->set_flashdata("message","captcha validation fail");
      $this->session->set_flashdata("type","danger");
    }
  }
  /*
  *
  *
  */

  function sendEmail($params) {
    $curl = curl_init();
    $title = isset($params["title"]) ? $params["title"] : 'CONTACT';
    $emails = $this->config->item("contact_emails");
    $table = "<table>
                <tr><td><b>Ad</b></td><td>{$params["message"]["name"]}</td></tr>
                <tr><td><b>Soyad</b></td><td>{$params["message"]["surname"]}</td></tr>
                <tr><td><b>Şirkət adı</b></td><td>{$params["message"]["company_name"]}</td></tr>
                <tr><td><b>Şirkət nömrəsi</b></td><td>{$params["message"]["company_phone"]}</td></tr>
                <tr><td><b>Şirkət E-poçtu</b></td><td>{$params["message"]["company_email"]}</td></tr>
                <tr><td><b>Şirkət ünvanı</b></td><td>{$params["message"]["company_address"]}</td></tr>
                <tr><td><b>Şəhər</b></td><td>{$params["message"]["company_city"]}</td></tr>
                <tr><td><b>Rəy</b></td><td>{$params["message"]["comment"]}</td></tr>
              </table>";

    $data = [
      "name_from" => "B2C Əlaqə",
      "mail_from" => $this->config->item("PWD_reset_email"),
      "mail_to" => $this->config->item("contact_emails"),
      "subject" => "B2C - ".$params["message_title"],
      "message" => $table,
      "source" => $this->config->item("project_name")
    ];

    $data_string = json_encode($data);
    $headers = array_merge($this->config->item("data_cisct_credentials"),['Content-Type: application/json']);
    $url = $this->config->item("data_cisct_url") . "services/email/send";

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_POSTFIELDS => $data_string,
      CURLOPT_HTTPHEADER => $headers,
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($response,true);
    return $response;
  }

}
?>
