<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Kontak extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
    $this->load->model('M_Master_Kontak');
    $this->load->model('M_transaksi');
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_Master_Kontak->tambahData();
    }else{
      echo $this->M_Master_Kontak->ubahData();      
    }
   }

   function deletedata(){
    echo $this->M_Master_Kontak->hapusData();          
   }

   function getdata(){
      if($_POST['id'] == '' || $_POST['id'] == null) {
        echo _pesanError("Data tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.kid 'id', A.kkode 'kode', A.knama 'nama', A.ktipe 'idtipe', B.ktnama 'tipe',
                       A.k1alamat 'alamat1', A.k1telp1 'telp1', A.k1fax 'fax1', A.k1kodepos 'kodepos1',
                       A.k1email 'email1', A.k1kontak 'kontak1',
                       IFNULL(A.kpembatashutang,0) 'batashutang',
                       IFNULL(A.kpenbataspiutang,0) 'bataspiutang',                       
                       IFNULL(A.kdiskon,0) 'diskon',                                              
                       A.kpemtermin 'idterminbeli', C.tkode 'terminbeli',
                       A.kpentermin 'idterminjual', D.tkode 'terminjual',
                       A.kpemkaryawan 'idbagbeli', E.knama 'bagbeli',
                       A.kpenkaryawan 'idbagjual', F.knama 'bagjual',
                       A.kpenpenagihan 'idbagtagih', G.knama 'bagtagih',                       
                       A.knpwp 'npwp', A.kpkp 'pkp', A.kjeniskelamin 'kelamin',
                       A.kmatauang 'iduang', H.ukode 'uang',
                       A.kbank 'idbank', I.bkode 'bank',
                       A.knoac 'norekbank', A.kpemrekhutang 'namarek',
                       A.kpenlevelharga 'levelhargajual',
                       J.ipunotransaksi 'nomorsa', DATE_FORMAT(J.iputanggal,'%d-%m-%Y') 'tanggalsa', J.iputermin 'idterminsa',
                       J.iputotaltransaksi 'jumlahsa', J.ipuuraian 'catatansa', K.tnama 'terminsa'                                              
                  FROM bkontak A
             LEFT JOIN bkontaktipe B ON A.ktipe=B.ktid 
             LEFT JOIN btermin C ON A.kpemtermin=C.tid 
             LEFT JOIN btermin D ON A.kpentermin=D.tid
             LEFT JOIN bkontak E ON A.kpemkaryawan=E.kid              
             LEFT JOIN bkontak F ON A.kpenkaryawan=F.kid                           
             LEFT JOIN bkontak G ON A.kpenpenagihan=G.kid
             LEFT JOIN buang H ON A.kmatauang=H.uid  
             LEFT JOIN bbank I ON A.kbank= I.bid
             LEFT JOIN einvoicepenjualanu J ON A.kid=J.ipukontak AND J.ipusaldoawal=1   
             LEFT JOIN btermin K ON J.iputermin=K.tid                                       
                 WHERE A.kid='".$_POST['id']."'";
     
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }


}