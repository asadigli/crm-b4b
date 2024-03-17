<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function invoicesEmailGenerator($order_id, $params)
{
  $CI = get_instance();

  $arr_baku_emails = $CI->config->item("order_send_baku_emails");
  // $arr_kerba_mail = $CI->config->item("order_send_kerba_emails");
  // $arr_kerba_customer_mail = $CI->config->item("order_send_kerba_customer_emails");
  $arr_ganja_emails = $CI->config->item("order_send_ganja_emails");
  $arr_europe_emails = $CI->config->item("order_send_europe_emails");
  $arr_store_emails = $CI->config->item("order_send_store_emails");

  $entry_info_query 	= $CI->local_db->query("SELECT
                                              entry.`company_id` as `id`,
                                              entry.`company_city_id` as `city_id`,
                                              entry.`company_depo_id` as `depo_id`,
                                              entry.`__company_ava_id` as `ava_id`,
                                              entry.`company_user_count` as `user_count`,
                                              entry.`company_curator_id` as `curator_id`,
                                              entry.`company_percent` as `percent`,
                                              entry.`company_price_percent` as `price_percent`,
                                              entry.`company_discount_percent` as `discount_percent`,
                                              entry.`company_monthly_limit` as `monthly_limit`,
                                              entry.`company_name` as `name`,
                                              entry.`company_phone` as `phone`,
                                              entry.`price_show` as `price_show`,
                                              entry.`stock_show` as `stock_show`,
                                              entry.`company_email` as `email`,
                                              b4b_orders.`product_order_comment`,
                                              stock_list.`stock_email`,
                                              b4b_orders.`product_order_depo_id`
                                          FROM `".local_table_name("b4b_orders")."` b4b_orders
                                          LEFT JOIN `".local_table_name("b4b_companies")."` entry ON entry.`company_id` = b4b_orders.`product_order_company_id`
                                            AND entry.`deleted_at` IS NULL
                                          LEFT JOIN `".local_table_name("stock_list")."` stock_list ON stock_list.`stock_city_id` = entry.`company_city_id`
                                            AND stock_list.`deleted_at` IS NULL
                                          WHERE
                                            b4b_orders.`product_order_delete`	='".STATUS_NO."' AND
                                            b4b_orders.`product_order_id`		= {$order_id}
                                          LIMIT 1
                                        ");
  $auth_user = $entry_info_query->row_array();

  $stock_types_query = $CI->local_db->query("SELECT
                                        `orlist_from`
                                      FROM `".local_table_name("b4b_order_details")."`
                                      WHERE `orlist_delete`		='".STATUS_NO."'
                                      AND `deleted_at` IS NULL
                                      AND `orlist_order_id`	= {$order_id}
                                      GROUP BY `orlist_from`
                                    ");
  $stock_types = $stock_types_query->result_array();

  $b4b_order_detalil_query 	= $CI->local_db->query("SELECT
                                                            b4b_order_details.`orlist_quantity`,
                                                            b4b_order_details.`orlist_price`,
                                                            b4b_order_details.`orlist_currency`,
                                                            b4b_order_details.`orlist_from`,
                                                            b4b_order_details.`orlist_name`,
                                                            b4b_order_details.`orlist_brend`,
                                                            b4b_order_details.`orlist_brend_code`,
                                                            b4b_order_details.`orlist_brend_org_code`,
                                                            b4b_order_details.`orlist_description`,
                                                            b4b_order_details.`orlist_ins_date`,

                                                            b4b_orders.`product_order_id`,
                                                            b4b_orders.`product_order_amount`,
                                                            b4b_orders.`product_order_currency`,
                                                            b4b_orders.`product_order_product_count`,
                                                            b4b_orders.`product_order_comment`,
                                                            b4b_orders.`product_order_store_comment`,
                                                            b4b_orders.`product_order_ins_date`

                                                            FROM `".local_table_name("b4b_order_details")."` b4b_order_details
                                                            LEFT JOIN `".local_table_name("b4b_orders")."` b4b_orders ON b4b_orders.`product_order_id` = b4b_order_details.`orlist_order_id`
                                                              AND b4b_orders.`product_order_delete` = '".STATUS_NO."'
                                                            WHERE b4b_order_details.`orlist_delete` = '".STATUS_NO."'
                                                            AND b4b_orders.`product_order_approve` = '".STATUS_YES."'
                                                            AND b4b_orders.`product_order_company_id` = {$auth_user["id"]}
                                                            AND b4b_orders.`product_order_id` = {$order_id}
                                                            ORDER BY b4b_order_details.`orlist_id`
                                                      ");
  $b4b_order_details = $b4b_order_detalil_query->result_array();

  $products_brand_codes = [];
  foreach ($b4b_order_details as $key => $item) {
    if(cleaned_text($item["orlist_brend_code"])){
      $products_brand_codes[] = cleaned_text($item["orlist_brend_code"]);
    }
  }
  $b4b_order_product_list = [];
  if($products_brand_codes) {
    $products_query = $CI->local_db->query("SELECT
                                              products.`cleaned_brand_code`,
                                              products.`stock_baku`,
                                              products.`stock_ganja`
                                            FROM `".local_table_name("cached_products")."` products
                                            WHERE products.`deleted_at` IS NULL
                                            AND products.`cleaned_brand_code` IN ('".implode("','",$products_brand_codes)."')
                                              ");
    $b4b_order_products = $products_query->result_array();
    foreach ($b4b_order_products as $key => $item) {
      $b4b_order_product_list[$item["cleaned_brand_code"]] = $item;
    }
  }

  $company_customer_account_info_sql = "SELECT
                                          customer_accounts.`exit_amount` as `last_payment_amount`,
                                          customer_accounts.`remote_created_at` as `last_payment_date_amount`,
                                          (SELECT
                                            (SUM(IFNULL(`entry_amount`,0)) -
                                            SUM(IFNULL(`exit_amount`,0)))
                                            FROM `".local_table_name("cached_customer_accounts")."`
                                            WHERE `deleted_at` IS NULL
                                            AND `company_id` = customer_accounts.`company_id`
                                          ) as `debt_amount`
                                      FROM `".local_table_name("cached_customer_accounts")."` customer_accounts
                                      WHERE customer_accounts.`deleted_at` IS NULL
                                      AND customer_accounts.`company_id` = {$auth_user["id"]}
                                      AND customer_accounts.`exit_amount` != '0.00'
                                      AND customer_accounts.`exit_amount` IS NOT NULL
                                      ORDER BY customer_accounts.`remote_created_at` desc
                                      LIMIT 1
                                        ";
  $company_customer_account_info_query = $CI->local_db->query($company_customer_account_info_sql);
  $result_ava_customer = $company_customer_account_info_query->row_array();

  $customer_payments_sql = "SELECT
                              customer_accounts.`exit_amount` as `payment_amount`,
                              customer_accounts.`invoice_code`,
                              customer_accounts.`report_tr_code`,
                              customer_accounts.`remote_created_at` as `payment_date`
                          FROM `".local_table_name("cached_customer_accounts")."` customer_accounts
                          WHERE customer_accounts.`deleted_at` IS NULL
                          AND customer_accounts.`company_id` = {$auth_user["id"]}
                          AND customer_accounts.`exit_amount` != '0.00'
                          AND customer_accounts.`exit_amount` IS NOT NULL
                          ORDER BY customer_accounts.`remote_created_at` desc
                          LIMIT 12
                        ";
  $customer_payment_query = $CI->local_db->query($customer_payments_sql);
  $customer_payments = $customer_payment_query->result_array();

  $mail_table_customer = "";
  $message_customer = "";
  $message = "";
  $message_kerba_customer = "";

  foreach ($stock_types as $key => $item)
  {
    $order_template_file = ($item['orlist_from'] == PRODUCT_RESOURCE_AVA || $item['orlist_from'] == PRODUCT_RESOURCE_AVA_CACHE ) ? 'OrderTemplate.xlsx' : 'OrderTemplate-2.xlsx';
    $objPHPExcel = PHPExcel_IOFactory::load(APPPATH ."..". DIRECTORY_SEPARATOR ."assets". DIRECTORY_SEPARATOR ."b4b". DIRECTORY_SEPARATOR ."templates". DIRECTORY_SEPARATOR .$order_template_file);

    $objPHPExcel->getProperties()->setCreator("InvoiceInfo")
      ->setLastModifiedBy("InvoiceInfo")
      ->setTitle("InvoiceInfo")
      ->setSubject("InvoiceInfo")
      ->setDescription("InvoiceInfo");

    $objPHPExcel->getActiveSheet(0)->getStyle("B4")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValueExplicit("B4", '#'.$order_id.' ('.date("d.m.Y H:i:s").')', PHPExcel_Cell_DataType::TYPE_STRING);

    $objPHPExcel->getActiveSheet(0)->getStyle("B6")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValueExplicit("B6", $auth_user['name'], PHPExcel_Cell_DataType::TYPE_STRING);

    $objPHPExcel->getActiveSheet(0)->getStyle("B7")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValueExplicit("B7", $auth_user['name'], PHPExcel_Cell_DataType::TYPE_STRING);

    $objPHPExcel->getActiveSheet(0)->getStyle("B8")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValueExplicit("B8", $auth_user['email'], PHPExcel_Cell_DataType::TYPE_STRING);

    $objPHPExcel->getActiveSheet(0)->getStyle("B9")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValueExplicit("B9", $params['entry_comment'], PHPExcel_Cell_DataType::TYPE_STRING);

    $j=12;
    $number = 0;
    $mail_table = '';
    $arr_total_price = [];

    foreach ($b4b_order_details as $sub_key => $sub_item) {
      $number++;
      $product_name 			= $sub_item['orlist_brend'];
      $product_model_name		= $sub_item['orlist_description'];
      $product_original_code	= $sub_item['orlist_brend_org_code'];
      $product_brend_code		= $sub_item['orlist_brend_code'];
      //$product_price 			= number_format($sub_item['orlist_price'], 2, '.', ' ');
      $product_price 			=  round($sub_item['orlist_price'], 2);
      $product_quantity 		=  round($sub_item['orlist_quantity'], 2);
      //$product_total_price 	= number_format( ( $product_price * $product_quantity ) , 2, '.', ' ');
      $product_total_price 	=  round(( $product_price * $product_quantity ), 2);

      $objPHPExcel->getActiveSheet(0)->getStyle("A$j")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
      $objPHPExcel->getActiveSheet(0)->getStyle("B$j")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
      $objPHPExcel->getActiveSheet(0)->getStyle("C$j")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
      $objPHPExcel->getActiveSheet(0)->getStyle("D$j")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
      $objPHPExcel->getActiveSheet(0)->getStyle("E$j")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
      $objPHPExcel->getActiveSheet(0)->getStyle("F$j")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
      $objPHPExcel->getActiveSheet(0)->getStyle("G$j")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
      $objPHPExcel->getActiveSheet(0)->getStyle("H$j")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);

      if( $sub_item['orlist_from'] == PRODUCT_RESOURCE_AVA || $sub_item['orlist_from'] == PRODUCT_RESOURCE_AVA_CACHE ) {
        $cleaned_brand_code = cleaned_text($product_brend_code);

        if($cleaned_brand_code) {
          $product_stock_quant_baku 	= isset($b4b_order_product_list[$cleaned_brand_code]) ? floatval($b4b_order_product_list[$cleaned_brand_code]["stock_baku"]) : 0;
          $product_stock_quant_ganca 	=isset($b4b_order_product_list[$cleaned_brand_code]) ? floatval($b4b_order_product_list[$cleaned_brand_code]["stock_ganja"]) : 0;
        }

        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValueExplicit("A$j", $number, 					PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("B$j", $product_name,			PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("C$j", $product_model_name,		PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("D$j", $product_original_code,	PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("E$j", $product_brend_code, 		PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("F$j", $product_quantity,		PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("G$j", $product_price, 			PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("H$j", $product_total_price, 	PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $j++;


        $delivery_day = AVA_PRODUCT_DELIVERY_TIME;

        $mail_table_customer.='
          <tr>
            <td>'.$number.'</td>
            <td>'.$product_name.'</td>
            <td>'.$delivery_day.'</td>
            <td>'.$product_model_name.'</td>
            <td>'.$product_original_code.'</td>
            <td>'.$product_brend_code.'</td>
            <td align="right">'.$product_quantity.'</td>
            <td align="right">'.number_format($product_price, 2, '.', ' ').'</td>
            <td align="right">'.number_format($product_total_price, 2, '.', ' ').'</td>
          </tr>
        ';

        $mail_table.='
          <tr>
            <td>'.$number.'</td>
            <td>'.$product_name.'</td>
            <td>'.$delivery_day.'</td>
            <td>'.$product_model_name.'</td>
            <td>'.$product_original_code.'</td>
            <td align="right">'.$product_stock_quant_baku.'</td>
            <td align="right">'.$product_stock_quant_ganca.'</td>
            <td>'.$product_brend_code.'</td>
            <td align="right">'.$product_quantity.'</td>
            <td align="right">'.number_format($product_price, 2, '.', ' ').'</td>
            <td align="right">'.number_format($product_total_price, 2, '.', ' ').'</td>
          </tr>
        ';

      }
      else {
        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValueExplicit("A$j", $number, 					PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("B$j", $product_brend_code,		PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("C$j", $product_model_name,		PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("D$j", $product_original_code,	PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("E$j", $product_name, 			PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit("F$j", $product_quantity,		PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("G$j", $product_price, 			PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit("H$j", $product_total_price, 	PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $j++;

        if($sub_item['orlist_from'] == PRODUCT_RESOURCE_RONAX || $sub_item['orlist_from'] == strtoupper(PRODUCT_RESOURCE_RONAX) )
        {
          $delivery_day = RONAX_PRODUCT_DELIVERY_TIME_2;
        }
        elseif( $sub_item['orlist_from'] == PRODUCT_RESOURCE_STORE_DATA || $sub_item['orlist_from'] == strtoupper(PRODUCT_RESOURCE_STORE_DATA) )
        {
          $delivery_day = STORE_PRODUCT_DELIVERY_TIME;
        }
        else {
          $delivery_day = '';
        }

        $mail_table.='
          <tr>
            <td>'.$number.'</td>
            <td>'.$product_brend_code.'</td>
            <td>'.$delivery_day.'</td>
            <td>'.$product_model_name.'</td>
            <td>'.$product_original_code.'</td>
            <td>'.$product_name.'</td>
            <td align="right">'.$product_quantity.'</td>
            <td align="right">'.number_format($product_price, 2, '.', ' ').'</td>
            <td align="right">'.number_format($product_total_price, 2, '.', ' ').'</td>
          </tr>
        ';
        $mail_table_customer.='
          <tr>
            <td>'.$number.'</td>
            <td>'.$product_brend_code.'</td>
            <td>'.$delivery_day.'</td>
            <td>'.$product_model_name.'</td>
            <td>'.$product_original_code.'</td>
            <td>'.$product_name.'</td>
            <td align="right">'.$product_quantity.'</td>
            <td align="right">'.number_format($product_price, 2, '.', ' ').'</td>
            <td align="right">'.number_format($product_total_price, 2, '.', ' ').'</td>
          </tr>
        ';
      }

      $arr_total_price[] = $product_total_price;
    }

    $net_total_price = round(array_sum($arr_total_price), 2);

    $objPHPExcel->getActiveSheet(0)->getStyle("A$j")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
    $objPHPExcel->getActiveSheet(0)->getStyle("B$j")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
    $objPHPExcel->getActiveSheet(0)->getStyle("C$j")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
    $objPHPExcel->getActiveSheet(0)->getStyle("D$j")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
    $objPHPExcel->getActiveSheet(0)->getStyle("E$j")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
    $objPHPExcel->getActiveSheet(0)->getStyle("F$j")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
    $objPHPExcel->getActiveSheet(0)->getStyle("G$j")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
    $objPHPExcel->getActiveSheet(0)->getStyle("H$j")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValueExplicit("A$j", "", 				PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("B$j", "",				PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("C$j", "",				PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("D$j", "",				PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("E$j", "", 				PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("F$j", "", 				PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("G$j", "", 				PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("H$j", $net_total_price, PHPExcel_Cell_DataType::TYPE_NUMERIC);

    $objPHPExcel->getActiveSheet()->setTitle('InvoiceInfo');
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

    $filename = APPPATH . "..". DIRECTORY_SEPARATOR ."assets" . DIRECTORY_SEPARATOR . "b4b" . DIRECTORY_SEPARATOR . "_temp" . DIRECTORY_SEPARATOR . "invoices" . DIRECTORY_SEPARATOR . date('YmdHis').'_'. md5($order_id) .'.xlsx';

    $objWriter->save($filename);
    $company_payment_table = '';

    $payment_number = 1;
    foreach ($customer_payments as $sub_key => $sub_item) {
        $invoice_number = $sub_item['invoice_code'];

        $company_payment_table.= '
          <tr>
            <td align="left">'.$payment_number++.'</td>
            <td align="center">'.date("d.m.Y", strtotime($sub_item['payment_date'])).'</td>
            <td align="center">'.$invoice_number.'</td>
            <th align="right">'.number_format($sub_item['payment_amount'], 2, '.', ' ').'</th>
          </tr>
        ';
    }

    $musteri_qaliq_borc 		= $result_ava_customer["debt_amount"];
    $musteri_son_odeme_tarixi 	= $result_ava_customer['last_payment_date_amount'];
    $musteri_son_odeme_meblegi 	= $result_ava_customer['last_payment_amount'];

    if( $item['orlist_from'] == PRODUCT_RESOURCE_AVA || $item['orlist_from'] == PRODUCT_RESOURCE_AVA_CACHE ) {
      $message = '

      <table border="0" style="width: 100%;">
        <tr>
          <th valign="top" align="left">

            <h2>Sifarişçi Məlumatları</h2>

            <p>

              <table border="1" style="width: 80%;">
                <thead>

                  <tr>
                    <th align="left" width="35%">ŞİRKƏT</th>
                    <td align="left">'.$auth_user['name'].'</td>
                  </tr>

                  <tr>
                    <th align="left">SİFARİŞÇİ</th>
                    <td align="left">'.$auth_user['name'].' '.$auth_user['name'].'</td>
                  </tr>

                  <tr>
                    <th align="left">E-MAİL</th>
                    <td align="left">'.$auth_user['email'].'</td>
                  </tr>

                  <tr>
                    <th align="left">ƏLAVƏ QEYD</th>
                    <td align="left">'.$params['entry_comment'].'</td>
                  </tr>

                </thead>
              </table>

            </p>


            <h2>Hesab Məlumatları</h2>

            <p>

              <table border="1" style="width: 80%;">
                <thead>

                  <tr>
                    <th align="left" width="35%">SON ÖDƏMƏ TARİXİ</th>
                    <td align="left">'.date("d.m.Y", strtotime($musteri_son_odeme_tarixi)).'</td>
                  </tr>

                  <tr>
                    <th align="left">SON ÖDƏMƏ MƏBLƏĞİ</th>
                    <td align="left">'.number_format($musteri_son_odeme_meblegi, 2, '.', ' ').'</td>
                  </tr>

                  <tr>
                    <th align="left">QALIQ BORC</th>
                    <th align="left">'.number_format($musteri_qaliq_borc, 2, '.', ' ').'</th>
                  </tr>

                </thead>
              </table>

            </p>

          </th>
          <th valign="top" align="left">

            <h2>Ödəniş Tarixçəsi</h2>

            <p>

              <table border="1" style="width: 100%;">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>TARİX</th>
                    <th>ƏMƏLİYYAT</th>
                    <th>MƏBLƏĞ</th>
                  </tr>
                </thead>
                <tbody>
                  '.$company_payment_table.'
                </tbody>
              </table>

            </p>

          </th>
        </tr>
      </table>

      <h2>Sifariş Məlumatları</h2>

      <p>

        <table border="1" style="width: 100%;">
          <thead>
            <tr>
              <th>#</th>
              <th>BREND ADI</th>
              <th>GÜN</th>
              <th>MARKA</th>
              <th>ORİGİNAL KOD</th>
              <th>BAKI</th>
              <th>GƏNCƏ</th>
              <th>BREND KODU</th>
              <th>SAYI</th>
              <th>QİYMƏT EUR</th>
              <th>MƏBLƏĞ</th>
            </tr>
          </thead>
          <tbody>
            '.$mail_table.'
          </tbody>
          <tfoot>
            <tr>
              <th colspan="11" align="right">'.number_format($net_total_price, 2, '.', ' ').'</th>
            </tr>
          </tfoot>
        </table>

      </p>';




      $message_customer = '

      <table border="0" style="width: 100%;">
        <tr>
          <th valign="top" align="left">

            <h2>Sifarişçi Məlumatları</h2>

            <p>

              <table border="1" style="width: 80%;">
                <thead>

                  <tr>
                    <th align="left" width="35%">ŞİRKƏT</th>
                    <td align="left">'.$auth_user['name'].'</td>
                  </tr>

                  <tr>
                    <th align="left">SİFARİŞÇİ</th>
                    <td align="left">'.$auth_user['name'].' '.$auth_user['name'].'</td>
                  </tr>

                  <tr>
                    <th align="left">E-MAİL</th>
                    <td align="left">'.$auth_user['email'].'</td>
                  </tr>

                  <tr>
                    <th align="left">ƏLAVƏ QEYD</th>
                    <td align="left">'.$params['entry_comment'].'</td>
                  </tr>

                </thead>
              </table>

            </p>


            <h2>Hesab Məlumatları</h2>

            <p>

              <table border="1" style="width: 80%;">
                <thead>

                  <tr>
                    <th align="left" width="35%">SON ÖDƏMƏ TARİXİ</th>
                    <td align="left">'.date("d.m.Y", strtotime($musteri_son_odeme_tarixi)).'</td>
                  </tr>

                  <tr>
                    <th align="left">SON ÖDƏMƏ MƏBLƏĞİ</th>
                    <td align="left">'.number_format($musteri_son_odeme_meblegi, 2, '.', ' ').'</td>
                  </tr>

                  <tr>
                    <th align="left">QALIQ BORC</th>
                    <th align="left">'.number_format($musteri_qaliq_borc, 2, '.', ' ').'</th>
                  </tr>

                </thead>
              </table>

            </p>

          </th>
          <th valign="top" align="left">

            <h2>Ödəniş Tarixçəsi</h2>

            <p>

              <table border="1" style="width: 100%;">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>TARİX</th>
                    <th>ƏMƏLİYYAT</th>
                    <th>MƏBLƏĞ</th>
                  </tr>
                </thead>
                <tbody>
                  '.$company_payment_table.'
                </tbody>
              </table>

            </p>

          </th>
        </tr>
      </table>

      <h2>Sifariş Məlumatları</h2>

      <p>

        <table border="1" style="width: 100%;">
          <thead>
            <tr>
              <th>#</th>
              <th>BREND ADI</th>
              <th>GÜN</th>
              <th>MARKA</th>
              <th>ORİGİNAL KOD</th>
              <th>BREND KODU</th>
              <th>SAYI</th>
              <th>QİYMƏT EUR</th>
              <th>MƏBLƏĞ</th>
            </tr>
          </thead>
          <tbody>
            '.$mail_table_customer.'
          </tbody>
          <tfoot>
            <tr>
              <th colspan="9" align="right">'.number_format($net_total_price, 2, '.', ' ').'</th>
            </tr>
          </tfoot>
        </table>

      </p>';



    }
    else {

      $message = '

      <table border="0" style="width: 100%;">
        <tr>
          <th valign="top" align="left">

            <h2>Sifarişçi Məlumatları</h2>

            <p>

              <table border="1" style="width: 80%;">
                <thead>

                  <tr>
                    <th align="left" width="35%">ŞİRKƏT</th>
                    <td align="left">'.($auth_user['name'] ?: "").'</td>
                  </tr>

                  <tr>
                    <th align="left">E-MAİL</th>
                    <td align="left">'.($auth_user['email'] ?: "").'</td>
                  </tr>

                  <tr>
                    <th align="left">ƏLAVƏ QEYD</th>
                    <td align="left">'.$params["entry_comment"].'</td>
                  </tr>

                </thead>
              </table>

            </p>


            <h2>Hesab Məlumatları</h2>

            <p>

              <table border="1" style="width: 80%;">
                <thead>

                  <tr>
                    <th align="left" width="35%">SON ÖDƏMƏ TARİXİ</th>
                    <td align="left">'.date("d.m.Y", strtotime($musteri_son_odeme_tarixi ?: "")).'</td>
                  </tr>

                  <tr>
                    <th align="left">SON ÖDƏMƏ MƏBLƏĞİ</th>
                    <td align="left">'. ($musteri_son_odeme_meblegi ? number_format($musteri_son_odeme_meblegi, 2, '.', ' ') : "").'</td>
                  </tr>

                  <tr>
                    <th align="left">QALIQ BORC</th>
                    <th align="left">'. ($musteri_qaliq_borc ? number_format($musteri_qaliq_borc, 2, '.', ' ') : "") .'</th>
                  </tr>

                </thead>
              </table>

            </p>

          </th>
          <th valign="top" align="left">

            <h2>Ödəniş Tarixçəsi</h2>

            <p>

              <table border="1" style="width: 100%;">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>TARİX</th>
                    <th>ƏMƏLİYYAT</th>
                    <th>MƏBLƏĞ</th>
                  </tr>
                </thead>
                <tbody>
                  '.$company_payment_table.'
                </tbody>
              </table>

            </p>

          </th>
        </tr>
      </table>

      <h2>Sifariş Məlumatları</h2>

      <p>

        <table border="1" style="width: 100%;">
          <thead>
            <tr>
              <th>#</th>
              <th>BREND KODU</th>
              <th>GÜN</th>
              <th>MARKA</th>
              <th>ORİGİNAL KOD</th>
              <th>BREND ADI</th>
              <th>SAYI</th>
              <th>QİYMƏT EUR</th>
              <th>MƏBLƏĞ</th>
            </tr>
          </thead>
          <tbody>
            '.$mail_table.'
          </tbody>
          <tfoot>
            <tr>
              <th colspan="9" align="right">'.number_format($net_total_price, 2, '.', ' ').'</th>
            </tr>
          </tfoot>
        </table>

      </p>';

    }
  }

  # Superviser emails
  if($sub_item['orlist_from'] == PRODUCT_RESOURCE_AVA || $sub_item['orlist_from'] == strtoupper(PRODUCT_RESOURCE_AVA)) {
    if($auth_user['product_order_depo_id'] === 2) {
      foreach( $arr_ganja_emails as $ganja_email ) {
        send_attachment_mail($ganja_email, $auth_user['name'].' - GƏNCƏ - Sifariş #'.$order_id.' ('.date('d.m.Y H:i:s').')', $message, $filename);
      }
    }
    elseif($auth_user['product_order_depo_id'] === 1) {
      foreach( $arr_baku_emails as $baku_email ){
        send_attachment_mail($baku_email, $auth_user['name'].' - BAKI - Sifariş #'.$order_id.' ('.date('d.m.Y H:i:s').')', $message, $filename);
      }
    }
  }
  // elseif( $sub_item['orlist_from'] == strtoupper(PRODUCT_RESOURCE_STORE_DATA) ) {
  //
  //   // SİFARİŞ-60
  //   foreach( $arr_store_emails as $store_mail )
  //   {
  //     send_attachment_mail($store_mail, $auth_user['name'].' - SİFARİŞ-60 - sifariş #'.$order_id.' ('.date('d.m.Y H:i:s').')', $message, $filename);
  //   }
  //
  //
  // }
  // else {
  //   // AVTOBOX
  //   foreach( $arr_europe_emails as $avropa_mail )
  //   {
  //     send_attachment_mail($avropa_mail, $auth_user['name'].' - AVROPA - sifariş #'.$order_id.' ('.date('d.m.Y H:i:s').')', $message, $filename);
  //   }
  // }

  # Company / Entry Email
  $auth_user['email'] = "cisctsbabayev@gmail.com";
  send_attachment_mail($auth_user['email'], $auth_user['name'].' - Sifariş #'.$order_id.' ('.date('d.m.Y H:i:s').')', $message_customer, $filename);
  die;
  return true;
}
