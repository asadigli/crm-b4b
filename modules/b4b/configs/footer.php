<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config["footer_links"] = [
  [
    "key" => "Customer services",
    "children" => [
      [
        "key" => "Brands",
        "link" => "https://test.com.loc/brand-list",
        "target" => "_blank"
      ],
      [
        "key" => "Promotions",
        "link" => "https://test.com.loc/promotions",
        "target" => "_blank"
      ],
      [
        "key" => "Certificates",
        "link" => "https://test.com.loc/certificates",
        "target" => "_blank"
      ],
      [
        "key" => "Products",
        "link" => "https://test.com.loc/search",
        "target" => "_blank"
      ]
    ]
  ],
  [
    "key" => "About us",
    "children" => [
      [
        "key" => "About us",
        "link" => "https://test.com.loc/about",
        "target" => "_blank"
      ],
      [
        "key" => "News",
        "link" => "https://test.com.loc/news-list",
        "target" => "_blank"
      ],
      [
        "key" => "Contact us",
        "link" => "https://test.com.loc/contact",
        "target" => "_blank"
      ],
      [
        "key" => "FAQ",
        "link" => "https://test.com.loc/faq",
        "target" => "_blank"
      ]
    ]
  ],
];

$token = base64_encode("test.com-loc");

$config["copyright"] = [
  "start_year" => "2019",
  "current_year" => date("Y"),
  "company_name" => "Author company",
  "link" => "https://test.author.com?redirect-from={$token}",
  "text" => "_ Powered by",
  "project_name" => ""
];
