<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends MY_Controller{

  function __construct(){
    parent::__construct();
  }

  function captchaIsValid($key_public){
    if (ENVIRONMENT !== "production") {
      return true;
    }
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
    if (ENVIRONMENT !== "production" || $params["captcha_response"]) {
      if($this->captchaIsValid($params["captcha_response"])){
        $params = [
          "name" => $this->input->post("person_name"),
          "phone" => $this->input->post("person_number"),
          "email" => $this->input->post("person_email"),
          "subject" => $this->input->post("message_title"),
          "body" => $this->input->post("message_body"),
        ];
        $this->load->model("contact/Add_model", "contact_model");
        $data = $this->contact_model->index($params);
        $this->sendEmail([
          "message_title" => "Yeni 'təklif al'",
          "message" => [
            "name" => $this->input->post("person_name"),
            "phone" => $this->input->post("person_number"),
            "email" => $this->input->post("person_email"),
            "subject" => $this->input->post("message_title"),
            "message" => $this->input->post("message_body"),
          ]
        ]);
        if (isset($data["code"]) && $data["code"] === 200) {
          Flash::set("message",lang("You application send successfully"));
          Flash::set("type","success");
        } else {
          Flash::set("message",lang("Internal Server Error"));
          Flash::set("type","danger");
        }
        // return json_response($data, "no_auth");
      }
    } else {
      $this->session->set_flashdata("message","captcha validation fail");
      $this->session->set_flashdata("type","danger");
    }
    redirect(path_local("contact"));
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
                <tr><td>Ad</td><td>{$params["message"]["name"]}</td></tr>
                <tr><td>Telefon</td><td>{$params["message"]["phone"]}</td></tr>
                <tr><td>E-poçt</td><td>{$params["message"]["email"]}</td></tr>
                <tr><td>Mövzu</td><td>{$params["message"]["subject"]}</td></tr>
                <tr><td>Detallı</td><td>{$params["message"]["message"]}</td></tr>
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
