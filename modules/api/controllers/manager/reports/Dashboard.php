<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

  function __construct() {
    parent::__construct();
    checkHeaders();
  }



  /*
  *
  */
  function index() {
    $this->local_db = $this->load->database("local_db",true);

    $params = [
      "month_date"      => $this->input->get("month_date")    ?: NULL,
      "warehouse"       => $this->input->get("warehouse")     ?: "0",
      "is_developer"    => $this->input->get("is_developer"),
      "local_db"        => $this->local_db,
      "warehouse_list"  => $this->config->item("warehouse_list")
    ];

    if ($params["month_date"]) {
      $params["month_date"] = date("Y",strtotime($params["month_date"])) === date("Y") && date("m",strtotime($params["month_date"])) === date("m") ? date("Y-m-d H:i:s") : date("Y-m-t", strtotime($params["month_date"]));
    } else {
      $params["month_date"] = date("Y-m-d H:i:s");
    }

    $params["year"] = date("Y",strtotime($params["month_date"]));

    $params["warehouse"] = !is_null($params["warehouse"]) && $params["warehouse"] !== "" && in_array((int)$params["warehouse"],array_keys($params["warehouse_list"])) ? $params["warehouse"] : NULL;

    $b4b = $this->b4b($params);
    $b4b = isset($b4b["data"]) ? $b4b["data"] : [];
    // return json_response(rest_response(100,"OK 1"));

    $sales = $this->sales($params);
    $sales = isset($sales["data"]) ? $sales["data"] : [];
    // return json_response(rest_response(100,"OK 2"));

    $reports = $this->annualReports($params);
    $reports = isset($reports["data"]) ? $reports["data"] : [];
    // return json_response(rest_response(100,"OK 3"));

    $onlines = $this->onlines($params);
    $onlines = isset($onlines["data"]) ? $onlines["data"] : [];

    return json_response(rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "amount"    => $sales,
        "reports"   => $reports,
        "b4b"       => $b4b,
        "onlines"   => $onlines
      ]
    ));
  }




  /*
  *
  */
  function sales($params = []) {
    $params = [
      "year"          => $params["year"],
      // "month_date"    => $params["month_date"],
      "month"         => date("m",strtotime($params["month_date"])),
      "day"           => date("d",strtotime($params["month_date"])),
      "is_developer"  => isset($params["is_developer"]) ? $params["is_developer"] : NULL,
      "local_db"      => $params["local_db"]
    ];

    $this->load->model("manager/reports/Sales_model","sales_model");
    $res = $this->sales_model->index($params);

    return $res;
  }



  /*
  *
  */
  function annualReports($params = []) {
    $params = [
      "year"        => $params["year"],
      "month_date"  => $params["month_date"],
      "warehouse"   => $params["warehouse"],
      "local_db"    => $params["local_db"]
    ];

    $this->load->model("manager/reports/Sales_model","sales_model");
    $res = $this->sales_model->annualReports($params);

    return $res;
  }




  /*
  *
  */
  function b4b($params = []) {
    $params = [
      "year"          => $params["year"],
      "month_date"    => $params["month_date"],
      "month"         => date("m",strtotime($params["month_date"])),
      "day"           => date("d",strtotime($params["month_date"])),
      "is_developer"  => isset($params["is_developer"]) ? $params["is_developer"] : NULL,
      "local_db"      => $params["local_db"]
    ];

    $this->load->model("manager/reports/B4b_model","b4b_model");
    $res = $this->b4b_model->index($params);

    return $res;
  }



  /*
  *
  */
  function onlines($params = []) {
    $from_time = strtotime(date("Y-m-01"));
    $to_time = strtotime("now");
    $time_list = [];
    for ($i = $from_time; $i < $to_time; $i = $i + 60 * 60) {
      $time = date("Y-m-d H:00:00",$i);
      $time_list[] = $time;
    }

    $params = [
      "is_developer" => isset($params["is_developer"]) ? $params["is_developer"] : NULL,
      "time_list" => $time_list,
      "local_db" => $params["local_db"]
    ];


    $this->load->model("manager/reports/Onlines_model","onlines_model");
    $res = $this->onlines_model->index($params);

    return $res;
  }

}
