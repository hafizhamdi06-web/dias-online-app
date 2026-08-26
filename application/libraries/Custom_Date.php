<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Custom_Date {

        public function tgl_database($tgl)
        {
			$tanggal=substr($tgl,0,2);
			$bulan=substr($tgl,3,2);
			$tahun=substr($tgl,6,4);
			return $tahun.'-'.$bulan.'-'.$tanggal;        	
        }  

        public function tgl_notrans($tgl){
			$tahun=substr($tgl,8,2);
			$bulan=substr($tgl,3,2);
			return $tahun.$bulan;						        	
        }      

}