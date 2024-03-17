<?php

/**
*
*
*/
class Menu_model extends CI_Model
{

	function __construct(){
		parent::__construct();
	}

	function menu(){
		// $res = cacheInJson('parts_group_catalog');
    // if (!$res) {
			$res = Base::callAPI2('GET',$this->config->item('data_cisct_url').'services/parts/group_catalog');
      // writeCacheInJson('parts_group_catalog',$res);
    // }
		return $res;
	}

	function auto_update_catalog(){
		$res = Base::callAPI2('GET',$this->config->item('data_cisct_url').'services/parts/group_catalog');
		if ($res['code'] === 200) {
			writeCacheInJson('parts_group_catalog',$res);
		}
		$brand_result = Base::callAPI2('GET',$this->config->item('data_cisct_url').'services/car_catalog/brands');
		if ($brand_result['code'] === 200) {
			writeCacheInJson('car_brand_list',$brand_result);
		}
		return true;
	}

	function getCurrencies(){
		$curr = cacheInJson('currency_data_session_list');
    if (!$curr) {
			$curr = Base::callAPI2('GET',$this->config->item('data_cisct_url').'services/rates/list');
      writeCacheInJson('currency_data_session_list',$curr);
    }
		if (isset($curr['data']) && $curr['message'] === 'Success') {
			return $curr['data'];
		}
		return ['message' => 'not accessible'];
	}

	// function getPageDetails($slug = null,$lang){
	// 	$pages = $this->db->select('p.*')->from('pages p')
	// 											->where('lang',$lang)->where('slug',$slug)->where('deleted_at',null)->get();
	// 	if ($pages->num_rows() == 0) {
	// 		return ['code' => 404,'message' => 'Page not found'];
	// 	}
	// 	$pg_all = $pages->result();
  //   $page_list = [];
  //   foreach ($pg_all as $key => $pg) {
  //     $tabs = $this->db->select('*')->from('page_tabs')->where('page_id',$pg->id)->where('deleted_at',null)->get();
  //     $page_list = array_merge($page_list,[$pg->lang => [
  //       'id' => $pg->id,
  //       'key' => $pg->key,
  //       'title' => $pg->title,
  //       'slug' => $pg->slug,
  //       'body' => $pg->body,
  //       'footer' => $pg->footer,
  //       'header' => $pg->header,
  //       'order' => $pg->order,
  //       'status' => $pg->status,
  //       'tabs' => $tabs->result(),
  //       'created_at' => $pg->created_at,
  //     ]]);
  //   }
	// 	return ['code' => 200,'data' => $page_list];
	// }

	//
	// function get_footer_header(){
	//
	// }

}
