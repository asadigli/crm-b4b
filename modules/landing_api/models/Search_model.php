<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  function searchSuggestions($keyword = NULL){
    if (!$keyword) {
      $msg = $this->lang->line("Please, enter a keyword");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }

    $exist = $this->db->select("name")
                      ->from('products')
                      ->or_group_start()
                        ->like('code',$keyword)
                        ->or_like('name',$keyword)
                        ->or_like('oem',$keyword)
                      ->group_end()
                      ->where('deleted_at',NULL)
                      ->get();
    $names = $exist->result();
    return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),$names);
  }



  /*
  *   Listing products based on search result
  */

  function findByAjax($keyword = null,$min = null,$max = null,$division = null,$category = null,$subcategory = null,$car_brand = null,$car_model = null,$condition = null,$date){
    if (!$keyword) {
      $msg = $this->lang->line("Please, enter a keyword");
      return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
    }
    $min_query = $min ? " price >= $min" : " id > 0 ";
    $max_query = $max ? " price <= $max" : " id > 0 ";
    $division_query = $division ? " division_id = $division " : " id > 0 ";
    $category_query = $category ? " category_id = $category " : " id > 0 ";
    $subcategory_query = $subcategory ? " subcat_id = $subcategory " : " id > 0 ";

    $condition_query = $condition ? " `condition` = '$condition' " : " id > 0 ";
    $car_brand_query = $car_brand ? "" : " id > 0 ";
    $car_model_query = $car_model ? "" : " id > 0 ";
    $product_query = $this->db->select("id as `key`,name as product_name,
                                          '₼' as currency,slug,code,oem,new_price,price,
                                          quantity,division_id,category_id,subcat_id,city_id,
                                          description,created_at as added,condition")
                                  ->from("products")
                                  ->group_start()
                                      ->like('oem',$keyword)
                                      ->or_like('name',$keyword)
                                      ->or_like('code',$keyword)
                                  ->group_end()
                                  ->where('deleted_at',NULL)
                                  ->where($min_query, NULL, FALSE)
                                  ->where($max_query, NULL, FALSE)
                                  ->where($division_query,NULL,FALSE)
                                  ->where($category_query,NULL,FALSE)
                                  ->where($subcategory_query,NULL,FALSE)
                                  ->where($condition_query,NULL,FALSE)
                                  ->where($car_brand_query,NULL,FALSE)
                                  ->where($car_model_query,NULL,FALSE)
                                  ->get();
    $product = $product_query->result();

    if (strlen($keyword) > 2) {
      $exist_tag = $this->db->select("id")->from("sa_searched_tags")->where('keyword',$keyword)->get();
      if ($exist_tag->num_rows()) {
        $this->db->where('id', $exist_tag->row()->id);
        $this->db->set('count', 'count+1', FALSE);
        $this->db->set('updated_at', $date);
        $this->db->update('sa_searched_tags');
      }else{
        $this->db->insert('sa_searched_tags',['keyword' => $keyword,'count' => 1]);
      }
    }

    return rest_response(Status_codes::HTTP_OK, $this->lang->line("Success"), $product);
  }


  /*
  *   Listing part names with number of products based on search result
  */

  // function panelSearchCategories($model = null,$marka = null,$year = null){
  //   $categories = Base::callApi("GET",null,[],'https://api.avtohisse.com/catalog/?type=partsgroup');
	// 	if (!isset($categories['message']) || $categories['message'] !== 'Success') {
  //     return rest_response(Status_codes::HTTP_CONFLICT, 'not accessible', []);
	// 	}
  //   $list = [
  //     'model' => $model,
  //     'marka' => $marka,
  //     'year' => $year,
  //   ];
  //   $oems = Base::callApi("GET",null,$list,'https://data.cisct.net/services/oem_data');
  //   if (!isset($oems['data'])) {
  //     return rest_response(Status_codes::HTTP_CONFLICT,'Data service error', []);
  //   }
  //   $new_list = [];
  //   foreach ($categories['firstgroup'] as $key => $cat) {
  //     $prods = $this->db->select("COUNT(id) as count")
  //                       ->from("products")->where('division_id',$cat['id'])
  //                         ->where('deleted_at',null)
  //                           ->where_in('oem',$oems['data'])
  //                             ->get();
  //     // bin2hex
  //     // $unique_key = base64_encode($cat['id'].'+'.date('d').'+'.$model.'+'.round($model)*round($marka).'+'.$marka.'+'.date('m').'+'.$year);
  //     $unique_key = base64_encode($model.','.$marka.','.$year);
  //     $new_list[] = [
  //       'key' => $unique_key,
  //       // 'unique_key' => base64_decode($unique_key),
  //       'name' => $cat['name_az'],
  //       'count' =>  $prods->row()->count
  //     ];
  //   }
  //   // sorting list by number of product
  //   usort($new_list, function($a, $b) {return $a['count'] < $b['count'];});
  //   return rest_response(Status_codes::HTTP_OK, $this->lang->line("Success"), $new_list);
  // }


  /*
  *  Listing products based on part
  */
  // function listProducts($unique_key = null){
  //   if (!$unique_key) {
  //     $msg = "Key is required";
  //     return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
  //   }
  //   if (!isset(explode(',',base64_decode($unique_key))[0])
  //         || !isset(explode(',',base64_decode($unique_key))[1])
  //             || !isset(explode(',',base64_decode($unique_key))[2])) {
  //     $msg = "Undefined key";
  //     return rest_response(Status_codes::HTTP_CONFLICT,$msg,[]);
  //   }
  //   $model = explode(',',base64_decode($unique_key))[0];
  //   $marka = explode(',',base64_decode($unique_key))[1];
  //   $year = explode(',',base64_decode($unique_key))[2];
  //
  //   $list = [
  //     'model' => $model,
  //     'marka' => $marka,
  //     'year' => $year,
  //   ];
  //   $oems = Base::callApi("GET",null,$list,'https://data.cisct.net/services/oem_data');
  //   if (!isset($oems['data'])) {
  //     return rest_response(Status_codes::HTTP_CONFLICT,'Data service error',[]);
  //   }
  //   $prods_query = $this->db->select("*")
  //                             ->from("products")
  //                               ->where_in('oem',$oems['data'])
  //                                 ->where('deleted_at',null)
  //                                   ->get();
  //   $prods = $prods_query->result();
  //   return rest_response(Status_codes::HTTP_OK,$this->lang->line("Success"),$prods);
  // }

}
