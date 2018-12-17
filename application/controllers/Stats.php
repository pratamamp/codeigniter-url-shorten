<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stats extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        $this->load->model('shortenmodel', 'short');
    }

    public function index($country=false){
        $this->load->view('stats', ['country'=>$country], false);
    }

    function json($country=false) {
        error_reporting(E_ALL);
        if($country) {
            $list = $this->short->stats_by_('country');
        }else {
            $list = $this->short->stats_by_('links');
        }
        $data = array('data'=>$list);
        echo json_encode($data);
    }

}

/* End of file Stats.php */
    /* Location: ./application/controllers/Stats.php */