<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class STK_Penyesuaian_Barang extends CI_Controller {

   function __construct() { 
  		parent::__construct();
      if(!$this->session->has_userdata('nama')){
        redirect(base_url('exception'));
      }          
  		$this->load->model('M_transaksi');
  	  $this->load->model('M_STK_Penyesuaian_Barang');
   }

   function savedata(){
      if($_POST['id']==''){
        echo $this->M_STK_Penyesuaian_Barang->tambahTransaksi();
      }else{
        echo $this->M_STK_Penyesuaian_Barang->ubahTransaksi();      
      }
   }

   function deletedata(){
      echo $this->M_STK_Penyesuaian_Barang->hapusTransaksi();          
   }

   function get_item() {
      $query  = "SELECT A.isatuan AS 'idsatuan', B.snama 'namasatuan', A.ihargabeli 'harga' 
                   FROM bitem A LEFT JOIN bsatuan B ON A.isatuan=B.sid
                  WHERE A.iid='".$_POST['id']."'";
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
    }                   

   function getdata(){
   		if(empty($_POST['id'])) {
   			echo _pesanError("Id transaksi tidak ditemukan !");
  			exit;
   		}

      $transcode = $this->M_transaksi->prefixtrans(element('STK_Penyesuaian_Barang',NID));        
   		$query = "SELECT A.suid 'id', A.sunotransaksi 'nomor', DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',
           						 A.sukontak 'kontakid', B.kkode 'kontakkode', B.knama 'kontak', A.suuraian 'uraian', A.sunoref 'noref', 
                       A.susouid 'orderid', A.sunoreftransaksi 'noreftrans', A.sutermin 'idtermin', C.tkode 'termin', 
                       A.sucatatan 'catatan', A.sualamat 'alamat', A.suattention 'idperson', A.supajak 'pajak', 
                       A.sustatus 'status', A.sunofakturpajak 'nofakturpjk', DATE_FORMAT(A.sutglpajak,'%d-%m-%Y') 'tglpajak',
                       A.sujenispenyesuaian 'idjenis', I.jnama 'jenis',
                       D.sditem 'iditem', E.ikode 'kditem', E.inama 'namaitem', D.sdsatuan 'idsatuan', 
                       D.sdgudang 'idgudang', G.gnama 'gudang', 
                       D.sdproyek 'idproyek', H.pnama 'proyek',
                       D.sdcatatan 'catdetil', F.skode 'satuan', D.sdsodid 'orderdid',
                       IFNULL(D.sdmasuk,0) 'qtydetilin',
                       IFNULL(D.sdkeluar,0) 'qtydetilout',                        
                       IFNULL(D.sdharga,0) 'hargadetil',
                       IFNULL(D.sddiskon,0) 'diskon',
                       0 'persendiskon',
                       ((IFNULL(D.sdharga,0)-IFNULL(D.sddiskon,0))*IFNULL(D.sdmasuk,0)) 'subtotaldetil' 
                    FROM fstoku A 
               LEFT JOIN bkontak B ON A.sukontak=B.kid
               LEFT JOIN btermin C ON A.sutermin=C.tid
               LEFT JOIN fstokd D ON A.suid=D.sdidsu 
               LEFT JOIN bitem E ON D.sditem=E.iid 
               LEFT JOIN bsatuan F ON D.sdsatuan=F.sid 
               LEFT JOIN bgudang G ON D.sdgudang=G.gid 
               LEFT JOIN bproyek H ON D.sdproyek=H.pid
               LEFT JOIN bjenispenyesuaian I ON A.sujenispenyesuaian=I.jid 
                   WHERE A.susumber='".$transcode."' AND A.suid='".$_POST['id']."' ORDER BY D.sdurutan ASC ";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }

}