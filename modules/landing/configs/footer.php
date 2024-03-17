<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$token = base64_encode("customer-crm");

$config["copyright"] = [
    "start_year" => "2000",
    "current_year" => date("Y"),
    "company_name" => "Author",
    "link" => "https://test.loc?redirect-from={$token}",
    "text" => "_ Powered by",
    "project_name" => ""
];
