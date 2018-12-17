<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class API extends REST_Controller {
	function __construct(){
        // Construct the parent class
        parent::__construct();
        $this->load->model('shortenmodel', 'short');
    }

    function shorten_post() {
    	$http_user_auth   = $this->input->server('PHP_AUTH_USER');
        $http_pass_auth  = $this->input->server('PHP_AUTH_PW');

        if(prepare_basic_auth($http_user_auth, $http_pass_auth)){
        	$url = $this->input->get_post('url');
        	$link_length = $this->config->item('link_length');
        	$existing_alias = $this->short->alias_from_url(addhttp($url));

        	if($existing_alias == "") {
        		$this->load->helper('string');
				// generate alias from Alpha-numeric string with lower and uppercase characters
				$alias = random_string('alnum', $link_length);
				while($this->short->does_alias_exist($alias)) {
					$alias = random_string('alnum', $link_length);
				}
				if($insert = $this->short->save_new_alias(addhttp($url), $alias)) {
					$result = array('status'=>true, 'display_message'=>'Success added data', 'data'=>$alias);
        			$response_code = REST_Controller::HTTP_CREATED;
				}else {
					$result = array('status'=>false, 'display_message'=>'failed added data', 'data'=>'');
        			$response_code = REST_Controller::HTTP_OK;
				}
				
        	}else {
        		$result = array('status'=>false, 'display_message'=>'Data existed!', 'data'=>$existing_alias);
        		$response_code = REST_Controller::HTTP_OK;
        	}
        }else {
        	$result = array('status'=>failed, 'display_message'=>'Error Access!', 'data'=>'');
        	$response_code = REST_Controller::HTTP_OK;
        }

        $this->set_response($result, $response_code);
    }

    function realpath_get() {
    	$http_user_auth   = $this->input->server('PHP_AUTH_USER');
        $http_pass_auth  = $this->input->server('PHP_AUTH_PW');

        if(prepare_basic_auth($http_user_auth, $http_pass_auth)){
	    	$id = $this->get('id');
	    	$links = $this->short->url_from_alias($id);
	    	if($links) {
	    		$this->short->log_redirect();
	    		$links = $this->short->url_from_alias($id);
	    		$result = array('status'=>true, 'display_message'=>'Success', 'data'=>$links);
	        	$response_code = REST_Controller::HTTP_OK;
	    	}else {
	    		$result = array('status'=>failed, 'display_message'=>'Error url not found!', 'data'=>'');
	        	$response_code = REST_Controller::HTTP_OK;
	    	}
	    }else {
	    	$result = array('status'=>failed, 'display_message'=>'Error Access!', 'data'=>'');
        	$response_code = REST_Controller::HTTP_OK;
	    }

    	$this->set_response($result, $response_code);
    }

    function stats_by_country_get() {
    	$http_user_auth   = $this->input->server('PHP_AUTH_USER');
        $http_pass_auth  = $this->input->server('PHP_AUTH_PW');

        if(prepare_basic_auth($http_user_auth, $http_pass_auth)){
	    	$data = $this->db->select('ip_country,url_string, COUNT(*) as number')
	                            ->group_by('ip_country')
	                            ->order_by('number', 'desc')
	                            ->get('redirects')
	                            ->result();
	        if($data) {
	        	$result = array('status'=>true, 'display_message'=>'Success', 'data'=>$data);
		        $response_code = REST_Controller::HTTP_OK;
	        }else {
	        	$result = array('status'=>failed, 'display_message'=>'Error Access!', 'data'=>'');
	        	$response_code = REST_Controller::HTTP_OK;
	        }
	    }else {
	    	$result = array('status'=>failed, 'display_message'=>'Error Access!', 'data'=>'');
        	$response_code = REST_Controller::HTTP_OK;
	    }

        $this->set_response($result, $response_code);
    }

    function stats_by_links_get() {
    	$http_user_auth   = $this->input->server('PHP_AUTH_USER');
        $http_pass_auth  = $this->input->server('PHP_AUTH_PW');

        if(prepare_basic_auth($http_user_auth, $http_pass_auth)){

	    	$data = $this->db->order_by('hit_count', 'desc')
	    				 ->get('links')
	    				 ->result();
	        if($data) {
	        	$result = array('status'=>true, 'display_message'=>'Success', 'data'=>$data);
		        $response_code = REST_Controller::HTTP_OK;
	        }else {
	        	$result = array('status'=>failed, 'display_message'=>'Error Access!', 'data'=>'');
	        	$response_code = REST_Controller::HTTP_OK;
	        }
	    }else {
	    	$result = array('status'=>failed, 'display_message'=>'Error Access!', 'data'=>'');
        	$response_code = REST_Controller::HTTP_OK;
	    }

        $this->set_response($result, $response_code);
    }
}