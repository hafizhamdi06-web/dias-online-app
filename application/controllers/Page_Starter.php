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

	function rekapaktivitas()
	{
		$iduser = $this->session->id;

		$data = array(
			'nama' => @$_SESSION['nama'],
			'namagudang' => @$_SESSION['namagudang'],
			'namabulanini' => $this->_namaBulanTahun(date('Y-m-01')),
			'namabulanlalu' => $this->_namaBulanTahun(date('Y-m-01', strtotime('-1 month'))),
			'fstoku' => $this->_rekapSumber('fstoku', 'SUSUMBER', 'SUCREATEU', 'SUCREATED', $iduser),
			'permintaan' => $this->_rekapSumber('fpermintaanbarangu', 'PBUSUMBER', 'PBUCREATEU', 'PBUCREATED', $iduser),
			'salesorder' => $this->_rekapSumber('esalesorderu', 'SOUSUMBER', 'SOUCREATEU', 'SOUCREATED', $iduser),
			'invoice' => $this->_rekapSumber('einvoicepenjualanu', 'IPUSUMBER', 'IPUCREATEU', 'IPUCREATED', $iduser),
			'kaskecil' => $this->_rekapSumber('ctransaksiu', 'CUSUMBER', 'CUCREATEU', 'CUCREATED', $iduser),
			'piutang' => $this->_rekapSumber('ctransaksipu', 'CUSUMBER', 'CUCREATEU', 'CUCREATED', $iduser)
		);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	private function _namaBulanTahun($tanggal)
	{
		$namaBulan = array(
			1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
			5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
			9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
		);

		$ts = strtotime($tanggal);
		return $namaBulan[(int) date('n', $ts)].' '.date('Y', $ts);
	}

	private function _rekapSumber($table, $colsumber, $colcreateu, $colcreated, $iduser)
	{
		$labelMap = $this->_labelSumberMap();

		$sql = "SELECT A.$colsumber 'sumber',
					   SUM(CASE WHEN DATE(A.$colcreated)=CURDATE() THEN 1 ELSE 0 END) 'hariini',
					   SUM(CASE WHEN A.$colcreated>=DATE_FORMAT(CURDATE(),'%Y-%m-01') THEN 1 ELSE 0 END) 'bulanini',
					   SUM(CASE WHEN A.$colcreated>=DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01')
								 AND A.$colcreated<DATE_FORMAT(CURDATE(),'%Y-%m-01') THEN 1 ELSE 0 END) 'bulanlalu'
				  FROM $table A
				 WHERE A.$colcreateu=?
				   AND A.$colcreated>=DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01')
			  GROUP BY A.$colsumber
			  ORDER BY bulanini DESC, bulanlalu DESC";

		$rows = $this->db->query($sql, array($iduser))->result_array();

		foreach ($rows as &$r) {
			$r['label'] = isset($labelMap[$r['sumber']]) ? $labelMap[$r['sumber']] : $r['sumber'];
			$r['hariini'] = (int) $r['hariini'];
			$r['bulanini'] = (int) $r['bulanini'];
			$r['bulanlalu'] = (int) $r['bulanlalu'];
		}

		return $rows;
	}

	private function _labelSumberMap()
	{
		$map = array();

		$rows = $this->db->query("SELECT NKODE, NKETERANGAN FROM aanomor")->result_array();

		foreach ($rows as $r) {
			if (!isset($map[$r['NKODE']])) {
				$map[$r['NKODE']] = $r['NKETERANGAN'];
			}
		}

		return $map;
	}

}