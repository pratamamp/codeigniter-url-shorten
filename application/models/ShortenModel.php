<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class ShortenModel extends CI_Model {

	private $api_url = 'http://www.geoplugin.net/json.gp';
	private $header = array('Except:');


	function does_alias_exist($alias){
		$this->db->select('id');

		$query = $this->db->get_where('links', array('alias' => $alias), 1, 0);

		if ($query->num_rows() > 0){
	    	return true;
	    }else{
	    	return false;
		}
    }

    function url_from_alias($alias) {
        return $this->db->get_where('links', array('alias'=>$alias))
                        ->result()[0];
    }

    function save_new_alias($url, $alias) {
    	$data = array(
    			'alias'=>$alias,
    			'url'=>$url,
    			'created'=>date('Y-m-d H:i:s'));
    	return $this->db->insert('links', $data);
    }

    function alias_from_url($url) {
    	return $this->db->select('alias')
    			 ->get_where('links', array('url'=>$url), 1, 0)
    			 ->row();
    }

    function log_redirect() {
    	$geoLocation = array('country'=>'', 'city'=>'');
    	$geoLocation = $this->getLocationInfoByIP($this->input->ip_address());
    	$data = array(
    			'datetime'=>date('Y-m-d H:i:s'),
    			'ip_address'=>$this->input->ip_address(),
    			'browser_agent'=>$this->input->user_agent(),
    			'url_string' =>$this->uri->uri_string(),
    			'ip_country' =>isset($geoLocation['country']) ? $geoLocation['country'] : ' ', 
    		);
    	$insert = $this->db->insert('redirects', $data);
    	if($insert) {
            $uri = str_replace('api/', "", $this->uri->uri_string());
    		if($this->db->get_where('links',array('alias'=>$uri))
    					->num_rows() >0) {
	    		$data = array('alias'=>$uri);
	    		$sql = $this->db->insert_string('links', $data). 'ON DUPLICATE KEY UPDATE hit_count=hit_count+1';
	    		$this->db->query($sql);
	    	}

    	}
    }

    function getLocationInfoByIP($ip_addr) {
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $this->api_url."?ip=".$ip_addr);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    	$result = curl_exec($ch);
    	curl_close($ch);
    	return @json_encode($result);

    }

    function find_links($alias) {
    	$query = $this->db->get_where('links', array('alias'=>$alias), 1, 0);
    	// print_r($query->result());exit;
    	if($query->num_rows() > 0) {
    		foreach ($query->result() as $row) {
    			redirect($row->url, 'refresh');
    		}
    	}else {
    		echo "Error, link '$alias' not found";
    	}
    }

    function stats_by_($countrylinks) {
    	if($countrylinks === "links") {
    		return $this->db->order_by('hit_count', 'desc')
    				 ->get('links')
    				 ->result();
    	}else {
            return $this->db->select('ip_country,url_string, COUNT(*) as number')
                            ->group_by('ip_country')
                            ->order_by('number', 'desc')
                            ->get('redirects')
                            ->result();
    	}
    }

  
}