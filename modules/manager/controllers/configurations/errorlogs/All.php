<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    Auth::check(true,[]);
    $this->load->model("configurations/errorlogs/All_model", "model");
  }

  public function index()
  {
    $params = [
      "date" => $this->input->get("date")
    ];

    $this->extraJS = [
      "js/helpers.js",
      "js/pages/configurations/errorlogs/index.js",
    ];

    $this->page_title = lang("Application error logs");
    $this->view([
      "layouts/header",
      "configurations/errorlogs/index",
      "layouts/footer",
    ],[
      "url_params" => $params,
    ]);
  }

  public function live()
  {
    $params = [
      "date" => $this->input->get("date"),
      "log_path" => $this->input->get("log_path"),
    ];

    $res = $this->model->index($params);
    return json_response($res);
  }

  public function logsPathsLive() {
    $log_paths_cache_key = "log_paths_list.cache";
    $log_paths = $this->cache->get($log_paths_cache_key);
    if(!$log_paths) {
      $log_paths = $this->model->logsPaths([]);
      $this->cache->save($log_paths_cache_key, $log_paths, 3600 * 24);
    }
    return json_response($log_paths);
  }
}
