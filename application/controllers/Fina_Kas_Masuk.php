<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fina_Kas_Masuk extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }         
		$this->load->model('M_transaksi'); 
    $this->load->model('M_Fina_Kas_Masuk');
   }

   function savedata(){
    if($this->input->post('id')==''){
      echo $this->M_Fina_Kas_Masuk->tambahTransaksi();
    }else{
      echo $this->M_Fina_Kas_Masuk->ubahTransaksi();      
    }
   }

   function deletedata(){
    echo $this->M_Fina_Kas_Masuk->hapusTransaksi();
   }

   function getketerangan(){
      $query = "SELECT NKETERANGAN 'keterangan' FROM aanomor WHERE NID='".element('Fina_Kas_Masuk',NID)."'";
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

   function getdata(){
      $transcode = $this->M_transaksi->prefixtrans(element('Fina_Kas_Masuk',NID));        
   		$query = "SELECT A.cuid 'id', A.cunotransaksi 'nomor', 
   						 DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',
   						 A.cukontak 'kontakid', B.kkode 'kontakkode', B.knama AS 'kontak', A.cuuraian 'uraian',
   						 A.curekkas 'coadbid',E.cnocoa 'coadbkode', E.cnama 'coadb', A.cuuang 'iduangdebet', C.usimbol 'uangdebet', A.cukurs 'kursdebet',
   						 IFNULL(A.cutotaltrans,0) 'total', 
               IFNULL(A.cutotaltransv,0) 'valas', 
               A.cutipe 'tipebayar', A.custatus 'status',
   						 D.cdid 'iddetil', D.cdnocoa 'coadetilid', F.cnama 'coadetil', 
               IFNULL(D.cdkredit,0) 'jmldetil', 
               IFNULL(D.cdkreditv,0) 'jmldetilv',
   						 D.cduang 'iduangdetil', G.usimbol 'uangdetil', D.cdkurs 'kursdetil', D.cdcatatan 'catatandetil',
   						 D.cddvisi 'divisidetilid', H.dkode 'divisidetil', D.cdproyek 'proyekdetilid', I.pkode 'proyekdetil' 
                    FROM ctransaksiu A 
               LEFT JOIN bkontak B ON A.cukontak=B.kid
               LEFT JOIN buang C ON A.cuuang=C.uid
               LEFT JOIN ctransaksid D ON A.cuid=D.cdidu 
               LEFT JOIN bcoa E ON A.curekkas=E.cid
               LEFT JOIN bcoa F ON D.cdnocoa=F.cid
               LEFT JOIN buang G ON D.cduang=G.uid  
               LEFT JOIN bdivisi H ON D.cddvisi=H.did
               LEFT JOIN bproyek I ON D.cdproyek=I.pid
                   WHERE A.cusumber='".$transcode."' AND D.cddebit=0";

      if(!empty($_POST['id'])) {
          $query .= " AND A.cuid='".$this->input->post('id')."'";
      }else{
          $query .= " AND A.cunotransaksi='".$this->input->post('nomor')."'";
      }

      $query .= " ORDER BY D.cdid ASC ";
       
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

}