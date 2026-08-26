<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PB_Retur_Pembelian extends CI_Controller {

   function __construct() { 
  		parent::__construct();
      if(!$this->session->has_userdata('nama')){
        redirect(base_url('exception'));
      }          
  		$this->load->model('M_transaksi');
      $this->load->model('M_PB_Retur_Pembelian');
   }

   function savedata(){
      if($_POST['id']==''){
        echo $this->M_PB_Retur_Pembelian->tambahTransaksi();
      }else{
        echo $this->M_PB_Retur_Pembelian->ubahTransaksi();      
      }
   }

   function deletedata(){
      echo $this->M_PB_Retur_Pembelian->hapusTransaksi();          
   }

   function get_item() {
      $query  = "SELECT A.isatuan AS 'idsatuan', B.snama 'namasatuan', A.ihargabeli 'harga',
                        (SELECT gid FROM bgudang WHERE gdefault=1 LIMIT 1) 'idgudang',
                        (SELECT gnama FROM bgudang WHERE gdefault=1 LIMIT 1) 'gudang'       
                   FROM bitem A LEFT JOIN bsatuan B ON A.isatuan=B.sid
                  WHERE A.iid='".$this->input->post('id')."'";    
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
    }                   

   function getdata(){
      $transcode = $this->M_transaksi->prefixtrans(element('PB_Retur_Pembelian',NID));        
   		$query = "SELECT A.ipuid 'id', A.ipunotransaksi 'nomor', DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
           						 A.ipukontak 'kontakid', B.kkode 'kontakkode', B.knama 'kontak', A.ipuuraian 'uraian',
           						 A.ipukaryawan 'idkaryawan', C.kkode 'kodekaryawan', C.knama 'namakaryawan', 
                       A.iputermin 'idtermin', D.tkode 'termin', A.ipucatatan 'catatan', A.ipunobkg 'bkgid', 
                       I.sunotransaksi 'nomorbkg', A.ipualamat 'alamat', A.ipuattention 'idperson', 
                       E.kanama 'person', A.ipujenispajak 'pajak', A.ipustatus 'status', A.ipunofakturpajak 'nofakturpjk', 
                       DATE_FORMAT(A.iputglpajak,'%d-%m-%Y') 'tglpajak', F.ipdgudang 'idgudang', J.gnama 'gudang',
                       F.ipdproyek 'idproyek', K.pnama 'proyek',
                       IFNULL(A.iputotalpajak,0) 'tpajak', 
                       IFNULL(A.iputotalpph22,0) 'tpph22',                        
                       IFNULL(A.ipujumlahdp,0) 'tdp', 
                       IFNULL(A.iputotaltransaksi,0) 'totaltrans', 
                       F.ipditem 'iditem', G.ikode 'kditem', G.inama 'namaitem',  
                       F.ipdcatatan 'catdetil', F.ipdsatuan 'idsatuan', H.skode 'satuan', 
                       IFNULL(F.ipdkeluar,0) 'qtydetil', 
                       IFNULL(F.ipdharga,0) 'hargadetil', 
                       IFNULL(F.ipddiskon2,0) 'diskon', 
                       IFNULL(F.ipddiskonp,0) 'persendiskon',
                       ((IFNULL(F.ipdkeluar,0)*IFNULL(F.ipdharga,0))-IFNULL(F.ipddiskon2,0)) 'subtotaldetil'
                  FROM einvoicepenjualanu A 
             LEFT JOIN bkontak B ON A.ipukontak=B.kid
             LEFT JOIN bkontak C ON A.ipukaryawan=C.kid 
             LEFT JOIN btermin D ON A.iputermin=D.tid
             LEFT JOIN bkontakatention E ON A.ipuattention=E.kaid 
             LEFT JOIN einvoicepenjualand F ON A.ipuid=F.ipdidipu 
             LEFT JOIN bitem G ON F.ipditem=G.iid 
             LEFT JOIN bsatuan H ON F.ipdsatuan=H.sid 
             LEFT JOIN fstoku I ON A.ipunobkg=I.suid 
             LEFT JOIN bgudang J ON F.ipdgudang=J.gid 
             LEFT JOIN bproyek K ON F.ipdproyek=K.pid 
                 WHERE A.ipusumber='".$transcode."' AND A.ipusaldoawal <> '1'";

        if(!empty($_POST['id'])) {
            $query .= " AND A.ipuid='".$_POST['id']."'";
        }else{
            $query .= " AND A.ipunotransaksi='".$_POST['nomor']."'";
        }

        $query .= " ORDER BY F.ipdurutan ASC";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }

}