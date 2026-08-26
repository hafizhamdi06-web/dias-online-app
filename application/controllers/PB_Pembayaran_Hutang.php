<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PB_Pembayaran_Hutang extends CI_Controller {

   function __construct() { 
  		parent::__construct();
      if(!$this->session->has_userdata('nama')){
        redirect(base_url('exception'));
      }          
  		$this->load->model('M_transaksi');
      $this->load->model('M_PB_Pembayaran_Hutang');
   }

   function savedata(){
      if($_POST['id']==''){
        echo $this->M_PB_Pembayaran_Hutang->tambahTransaksi();
      }else{
        echo $this->M_PB_Pembayaran_Hutang->ubahTransaksi();      
      }
   }

   function deletedata(){
      echo $this->M_PB_Pembayaran_Hutang->hapusTransaksi();          
   }

   function getdata(){

      $transcode = $this->M_transaksi->prefixtrans(element('PB_Pembayaran_Hutang',NID));  
   		$query = "SELECT A.piuid 'id', A.piunotransaksi 'nomor', 
                       DATE_FORMAT(A.piutanggal,'%d-%m-%Y') 'tanggal',
                       A.piukontak 'kontakid', B.kkode 'kontakkode', B.knama AS 'kontak', A.piuuraian 'uraian', A.piustatuspph 'statussetor',
                       A.piucoakas 'coacrid', E.cnama 'coacr', A.piuuang 'iduangkredit', C.usimbol 'uangkredit', A.piukurs 'kurskredit',
                       IFNULL(A.piujmlkas,0) 'total', A.piuakunselisih 'coaselisihid', J.cnama 'coaselisih',
                       IFNULL(A.piujmlkasv,0) 'valas', A.piubank 'idbank', I.bkode 'bank',
                       A.piutipe 'tipebayar', A.piustatus 'status', IFNULL(D.piitipe,1) 'tipeinv',
                       A.piupph 'idpph',A.piunilaibuktipotong 'totalpajak',A.piubuktipph 'nomorbupot',
                       H.pkode 'namapajak', A.piutotalretur 'totalretur', A.piutotalpiutang 'totalpembayaran',
                       A.piudiskon 'totaldiskon', D.piiid 'iddetil', 
                       CASE WHEN D.piitipe=0 THEN D.piiiddp ELSE D.piiidinvoice END 'idinv', 
                       CASE WHEN D.piitipe=0 THEN G.dpnotransaksi ELSE F.ipunotransaksi END 'nomorinv', 
                       CASE WHEN D.piitipe=0 THEN DATE_FORMAT(G.dptanggal,'%d-%m-%Y') ELSE DATE_FORMAT(F.iputanggal,'%d-%m-%Y') END 'tglinv',
                       CASE WHEN D.piitipe=0 THEN IFNULL(G.dpjumlah,0) ELSE (IFNULL(F.iputotaltransaksi,0)-IFNULL(F.ipujumlahdp,0)) END 'totaltrans',
                       CASE WHEN D.piitipe=0 THEN IFNULL(G.dpjumlahbayar,0) ELSE IFNULL(F.iputotalbayar,0) END 'terbayar',
                       IFNULL(D.piijmlbayar,0) 'totalbayar', IFNULL(D.piijmldiskon,0) 'jmldiskon', IFNULL(D.piijmlkasbank,0) 'jmlkasbank',
                       IFNULL(D.piijmlretur,0) 'jmlretur'               
                  FROM epembayaraninvoiceu A 
             LEFT JOIN bkontak B ON A.piukontak=B.kid
             LEFT JOIN buang C ON A.piuuang=C.uid
             LEFT JOIN epembayaraninvoicei D ON A.piuid=D.piiidu 
             LEFT JOIN bcoa E ON A.piucoakas=E.cid
             LEFT JOIN einvoicepenjualanu F ON D.piiidinvoice=F.ipuid
             LEFT JOIN ddp G ON D.piiiddp=G.dpid 
             LEFT JOIN bpajak H ON A.piupph=H.pid  
             LEFT JOIN bbank I ON A.piubank=I.bid    
             LEFT JOIN bcoa J ON A.piuakunselisih=J.cid                                                             
                 WHERE A.piusumber='".$transcode."'";

      if(!empty($_POST['id'])) {
          $query .= " AND A.piuid='".$_POST['id']."'";
      }else{
          $query .= " AND A.piunotransaksi='".$_POST['nomor']."'";
      }

      $query .= " ORDER BY D.piiid ASC";

      $query2 ="SELECT A.piuid 'id', B.piridreturpembelian 'idretur', C.ipunotransaksi 'noretur', 
                       DATE_FORMAT(C.iputanggal,'%d-%m-%Y') 'tglretur',
                       IFNULL(C.iputotaltransaksi,0) 'totaltransr', 
                       IFNULL(C.iputotalbayar,0) 'terbayarr',
                       IFNULL(B.pirjmlbayar,0) 'totalpotong'      
                  FROM epembayaraninvoiceu A
             LEFT JOIN epembayaraninvoicer B ON A.piuid=B.piridu
             LEFT JOIN einvoicepenjualanu C ON B.piridreturpembelian=C.ipuid
                 WHERE A.piusumber='".$transcode."'";

      if(!empty($_POST['id'])) {
          $query2 .= " AND A.piuid='".$_POST['id']."'";
      }else{
          $query2 .= " AND A.piunotransaksi='".$_POST['nomor']."'";
      }

      $query2 .= " ORDER BY B.pirid ASC";
      
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query_second($query,$query2);
   }

   function getdatafaktur(){
      if(empty($_POST['id'])) {
        echo _pesanError("Id transaksi tidak ditemukan !");
        exit;
      }

      $query = "SELECT * FROM (
                SELECT 1 'tipe',A.ipuid 'id', A.ipunotransaksi 'nomor', DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tgl',
                       (IFNULL(A.iputotaltransaksi,0)-IFNULL(A.ipujumlahdp,0)) 'totaltrans', IFNULL(A.iputotalbayar,0) 'terbayar',
                       (IFNULL(A.iputotaltransaksi,0)-IFNULL(A.ipujumlahdp,0)-IFNULL(A.iputotalbayar,0)) 'totalbayar'
                  FROM einvoicepenjualanu A
                 UNION
                SELECT 0 'tipe',A.dpid 'id', A.dpnotransaksi 'nomor', DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tgl',
                       IFNULL(A.dpjumlah,0) 'totaltrans', IFNULL(A.dpjumlahbayar,0) 'terbayar',
                       IFNULL(A.dpjumlah,0)-IFNULL(A.dpjumlahbayar,0) 'totalbayar'
                  FROM ddp A
                ) T                                        
                 WHERE T.nomor IN (".$_POST['id'].")";

      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

   function getdataretur(){
      if(empty($_POST['id'])) {
        echo _pesanError("Id transaksi tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.ipuid 'id', A.ipunotransaksi 'nomor', DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tgl',
                       A.iputotaltransaksi 'totaltrans', A.iputotalbayar 'terbayar',
                       (IFNULL(A.iputotaltransaksi,0)-IFNULL(A.iputotalbayar,0)) 'totalpotong'
                  FROM einvoicepenjualanu A
                 WHERE A.ipunotransaksi IN (".$_POST['id'].")";

      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

}