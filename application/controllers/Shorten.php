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
		$link_length = $this->config->item('link_length');
		$existing_alias = $this->short->alias_from_url($url);
		$short_url = "";

		if($existing_alias == "") {
			$this->load->helper('string');
			// generate alias from Alpha-numeric string with lower and uppercase characters
			$alias = random_string('alnum', $link_length);

			while($this->short->does_alias_exist($alias)) {
				$alias = random_string('alnum', $link_length);
			}


			$this->short->save_new_alias(addhttp($url), $alias);
			$short_url = $alias;
		}else {
			$short_url = $existing_alias;
		}

		echo json_encode(array('result'=>base_url() . $short_url));
		
	}

	// function addhttp($url) {
	//     if (!preg_match("@^[hf]tt?ps?://@", $url)) {
	//         $url = "http://" . $url;
	//     }
	//     return $url;
	// }
}