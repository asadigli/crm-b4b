<?php
defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('memory_limit','-1');
ini_set('max_execution_time', 9000); //300 seconds = 5 minutes
ini_set('max_allowed_packet', '12000006M');

class Add_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  public function index($params)
  {
    escapeAllKeys($params,NULL,$this->local_db);

    $excel_extensions = $this->config->item("excel_extensions");
    $ext = explode("data:",substr($params["excel_file_64"], 0, strpos($params["excel_file_64"], ';base64')))[1];

    if( $excel_extensions && !in_array($ext, $excel_extensions)){
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Only excel formats are acceptable")
      );
    }

    $check_currency_sql = "SELECT
                            `id`,
                            `main_name`
                          FROM `".local_table_name("cached_currencies")."` currencies
                          WHERE currencies.`id` = {$params["currency_id"]}
                          AND currencies.`deleted_at` IS NULL
                          ";
    $check_currency_query = $this->local_db->query($check_currency_sql);
    if(!$check_currency_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Currency not found")
      );
    }
    $check_currency = $check_currency_query->row_array();

    $check_brands_sql = "SELECT
                              `id`,
                              `name`
                            FROM `".local_table_name("cached_product_brands")."`
                            WHERE `deleted_at` IS NULL
                            AND `id` = {$params["brand_id"]}";
    $check_brands_query = $this->local_db->query($check_brands_sql);
    if(!$check_brands_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("No brand found")
      );
    }
    $check_brand = $check_brands_query->row_array();

    $this->load->library("PHPExcel");
    $excel_64 = explode("base64,",$params["excel_file_64"])[1];

    $file = base64_decode($excel_64);

    $temp_file_path =  tempnam(sys_get_temp_dir(), "bpm_products_import_temp_file.".md5(uniqid()));
    file_put_contents($temp_file_path, $file);

    $object = @PHPExcel_IOFactory::load($temp_file_path);

    foreach ($object->getWorksheetIterator() as $key => $worksheet) {
      if(!$worksheet) {
        return rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("No excel page found")
        );
      }

      $highestRow = $worksheet->getHighestRow();
      $product_list = [];
      $product_tokens = [];
      $description_names = [];
      $brand_name = $check_brand["name"];


      for($row = 2; $row <= $highestRow; $row++) {
        $brand_code                       = trim(addslashes($worksheet->getCellByColumnAndRow(0, $row)->getValue()));
        $reference_information            = trim(addslashes($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
        $description_type                 = trim(addslashes($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
        $OEM                              = trim(addslashes($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
        $description                      = trim(addslashes($worksheet->getCellByColumnAndRow(4, $row)->getValue()));
        $name                             = trim(addslashes($worksheet->getCellByColumnAndRow(5, $row)->getValue()));
        $full_name                        = trim(addslashes($worksheet->getCellByColumnAndRow(6, $row)->getValue()));
        $line_main                        = trim(addslashes($worksheet->getCellByColumnAndRow(7, $row)->getValue()));
        $barcode                          = trim(addslashes($worksheet->getCellByColumnAndRow(8, $row)->getValue()));
        $packing_unit                     = trim(addslashes($worksheet->getCellByColumnAndRow(9, $row)->getValue()));
        $liter                            = trim(addslashes($worksheet->getCellByColumnAndRow(10, $row)->getValue()));
        $sale_price                       = trim(addslashes($worksheet->getCellByColumnAndRow(11, $row)->getValue()));
        $special_min_1_quantity           = trim(addslashes($worksheet->getCellByColumnAndRow(12, $row)->getValue()));
        $special_min_1_quantity_price     = trim(addslashes($worksheet->getCellByColumnAndRow(13, $row)->getValue()));
        $special_min_2_quantity           = trim(addslashes($worksheet->getCellByColumnAndRow(14, $row)->getValue()));
        $special_min_2_quantity_price     = trim(addslashes($worksheet->getCellByColumnAndRow(15, $row)->getValue()));

        $sale_price_description = NULL;

        $cleaned_OEM          = cleaned_text($OEM);
        $cleaned_brand_code   = cleaned_text($brand_code);

        $description = strtoupper($description);

        if($cleaned_brand_code) {

          $description_names[] = $description;

          if(!array_key_exists($cleaned_brand_code, $product_list)) {

            $sale_price = !$sale_price ? 0 : $sale_price;
            # sale price could contain strings :/
            if(preg_match("/[a-z]/i", $sale_price)){
              $sale_price_description = $sale_price;
              $sale_price = NULL;
            } else {
              $sale_price_description = NULL;
              $sale_price = $sale_price + ($params["brand_price_rate"] * $sale_price);
            }

            $token = md5(EXCEL_KEYWORD . DASH_SPRT . $check_brand["name"] . DASH_SPRT . $cleaned_brand_code . DASH_SPRT . special_codes("products.resources.store"));

            $product_list[$cleaned_brand_code] = [
              "token"                             => $token,
              "remote_id"                         => NULL,

              "brand_code"                        => $brand_code,
              "cleaned_brand_code"                => $cleaned_brand_code,
              "reference_information"             => $reference_information,

              "description"                       => $description,
              "description_type"                  => $description_type,
              "model"                             => $description_type,
              "descriptions_temp_arr"             => [$description],

              "OEM"                               => $OEM,
              "cleaned_OEM"                       => $cleaned_OEM,
              "name"                              => $name,
              "full_name"                         => $full_name,
              "line_main"                         => $line_main,
              "barcode"                           => $barcode,
              "packing_unit"                      => $packing_unit,
              "liter"                             => $liter,

              "sale_price"                        => $sale_price,
              "sale_price_description"            => $sale_price_description,

              "currency"                          => $check_currency["main_name"],
              "currency_id"                       => $check_currency["id"],
              "final_currency_id"                 => $check_currency["id"],

              "special_min_1_quantity"            => $special_min_1_quantity,
              "special_min_1_quantity_price"      => $special_min_1_quantity_price,
              "special_min_2_quantity"            => $special_min_2_quantity,
              "special_min_2_quantity_price"      => $special_min_2_quantity_price,

              "initial_extra_rate"                  => $params["brand_price_rate"],

              // "brand_id"                          => $check_brand["id"],
              "brand_name"                          => $check_brand["name"],
              // "is_b2b_active"                     => $params["b2b_active"],
              "resource"                          => special_codes("products.resources.store"),

              "remote_is_active"                  => STATUS_DEACTIVE,
              "deleted_at"                        => NULL,
            ];
            $product_tokens[] = $token;
          } else {
            if (strpos($product_list[$brand_code]["OEM"], $OEM) === false) {
              $product_list[$brand_code]["OEM"] = $product_list[$brand_code]["OEM"] . " " . $OEM;
              $product_list[$brand_code]["cleaned_OEM"] = $product_list[$brand_code]["cleaned_OEM"] . OEM_SPRTR . $cleaned_OEM;
            }
            if (strpos($product_list[$brand_code]["description"], $description) === false) {
              $product_list[$brand_code]["description"]               = $product_list[$brand_code]["description"] . " " . $description;
              $product_list[$brand_code]["descriptions_temp_arr"][]   = $description;
            }
            if (strpos($product_list[$brand_code]["description_type"], $description_type) === false) {
              $product_list[$brand_code]["description_type"] = $product_list[$brand_code]["description_type"] . " " . $description_type;
              $product_list[$brand_code]["model"] = $product_list[$brand_code]["model"] . " " . $description_type; # I think it is same with desc type, ask this
            }
          }


        }
      }
      break;
    }

    // return json_response(rest_response(205,"test",["key" => $product_list]));
    if($description_names) { #carbrands
      $description_names = array_unique($description_names);

      $description_names_list = [];
      foreach ($description_names as $key => $item) {
        $description_names_list[] = [
          "token" => md5($item),
          "name" => $item,
        ];
      }

      if($description_names_list) {
        $this->local_db->query(insert_dublicate_key(local_table_name("cached_carbrands"),$description_names_list));
      }
    }

    $check_carbrands_sql = "SELECT
                          carbrands.`id`,
                          carbrands.`name`
                        FROM `".local_table_name("cached_carbrands")."` carbrands
                        WHERE carbrands.`deleted_at` IS NULL
                        ";
    $check_carbrands_query = $this->local_db->query($check_carbrands_sql);
    $check_carbrands = $check_carbrands_query->result_array();

    $check_carbrands_list = [];
    foreach ($check_carbrands as $key => $item) {
      $check_carbrands_list[$item["name"]] = $item;
    }
    $check_carbrands_list_values = array_keys($check_carbrands_list);
    $cross_codes                        = [];
    $delete_product_cross_codes_ids     = [];
    $product_carbrand_joints         = [];

    foreach ($product_list as $key => $item) {
      $product_list[$key]["carbrand_id"] = isset($check_carbrands_list[$item["description"]]) ? $check_carbrands_list[$item["description"]]["id"] : NULL; #for formality

      foreach ($item["descriptions_temp_arr"] as $sub_key => $sub_item) {
        if(in_array($sub_item,$check_carbrands_list_values)){
          $product_carbrand_joints[] = [
            "product_token"   => $item["token"],
            "carbrand_id"     => $check_carbrands_list[$sub_item]["id"]
          ];
        }
      }
      unset($product_list[$key]["descriptions_temp_arr"]);

      $delete_product_cross_codes_ids[] = $item["token"];
      $cross_codes = array_merge($cross_codes, oemstToproductCodes($item));
    }

    if(!$product_list) {
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("No data gainend from excel")
      );
    }

    $this->local_db->query("UPDATE `".local_table_name("cached_product_codes")."`
                              SET `deleted_at` = '".$params["deleted_at"]."'
                              WHERE `product_token` IN ('".implode("','",$delete_product_cross_codes_ids)."')
                            ");

    $this->local_db->query("UPDATE `".local_table_name("product_carbrand_joints")."`
                              SET `deleted_at` = '".$params["deleted_at"]."'
                              WHERE `product_token` IN ('".implode("','",$delete_product_cross_codes_ids)."')
                            ");


    $product_list = array_values($product_list);

    $this->local_db->insert(local_table_name("product_import_history"), [
      "brand_id" => $check_brand["id"],
      // "remote_brand_id" => $check_brand["remote_id"],
      "creator_id" => $params["creator_id"],
      "creator_ip" => $params["creator_ip"],
      "creator_name" => $params["creator_name"],
      "description" => $params["description"],
      "file_name" => $params["excel_file"],
      "brand_price_rate" => $params["brand_price_rate"],
      "brand_name" => $check_brand["name"],
      "result_count" => count($product_list),
      "cross_codes_count" => count($cross_codes),
      "is_dev" => $params["is_dev"],
      // "b2b_active" => $params["b2b_active"],
      "operation_date" => $params["operation_date"],
    ]);
    $insert_id = $this->local_db->insert_id();
    foreach ($product_list as $key => $item) {
      $product_list[$key]["import_history_id"] = $insert_id;
    }

    $product_list_insert_sql = insert_dublicate_key(local_table_name("cached_products"), $product_list);
    $this->local_db->query($product_list_insert_sql);

    if($cross_codes) {
      $cross_codes = array_values($cross_codes);
      $this->local_db->query(insert_dublicate_key(local_table_name("cached_product_codes"),$cross_codes));
    }

    if($product_carbrand_joints) {
      $this->local_db->insert_batch(local_table_name("product_carbrand_joints"), $product_carbrand_joints);
    }

    return rest_response(
      Status_codes::HTTP_CREATED,
      lang("Products successfuly imported")
    );
  }
}
