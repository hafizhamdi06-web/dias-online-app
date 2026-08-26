<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page_Starter extends CI_Controller {

    function __construct(){
	    header('Access-Control-Allow-Origin: *');
	    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");    	
        parent::__construct();     
		$this->load->helper('url');		        
		if(!$this->session->has_userdata('nama')){
            redirect(base_url());
		}		
    }

	function index()
	{
		$appName = $this->config->item('app_name');
		$data['title'] = 'Page Starter | '.$appName;
		$data['dasbor_msg'] = $this->config->item('dasbor_msg');
        $this->load->view('include/header', $data);			
		$this->load->view('include/first_page', $data);
        $this->load->view('include/footer', $data);
	}

}