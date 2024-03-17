<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends MY_Controller {

  public function __construct() {
    parent::__construct();
    checkHeaders();
  }

  public function applicationLogs() {
    $body_limit = 500;

    $params = [
      "date" => $this->input->get("date"),
      "log_path" => $this->input->get("log_path"),
    ];

    validateArray($params,["date"]);

    if($params["log_path"] && !in_array($params["log_path"],special_codes("error_logs.paths"))) {
      $params["log_path"] = special_codes("error_logs.paths.application");
    }


    $year_folder = date("Y",strtotime($params["date"]));
    $month_folder = date("m",strtotime($params["date"]));

    $base_path = APPPATH ."logs";
    if($params["log_path"] && $params["log_path"] !== special_codes("error_logs.paths.application")) {
      $base_path = APPPATH . ".." . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . $params["log_path"] . DIRECTORY_SEPARATOR . "logs";
    }
    $log_path = $base_path . DIRECTORY_SEPARATOR . $year_folder . DIRECTORY_SEPARATOR . $month_folder .  DIRECTORY_SEPARATOR ."log-" . $params["date"] . ".php";

    if (file_exists($log_path)) {
      $data = file_get_contents($log_path);
      $data = substr($data, strpos($data, "\n") + 1);
      $data = str_replace("\n","",$data);
      $data = str_replace(["ERROR - ","DEBUG - ","INFO - ","ALL - "],[" |SPRTR| ERROR --> "," |SPRTR| DEBUG --> "," |SPRTR| INFO --> "," |SPRTR| ALL --> "],$data);
      $data_array = array_values(array_filter(explode(" |SPRTR| ",$data)));
      $data_array_list = [];
      foreach ($data_array as $key => $item) {
        $sub_list = explode(" --> ", $item, 10);
        $body = isset($sub_list[3]) ? substr(strip_tags($sub_list[3]), 0, $body_limit) . (strlen($sub_list[3]) > $body_limit ? "..." : "") : NULL;
        $title = isset($sub_list[2]) ? $sub_list[2] : NULL;
        if (isset($sub_list[2]) && !isset($sub_list[3])) {
          $title = "";
          $body = isset($sub_list[2]) ? substr(strip_tags($sub_list[2]), 0, $body_limit) . (strlen($sub_list[2]) > $body_limit ? "..." : "") : NULL;
        }
        $data_array_list[] = [
          "type" => isset($sub_list[0]) ? $sub_list[0] : null,
          "datetime" => isset($sub_list[1]) ? $sub_list[1] : null,
          "title" => $title,
          "body" => "<code>" . $body . "</code>",
        ];
      }
      return json_response(rest_response(
        Status_codes::HTTP_OK,
        lang("Success"),
        [
          "count" => count($data_array_list),
          "list" => array_reverse($data_array_list)
        ]
      ));
    }

    return json_response(rest_response(
      Status_codes::HTTP_NO_CONTENT,
      lang("No information found")
    ));
  }

  public function applicationLogsPaths()
  {
    // checkAdmin();
    $params = [];
    $sorts = special_codes("error_logs.paths");

    $sortingFilter = [];
    foreach ($sorts as $key => $item) {
      $sortingFilter[] = [
        "value" => $item,
        "name" => lang($item)
      ];
    }

    return json_response(rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "count" => count($sortingFilter),
        "list" => $sortingFilter,
      ]
    ));
  }
}
