<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

  public function __construct() {
    parent::__construct();
    Auth::check(true,["manager", "admin","main_admin"],"profile");
  }

  public function index() {
    $this->extraCSS = [
      "../globals/css/libs/flot-chart-custom.css.css"
    ];
    $this->extraJS = [
      "js/helpers.js",
      "js/Charts.js",
      // "js/apexcharts.js",
      "../globals/js/libs/jquery.flot.js",
      "../globals/js/libs/jquery.flot.selection.js",
      "../globals/js/libs/jquery.flot.time.js",
      "../globals/js/libs/jquery.flot.resize.js",
      "js/pages/dashboard.js"
    ];

    if (Auth::checkRole(["admin", "main_admin"])) {
      $this->extraJS[] = "js/pages/caches/index.js";
    }

    $data = $this->cache->get("manager.dashboard_reports".md5(date("Y-m")).".cache") ?: [];

    $this->page_title = lang("Home page");
    $this->view([
      "layouts/header",
      "home",
      "layouts/footer",
    ],[
      "data" => $data
    ]);
  }

  function dashboardReports(){
    $params = [
      "is_developer"  => Auth::isDeveloper() || Auth::isMainDeveloper(),
      "year"          => $this->input->get("year"),
      "month_date"    => $this->input->get("month_date"),
      "warehouse"     => $this->input->get("warehouse") ?: 0,
    ];
    $this->load->model("Dashboard_model","model");
    $data = $this->model->dashboardReports($params);
    if (isset($data["data"])) {
      $this->cache->save("manager.dashboard_reports".md5($params["month_date"]).".cache", $data["data"], 3600 * 10);
    }
    return json_response($data);
  }

}
