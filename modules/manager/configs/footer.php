<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config["footer_links"] = [
  [
    "key" => "Customer services",
    "children" => [
      [
        "key" => "Brands",
        "link" => "http://test.loc/brand-list",
        "target" => "_blank"
      ],
      [
        "key" => "Promotions",
        "link" => "http://test.loc/promotions",
        "target" => "_blank"
      ],
      [
        "key" => "Certificates",
        "link" => "http://test.loc/certificates",
        "target" => "_blank"
      ],
      [
        "key" => "Products",
        "link" => "http://test.loc/search",
        "target" => "_blank"
      ]
    ]
  ],
  [
    "key" => "About us",
    "children" => [
      [
        "key" => "About us",
        "link" => "http://test.loc/about",
        "target" => "_blank"
      ],
      [
        "key" => "News",
        "link" => "http://test.loc/news-list",
        "target" => "_blank"
      ],
      [
        "key" => "Contact us",
        "link" => "http://test.loc/contact",
        "target" => "_blank"
      ],
      [
        "key" => "FAQ",
        "link" => "http://test.loc/faq",
        "target" => "_blank"
      ]
    ]
  ],
];

$token = base64_encode("customer");

$config["copyright"] = [
  "start_year" => "2000",
  "current_year" => date("Y"),
  "company_name" => "Author",
  "link" => "http://test.loc?redirect-from={$token}",
  "text" => "_ Powered by",
  "project_name" => ""
];
