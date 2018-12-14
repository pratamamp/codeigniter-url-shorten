<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class ShortenModel extends CI_Model {


	function does_alias_exist($alias){
		$this->db->select('id');

		$query = $this->db->get_where('links', array('alias' => $alias), 1, 0);

		if ($query->num_rows() > 0){
	    	return true;
	    }else{
	    	return false;
		}
    }

    function save_new_alias($url, $alias) {
    	$data = array(
    			'alias'=>$alias,
    			'url'=>$url,
    			'created'=>date('Y-m-d H:i:s'));
    	return $this->db->insert('links', $data);
    }

    function alias_from_url($url) {
    	$alias = "";
    	$this->db->select('alias');
    	$query = $this->db->get_where('links', array('url'=>$url), 1, 0);

    	if($query->num_rows() > 0 ) {
    		foreach ($query->result() as $row) {
    			$alias = $row->alias;
    		}
    	}

    	return $alias;
    }

    function log_redirect() {
    	$geoLocation = $this->getLocationInfoByIP(@$_SERVER['REMOTE_ADDR']);
    	$data = array(
    			'datetime'=>date('Y-m-d H:i:s'),
    			'ip_address'=>$this->input->ip_address(),
    			'browser_agent'=>$this->input->user_agent(),
    			'url_string' =>$this->uri->uri_string(),
    			'ip_country' =>$geoLocation['country']
    		);
    	return $this->db->insert('redirects', $data);
    }

    function getLocationInfoByIP($ip_addr) {
    	$return_data  = array('country'=>'', 'city'=>'');
	    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip_addr));
	    if($ip_data && $ip_data->geoplugin_countryName != null)
	    {
	        $return_data['country'] = $ip_data->geoplugin_countryCode;
	        $return_data['city'] = $ip_data->geoplugin_city;
	    }
	    return $return_data;
    }
}