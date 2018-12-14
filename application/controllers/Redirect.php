<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Redirect extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('shortenmodel', 'short');
	}

	function index() {
		$this->short->log_redirect();
	}
}