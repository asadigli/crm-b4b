<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model{

  function __construct() {
    parent::__construct();
    $this->local_db = $this->load->database("local_db",TRUE);
  }

  function getValidEntryToken($params){
    $token_query = $this->local_db->select("id,token")
                                    ->from(local_table_name("b4b_entry_tokens"))
                                      ->where("entry_id",$params["user_id"])
                                        ->where("deleted_at",null)
                                          ->where("status",ETS_IDLE)
                                            ->get();

    if (!$token_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_BAD_REQUEST,
        lang("You are out of limit") . " - WRN100"
      );
    }
    $token = $token_query->row_array();

    $this->local_db->where("id",$token["id"]);
    $this->local_db->update(local_table_name("b4b_entry_tokens"),["status" => ETS_RESERVED,"device" => $params["device"]]);

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "token" => $token["token"]
      ]
    );
  }

  function checkValidityOfToken($params){
    $token_query = $this->local_db->select("id,token")
                                    ->from(local_table_name("b4b_entry_tokens"))
                                      ->where("entry_id",$params["user_id"])
                                        ->where("token",$params["entry_token"])
                                          ->where("status",ETS_RESERVED)
                                            ->where("deleted_at",null)
                                              ->get();

    if (!$token_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_MOVED_PERMANENTLY,
        lang("Entry not found") . " - WRN101"
      );
    }
    $token = $token_query->row_array();

    return rest_response(
      Status_codes::HTTP_OK,
      lang("Success"),
      [
        "token" => $token["token"]
      ]
    );
  }

  function index($params){
    if ($params["password_free"]) {
      $status = special_codes("entry_tokens.statuses.reserved");
      $where_condition = " AND  MD5(entry.`company_id`) = '{$params["user_secret"]}'
                           AND  entry.`company_id` = (SELECT entry_tokens.`entry_id`
                                                       FROM `".local_table_name("b4b_entry_tokens")."` entry_tokens
                                                       WHERE entry_tokens.`token` = '{$params["entry_token"]}'
                                                       AND entry_tokens.`deleted_at` IS NULL
                                                       AND entry_tokens.`status` = '$status'
                                                       ORDER BY `id` DESC
                                                       LIMIT 1) ";
    } else if($params["access_token"]) {
      $login_token_sql_query = "SELECT
                                      `id`,
                                      `entry_id`
                                FROM `".local_table_name("b4b_login_tokens")."`
                                WHERE `deleted_at` IS NULL
                                AND `expired_at` IS NULL
                                AND `entry_token` = '{$params["entry_token"]}'
                                AND `token` = '{$params["access_token"]}'
                                AND `expire_at` > NOW()";

      $login_token = $this->local_db->query($login_token_sql_query);
      if (!$login_token->num_rows()) {
        return rest_response(
          Status_codes::HTTP_UNAUTHORIZED,
          lang("Invalid login access token")
        );
      }
      $login_token_row = $login_token->row_array();
      $this->local_db->where("id",$login_token_row["id"]);
      $this->local_db->update(local_table_name("b4b_login_tokens"), ["expired_at" => now()]);

      $where_condition = " AND entry.`company_id` = {$login_token_row["entry_id"]} ";
    } else {
      $where_condition = " AND entry.`company_email` = '{$params["email"]}' ";
    }
    $sql_query = "SELECT
                      entry.`company_id` as id,
                      entry.`company_name` as name,
                      entry.`company_email` as email,
                      entry.`company_phone` as phone,
                      entry.`price_show` as price_show,
                      entry.`is_blocked` as is_blocked,
                      entry.`stock_show` as stock_show,
                      entry.`company_password` as password,
                      entry.`old_password` as old_password,
                      entry.`company_token` as token,
                      entry.`company_avatar` as avatar,
                      -- entry.`__company_ava_id` as remote_id,
                      entry.`company_depo_id` as default_warehouse_id,
                      entry.`company_curator_id` as supervisor_id,
                      CONCAT(curator.`curator_name`,' ',curator.`curator_surname`) as supervisor_name,
                      curator.`curator_email` as supervisor_email,
                      curator.`curator_mobile` as supervisor_phone,
                      curator.`image` as supervisor_photo,
                      curator.`curator_whatsapp` as supervisor_whatsapp,
                      (SELECT
                          CONCAT(SUM(CASE WHEN `status` IN ('".ETS_IDLE."','".ETS_RESERVED."') THEN 1 ELSE 0 END),'||',SUM(CASE WHEN `status` IN ('".ETS_RESERVED."') THEN 1 ELSE 0 END)) as result
                       FROM `".local_table_name("b4b_entry_tokens")."`
                       WHERE `entry_id` = entry.`company_id`
                       AND `deleted_at` IS NULL) as entry_limits
                   FROM `".local_table_name("b4b_companies")."` entry
                   LEFT JOIN `".local_table_name("b4b_curators")."` curator ON curator.`curator_id` = entry.`company_curator_id` AND curator.`deleted_at` IS NULL
                   WHERE entry.`deleted_at` IS NULL
                   $where_condition
                   LIMIT 1";
    $user_query = $this->local_db->query($sql_query);
    if (!$user_query->num_rows()) {
      return rest_response(
        Status_codes::HTTP_NO_CONTENT,
        lang("Entry does not exist")
      );
    }
    $user_row = $user_query->row_array();

    if($user_row["is_blocked"] === STATUS_ACTIVE) {
      return rest_response(
        Status_codes::HTTP_LOCKED,
        lang("User is blocked")
      );
    }


    if (!$params["password_free"] && !$params["access_token"]) {
      if ($user_row["password"]) {
        if (!password_verify($params["password"],$user_row["password"])) {
          $login_attempt_list = [
            "entry_id" => $user_row["id"],
            "entry_token" => $params["entry_token"],
            "status" => special_codes("b4b_entries.login_attempts.statuses.failed"),
            "type" => special_codes("b4b_entries.login_attempts.types.manual"),
            "device" => $params["entry_device"],
            "ip_address" => $params["entry_ip"]
          ];
          $this->local_db->insert(local_table_name("login_attempts"),$login_attempt_list);

          return rest_response(
            Status_codes::HTTP_BAD_REQUEST,
            lang("Password or username is incorrect")
          );
        }
      } else if($user_row["old_password"]) {
        if (md5(md5($params["password"].'13').md5($params["password"].'30').'17') !== $user_row["old_password"]) {
          $login_attempt_list = [
            "entry_id" => $user_row["id"],
            "entry_token" => $params["entry_token"],
            "status" => special_codes("b4b_entries.login_attempts.statuses.failed"),
            "type" => special_codes("b4b_entries.login_attempts.types.manual"),
            "device" => $params["entry_device"],
            "ip_address" => $params["entry_ip"]
          ];
          $this->local_db->insert(local_table_name("login_attempts"),$login_attempt_list);

          return rest_response(
            Status_codes::HTTP_BAD_REQUEST,
            lang("Password or username is incorrect")
          );
        }

        $this->local_db->where("company_id", $user_row["id"]);
        $this->local_db->update(local_table_name("b4b_companies"),["company_password" => password_hash($params["password"],PASSWORD_DEFAULT)]);
      } else {
        return rest_response(
          Status_codes::HTTP_BAD_REQUEST,
          lang("Password or username is incorrect")
        );
      }
    }

    if ($params["get_token"]) {
      $login_token = bin2hex(openssl_random_pseudo_bytes(45));
      $token_insert_list = [
        "entry_id"      => $user_row["id"],
        "token"         => $login_token,
        "ip_address"    => $params["entry_ip"],
        "entry_token"   => $params["entry_token"],
        "expire_at"     => date("Y-m-d H:i:s", strtotime("+30 sec"))
      ];

      $this->local_db->where("entry_id",$user_row["id"])
          ->where("entry_token",$params["entry_token"])
              ->where("expired_at",null);
      $this->local_db->update(local_table_name("b4b_login_tokens"), ["expired_at" => now()]);

      $this->local_db->insert(local_table_name("b4b_login_tokens"),$token_insert_list);

      return rest_response(
        Status_codes::HTTP_OK,
        lang("Success"),
        [
          "id"    => $user_row["id"],
          "token" => $login_token
        ]
      );
    }


    $sql_query = "SELECT
                      b4b_customers.`id`,
                      entry_customer.`order`,
                      entry_customer.`remote_customer_id` as remote_id,
                      IFNULL(b4b_customers.`custom_name`,b4b_customers.`name`) as `name`,
                      currencies.`main_name` as `currency_name`
                   FROM `".local_table_name("b4b_entry_customers")."` entry_customer
                   LEFT JOIN `".local_table_name("cached_customers")."` b4b_customers ON b4b_customers.`id` = entry_customer.`customer_id`
                   LEFT JOIN `".local_table_name("cached_currencies")."` currencies ON currencies.`id` = b4b_customers.`currency_id`
                    AND currencies.`deleted_at` IS NULL
                   WHERE  entry_customer.`deleted_at` IS NULL
                   AND    b4b_customers.`deleted_at` IS NULL
                   AND    b4b_customers.`id` IS NOT NULL
                   AND    entry_customer.`status` = '".special_codes("b4b_customers.statuses.allowed")."'
                   AND    entry_customer.`entry_id` = {$user_row["id"]}
                   ORDER BY entry_customer.`order`,entry_customer.`id` ASC"
                  ;
    $customers_query = $this->local_db->query($sql_query);
    $user_row["customers"] = $customers_query->result_array();
    $user_row["supervisor_photo"] = $user_row["supervisor_photo"] ? decode_blob($user_row["supervisor_photo"]) : null;


     $true_id = false;
     $user_row["remote_id"] = null;
     if ($params["customer_id"]) {
       $customer_ids = array_map(function($i){
         return $i["remote_id"];
       },$user_row["customers"]);
       $true_id = in_array($params["customer_id"],$customer_ids);
       $user_row["remote_id"] = $true_id ? $params["customer_id"] : NULL;
     }

     foreach ($user_row["customers"] as $key => $item) {
       if (!$user_row["remote_id"]) {
         if ($key === 0) {
           $user_row["remote_id"] = $item["remote_id"];
         }
       }
       $user_row["customers"][$key]["is_current"] = $true_id ? ((int)$params["customer_id"] === (int)$item["remote_id"] ?: false) : !$key;
     }

     $user_row["supervisor"] = [];
     if ($user_row["supervisor_id"]) {
       $user_row["supervisor"] = [
         "id" => $user_row["supervisor_id"],
         "name" => $user_row["supervisor_name"],
         "email" => $user_row["supervisor_email"],
         "phone" => $user_row["supervisor_phone"],
         "photo" => $user_row["supervisor_photo"],
         "whatsapp" => $user_row["supervisor_whatsapp"],
       ];
     }

     $user_row["entry_limit"]   = $user_row["entry_limits"] ? (int)explode("||",$user_row["entry_limits"])[0] : 0;
     $user_row["used_limit"]    = $user_row["entry_limits"] ? (int)explode("||",$user_row["entry_limits"])[1] : 0;


     unset($user_row["entry_limits"]);
     unset($user_row["supervisor_id"]);
     unset($user_row["supervisor_name"]);
     unset($user_row["supervisor_email"]);
     unset($user_row["supervisor_phone"]);
     unset($user_row["supervisor_photo"]);
     unset($user_row["supervisor_whatsapp"]);
     unset($user_row["password"]);

     $login_attempt_list = [
       "entry_id"       => $user_row["id"],
       "entry_token"    => $params["entry_token"],
       "status"         => special_codes("b4b_entries.login_attempts.statuses.success"),
       "type"           => special_codes("b4b_entries.login_attempts.types." . ($params["password_free"] ? "auto" : "manual")),
       "device"         => $params["entry_device"],
       "ip_address"     => $params["entry_ip"]
     ];
     $this->local_db->insert(local_table_name("login_attempts"),$login_attempt_list);

     $res_currencies = get_currency();
     $currencies_list = [];
     foreach ($res_currencies as $key => $item) {
       $currencies_list[$item["main_name"]] = $item;
     }

     $user_row["cart_info"] = [];

     $remote_customer_query = $user_row["remote_id"] ? " AND cart.`remote_customer_id` = {$user_row["remote_id"]}" : "";

     $current_account_currency = CURRENCY_EUR;
     foreach ($user_row["customers"] as $key => $item) {
       if($item["is_current"]) {
        $current_account_currency = $item["currency_name"];
       }
     }

     $cart_sql = "SELECT
                     cart.`basket_id` as `id`,
                     cart.`basket_quantity` as `quantity`,
                     cart.`basket_price` as `sale_price`,
                     products.`custom_main_sale_price` as `main_sale_price`,
                     cart.`basket_currency` as `currency`
                     FROM `".local_table_name("b4b_cart")."` cart
                     LEFT JOIN `".local_table_name("cached_products")."` products ON products.`id` = cart.`basket_product_id`
                       AND products.`deleted_at` IS NULL
                     WHERE cart.`deleted_at` IS NULL
                     AND cart.`basket_company_id` = {$user_row["id"]}
                     AND cart.`basket_currency` = '{$current_account_currency}'
                     $remote_customer_query
                     ";

     $cart_query = $this->local_db->query($cart_sql);
     $cart = $cart_query->result_array();
     $cart_list = [];
     $sale_price = $main_sale_price = $count = 0;

     foreach ($cart as $key => $item) {
       if($current_account_currency === $item["currency"]) {
         $count++;
         $sale_price += $item["sale_price"] * $item["quantity"];
       }
     }

     $user_row["cart_info"] = [
       "sale_price" => $sale_price,
       "count" => $count,
     ];

     return rest_response(
       Status_codes::HTTP_OK,
       lang("Success"),
       $user_row
     );
   }

}
