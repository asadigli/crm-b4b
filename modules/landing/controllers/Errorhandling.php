<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
class Errorhandling extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	function errorCreating() {
		
	}

	function notFound(){
		if (!$this->session->flashdata("error_code")) {
			redirect($this->session->flashdata("error_prev") ? $this->session->flashdata("error_prev") : base_url('/'));
		}
		$page = ['title' => 'Error'];
		$this->view("not_found",['page' => $page]);
		$this->session->set_flashdata("error_mess",null);
		$this->session->set_flashdata("error_code",null);
		$this->session->set_flashdata("error_prev",null);
	}



}
