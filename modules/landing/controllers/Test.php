<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Test extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$whitelist = array('127.0.0.1','::1');
		if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist) || !$this->developer) {
			redirect(base_url('/'));
		}
	}

	function getRouteTest($numb){
		$list = [];
		for ($i=0; $i < ($numb ? $numb : 1); $i++) {
			$items = ['','az','en','ru','tr'];
			$items[array_rand($items)];
			$list[] = Base::getRoute('my-garage',$items[array_rand($items)]);
			base_url("terms-and-conditions");
			langSwitcher('/','tr');
		}
		return 'ok';
	}

	function getFunctionSpeed(){
		$startTime = microtime(true);
		$this->getRouteTest(190);
		return number_format(( microtime(true) - $startTime), 6);
		// "Time:  " .  . " Seconds\n";
	}

	function getFunctionSpeed2(){
		$startTime = microtime(true);
		$this->getRouteTest(190);
		return number_format(( microtime(true) - $startTime), 6);
		// "Time:  " .  . " Seconds\n";
	}

	function testing(){

		// echo readCacheInJson('menuCatalog',['hellott']);die;
		header('Content-Type: application/json');
		$this->load->model('product/Product_model','model');
		echo json_encode($this->model->regions());die;
		// var_dump(langSwitcher(null,'az',$path));die;
		var_dump(base_url('error','az'));
	}
}
