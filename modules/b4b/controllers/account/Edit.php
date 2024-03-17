<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
  }

  public function deleteExcelFile()
  {
    $params = [
      "excel_path" => $this->custom_input->delete("excel_path"),
    ];

    validateArray($params,["excel_path"]);
    $params["excel_path"] = APPPATH .".." . $params["excel_path"];

    if(!file_exists($params["excel_path"])) {
      return json_response(rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No excel path found")
      ));
    }

    unlink($params["excel_path"]);

    return json_response(rest_response(
      Status_codes::HTTP_OK,
      lang("Success")
    ));
  }
}
