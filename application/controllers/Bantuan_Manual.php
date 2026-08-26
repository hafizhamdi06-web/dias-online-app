<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bantuan_Manual extends CI_Controller {

    function __construct(){
        parent::__construct();     
		$this->load->helper('url');		        
		if(!$this->session->has_userdata('nama')){
            redirect(base_url());
		}		
    }

	function index()
	{

	}

}