<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require FCPATH . "modules/api/libraries/PHPMailer/src/Exception.php";
require FCPATH . "modules/api/libraries/PHPMailer/src/PHPMailer.php";
require FCPATH . "modules/api/libraries/PHPMailer/src/SMTP.php";

class Mail
{
  public static function send($params)
  {
    $mail = new PHPMailer(true);
    $CI = get_instance();

    $email_params = [
      "mail_real_email" => $CI->config->item("avh_tech_email"),
      "mail_real_email_password" => $CI->config->item("avh_tech_email_password"),
      "mail_to" => isset($params["mail_to"]) && $params["mail_to"] ? $params["mail_to"] : null,
      "mail_from" => isset($params["mail_from"]) && $params["mail_from"] ? $params["mail_from"]: null,
      "name_from" => isset($params["name_from"]) && $params["name_from"] ? $params["name_from"]: null,
      "subject" => isset($params["subject"]) && $params["subject"] ? $params["subject"]: null,
      "message" => isset($params["message"]) && $params["message"] ? $params["message"]: null,
      "attachments" => isset($params["attachments"]) && $params["attachments"] ? $params["attachments"]: null
    ];

    validateArray($email_params, ["mail_real_email", "mail_real_email_password", "mail_to", "mail_from", "message", "name_from"]);

    try {
        $mail->CharSet = 'UTF-8';
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.mail.ru';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $email_params["mail_real_email"];                     //SMTP username
        $mail->Password   = $email_params["mail_real_email_password"];                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($email_params["mail_real_email"], $email_params["mail_from"]);
        $mail->addAddress($email_params["mail_to"], "TEST-RECEPIENT");     //Add a recipient
        // $mail->addAddress('ellen@example.com');               //Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        if($email_params["attachments"]){
          $mail->addAttachment($email_params["attachments"], 'order.xlsx');    //Optional name
        }

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $email_params["subject"];
        $mail->Body    = $email_params["message"];
        $mail->AltBody = "TEST_ALT_BODY";

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  }
}
