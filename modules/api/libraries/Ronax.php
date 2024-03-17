<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Ronax {

  public static function index($params)
  {
    $CI = get_instance();

    $search_code = $params["search_code"];
    $ronax_url = $CI->config->item("ronax_url") ?: NULL;
    $ronax_key = $CI->config->item("ronax_key") ?: NULL;

    validateArray([
      "search_code" => $search_code,
      "ronax_url" => $ronax_url,
      "ronax_key" => $ronax_key,
    ],["search_code", "ronax_url", "ronax_key"]);

    $post_fields = [
        "Key" => $ronax_key,
        "Sub" => "PartRequest",     #PartRequest
        "Format" => "json",         #json | php | xml
        "PartNr" => $search_code    #3G0880242E
    ];

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL                      => $ronax_url,
      CURLOPT_RETURNTRANSFER 	         => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_POST 		                 => true,

      CURLOPT_CONNECTTIMEOUT 	         => 10,
      CURLOPT_TIMEOUT 		             => 30,

      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_POSTFIELDS  	           => http_build_query($post_fields),
      CURLOPT_SSL_VERIFYPEER           => false,
    ));

    $response  	= curl_exec($curl);
    $httpCode  	= curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if((int)$httpCode !== Status_codes::HTTP_OK) {
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("Bad request")
      );
    }

    if(!trim($response)){
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No product from response")
      );
    }

    $ronax_products_response = json_decode($response, true);

    if(!$ronax_products_response) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("No product from response")
      );
    }

    $ronax_products = [];
    foreach( $ronax_products_response as $key => $item)
    {
      $product_name = isset($item["ProductName"]) ? $item["ProductName"] : NULL;

      $ronax_delivery_day = 20;

      if(strpos(strtoupper($product_name), "LAND ROVER") !== false && strpos(strtoupper($product_name), "(5 WEEKS)") !== false) {
        $ronax_delivery_day = 45;

      } elseif (strpos(strtoupper($product_name), "BMW") !== false && strpos(strtoupper($product_name), "(4 WEEKS)") !== false) {
        $ronax_delivery_day = 45;

      } elseif(strpos(strtoupper($product_name), "BMW") !== false && strpos(strtoupper($product_name), "(LONG DELIVERY)") !== false) {
        $ronax_delivery_day = 45;

      } elseif(strpos(strtoupper($product_name), "LAND ROVER") !== false && strpos(strtoupper($product_name), "(LONG DELIVERY)") !== false) {
        $ronax_delivery_day = 45;

      } elseif(strpos(strtoupper($product_name), "(3 WEEK)") !== false || strpos(strtoupper($product_name), "(3 WEEKS)") !== false  || strpos(strtoupper($product_name), "(2 WEEKS)") !== false) {
        $ronax_delivery_day = 30;

      } elseif(strpos(strtoupper($product_name), "(5 WEEK)") !== false || strpos(strtoupper($product_name), "(5 WEEKS)") !== false) {
        $ronax_delivery_day = 40;

      } elseif(strpos(strtoupper($product_name), "TOYOTA (4 WEEKS)") !== false) {
        $ronax_delivery_day = 50;

      } elseif(strpos(strtoupper($product_name), "TOYOTA A") !== false) {
        $ronax_delivery_day = 30;

      } elseif(strpos(strtoupper($product_name), "TOYOTA A") !== false) {
        $ronax_delivery_day = 30;

      } elseif(strpos(strtoupper($product_name), "MERCEDES BENZ (2-3 WEEKS)") !== false) {
          $ronax_delivery_day = 35;
      }

      $ronax_products[] = [
        "productid"       		=> isset($item["ProductId"]) ? $item["ProductId"] : NULL,
        "productname"     		=> $product_name,
        "partid" 	        		=> isset($item["PartId"]) ? $item["PartId"] : NULL,
        "partnr" 		         	=> isset($item["PartNr"]) ? $item["PartNr"] : NULL,
        "partnrnew" 	       	=> isset($item["PartNrNew"]) ? $item["PartNrNew"] : NULL,
        "partname" 		       	=> isset($item["PartName"]) ? $item["PartName"] : NULL,
        "discountgroup"      	=> isset($item["DiscountGroup"]) ? $item["DiscountGroup"] : NULL,
        "price" 	         		=> isset($item["Price"]) ? $item["Price"] : NULL,
        "faktor"              => isset($item["Faktor"]) ? $item["Faktor"] : NULL,
        "priceclient"     		=> isset($item["PriceClient"]) ? $item["PriceClient"] : NULL,
        "weight"              => isset($item["Weight"]) ? $item["Weight"] : NULL,
        "isold" 	         		=> isset($item["IsOld"]) ? $item["IsOld"] : NULL,
        "add"                 => isset($item["Add"]) ? $item["Add"] : NULL,
        "deliveryday"         => $ronax_delivery_day
      ];
    }

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      $ronax_products
    );
  }

  public static function search($params)
  {
    $ronax_products = Ronax::index($params);
    $ronax_products_list = [];

    if(isset($ronax_products["code"]) && $ronax_products["code"] === Status_codes::HTTP_OK) {
      $ronax_products_list = $ronax_products["data"];
    }

    return $ronax_products_list;
  }

}
