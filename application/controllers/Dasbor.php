<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dasbor extends CI_Controller {

	function __construct() {
	    header('Access-Control-Allow-Origin: *');
	    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");	 
		parent::__construct();
		$this->load->model('M_transaksi');
	}

	function index()
	{
		if($this->session->has_userdata('nama')){
			$company = $this->M_transaksi->column_value('inama','aainfo', array('iid' => 1));
			foreach ($company->result() as $row) {
				$data['copy'] = $row->inama;
				$data['vendor_text'] = $row->inama;								
			}

	        $info = _ainfo(1);
	        $data['decimalqty'] = $info['idecimalqty'];
			$data['app_name'] = $this->config->item('app_name');
			$data['title'] = 'Dasbor | '.$data['app_name'];
			$data['title2'] = 'Dasbor';			
			$data['versi'] = $this->config->item('versi');
			$data['app_url'] = $this->config->item('app_url');			

			$this->load->helper('url');		
            $this->load->view('include/header', $data);			
			$this->load->view('dasbor', $data);
	        $this->load->view('include/sidebar', $data);					
            $this->load->view('include/footer', $data);
		}else {		
            redirect(base_url());
		}
	}

	function refreshsidebarmenu()
	{
		$company = $this->M_transaksi->column_value('inama','aainfo', array('iid' => 1));
		foreach ($company->result() as $row) {
			$data['copy'] = $row->inama;
			$data['vendor_text'] = $row->inama;								
		}		
		$data['app_name'] = $this->config->item('app_name');
		$data['title'] = 'Dasbor | '.$data['app_name'];
		$data['title2'] = 'Dasbor';					
		$data['versi'] = $this->config->item('versi');
		$data['app_url'] = $this->config->item('app_url');					
        $this->load->view('include/sidebar', $data);		
	}

	function getmenutransaksi(){
      $query = "SELECT A.* FROM aamenu A INNER JOIN aausermenu B ON A.MID=B.AUIDMENU AND B.AUIDUSER=".$this->session->id." 
      			 WHERE A.mtype=2 AND A.mactive=1 ORDER BY A.murutan ASC, A.mparent ASC";
     
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);		
	}

	function getchildmenu(){
      $query = "SELECT A.* FROM aamenu A INNER JOIN aausermenu B ON A.MID=B.AUIDMENU AND B.AUIDUSER=".$this->session->id." 
      			 WHERE A.mparent=".$this->input->post('id')." ORDER BY A.murutan ASC";
     
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);		
	}

}