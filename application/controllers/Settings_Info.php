<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_Info extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
    $this->load->model('M_Settings_Info');
    $this->load->model('M_transaksi');
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_Settings_Info->tambahData();
    }else{
      echo $this->M_Settings_Info->ubahData();      
    }
   }

   function deletedata(){
    echo $this->M_Settings_Info->hapusData();          
   }

   function saveperiode(){
    echo $this->M_Settings_Info->simpanPeriode();
   }

   function getdata(){
      $query = "SELECT A.iid 'id', A.inama 'nama', A.ialamat1 'alamat1', A.ialamat2 'alamat2',
                       A.ikota 'kota', A.ipropinsi 'propinsi', A.ikodepos 'kodepos', A.inegara 'negara',
                       A.itelepon1 'telp1', A.itelepon2 'telp2', A.ifaks 'faks', A.iemail 'email', 
                       A.ibulanaktif 'bulan', A.itahunaktif 'idtahun', B.ptahun 'tahun', CONCAT_WS(' - ',C.ukode,C.unama) 'uang',
                       A.icetakpos 'icetakpos', A.ibarcodepos 'ibarcodepos', A.ipajakpos 'ipajakpos', A.ikontakpos 'ikontakpos', 
                       D.kkode 'kontakkode', D.knama 'kontaknama', A.ippnpos 'idppnpos', E.pkode 'ippnpos',
                       A.ipajakbeli 'ipajakbeli', A.ippnbeli 'idppnbeli', F.pkode 'ippnbeli', A.ipph22beli 'idpph22beli', G.pkode 'ipph22beli',
                       A.ipajakbeli 'ipajakjual', A.ippnbeli 'idppnjual', H.pkode 'ippnjual', A.ipph22beli 'idpph22jual', I.pkode 'ipph22jual',
                       A.idivisi 'idivisi', A.isatuan 'isatuan', A.iproyek 'iproyek', A.imatauang 'imatauang', A.idecimalqty 'idecimalqty'   
                  FROM ainfo A LEFT JOIN cperiode B ON A.itahunaktif=B.pid LEFT JOIN buang C ON A.iuang=C.uid
             LEFT JOIN bkontak D ON A.ikontakpos=D.kid
             LEFT JOIN bpajak E ON A.ippnpos=E.pid
             LEFT JOIN bpajak F ON A.ippnbeli=F.pid             
             LEFT JOIN bpajak G ON A.ipph22beli=G.pid
             LEFT JOIN bpajak H ON A.ippnjual=H.pid
             LEFT JOIN bpajak I ON A.ipph22jual=I.pid                                                                 
             ";
     
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

}