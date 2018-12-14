<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shorten extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('shortenmodel', 'short');
	}

	function index() {
		$this->load->view('form');
	}

	function create() {
		$url = $this->input->post('url');
		// length of alias url
		$link_length = 5;
		$existing_alias = $this->short->alias_from_url($url);
		$short_url = "";

		if($existing_alias == "") {
			$this->load->helper('string');
			// generate alias from Alpha-numeric string with lower and uppercase characters
			$alias = random_string('alnum', $link_length);

			while($this->short->does_alias_exist($alias)) {
				$alias = random_string('alnum', $link_length);
			}

			$this->short->save_new_alias($url, $alias);
			$short_url = $alias;
		}else {
			$short_url = $existing_alias;
		}

		echo base_url() . $short_url;
		
	}


	
}