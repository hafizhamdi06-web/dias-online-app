<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fina_Jurnal_Umum extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
		$this->load->model('M_transaksi');
    $this->load->model('M_Fina_Jurnal_Umum');
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_Fina_Jurnal_Umum->tambahTransaksi();
    }else{
      echo $this->M_Fina_Jurnal_Umum->ubahTransaksi();      
    }
   }

   function deletedata(){
    echo $this->M_Fina_Jurnal_Umum->hapusTransaksi();          
   }

   function getdata(){
      $transcode = element('Fina_Jurnal_Umum',NID);
      $transcode = $this->M_transaksi->prefixtrans($transcode);        
   		$query = "SELECT A.cuid 'id', A.cunotransaksi 'nomor', 
   						 DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',
   						 A.cukontak 'kontakid', B.kkode 'kontakkode', B.knama AS 'kontak', A.cuuraian 'uraian',
   						 A.curekkas 'coadbid', E.cnama 'coadb', A.cuuang 'iduangdebet', C.usimbol 'uangdebet', A.cukurs 'kursdebet',
   						 A.cutotaltrans 'total', A.cutotaltransv 'valas', A.cutipe 'tipebayar', A.custatus 'status',
   						 D.cdid 'iddetil', D.cdnocoa 'coadetilid', F.cnama 'coadetil', 
               IFNULL(D.cddebit,0) 'debit', 
               IFNULL(D.cdkredit,0) 'kredit', 
               IFNULL(D.cddebitv,0) 'debitv', 
               IFNULL(D.cdkreditv,0) 'kreditv',
   						 D.cduang 'iduangdetil', G.usimbol 'uangdetil', D.cdkurs 'kursdetil', D.cdcatatan 'catatandetil',
   						 D.cddvisi 'divisidetilid', H.dkode 'divisidetil', D.cdproyek 'proyekdetilid', I.pkode 'proyekdetil' 
                    FROM ctransaksiu A 
               LEFT JOIN bkontak B ON A.cukontak=B.kid
               LEFT JOIN buang C ON A.cuuang=C.uid
              INNER JOIN ctransaksid D ON A.cuid=D.cdidu 
               LEFT JOIN bcoa E ON A.curekkas=E.cid
               LEFT JOIN bcoa F ON D.cdnocoa=F.cid
               LEFT JOIN buang G ON D.cduang=G.uid  
               LEFT JOIN bdivisi H ON D.cddvisi=H.did
               LEFT JOIN bproyek I ON D.cdproyek=I.pid
                   WHERE A.cusumber='".$transcode."'";

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