<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PB_Terima_Barang extends CI_Controller {

   function __construct() { 
  		parent::__construct();
      if(!$this->session->has_userdata('nama')){
        redirect(base_url('exception'));
      }          
  		$this->load->model('M_transaksi');
  	  $this->load->model('M_PB_Terima_Barang');
   }

   function savedata(){
      if($_POST['id']==''){
        echo $this->M_PB_Terima_Barang->tambahTransaksi();
      }else{
        echo $this->M_PB_Terima_Barang->ubahTransaksi();      
      }
   }

   function deletedata(){
      echo $this->M_PB_Terima_Barang->hapusTransaksi();          
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

      $transcode = $this->M_transaksi->prefixtrans(element('PB_Terima_Barang',NID));        
   		$query = "SELECT A.suid 'id', A.sunotransaksi 'nomor', DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',
           						 A.sukontak 'kontakid', B.kkode 'kontakkode', B.knama 'kontak', A.suuraian 'uraian',
           						 A.sukaryawan 'idkaryawan', C.kkode 'kodekaryawan', C.knama 'namakaryawan', A.sunoref 'noref', 
                       A.susouid 'orderid', I.sounotransaksi 'nomororder',
                       A.sunoreftransaksi 'noreftrans', A.sutermin 'idtermin', D.tkode 'termin', A.sucatatan 'catatan', 
                       A.sualamat 'alamat', A.suattention 'idperson', E.kanama 'person', A.supajak 'pajak', 
                       A.sustatus 'status', A.sunofakturpajak 'nofakturpjk', DATE_FORMAT(A.sutglpajak,'%d-%m-%Y') 'tglpajak',
                       IFNULL(A.sutotalpajak,0) 'tpajak', 
                       IFNULL(A.sutotaldp,0) 'tdp', 
                       IFNULL(A.sutotaltransaksi,0) 'totaltrans', 
                       IFNULL(A.sutotalsisa,0) 'tsisa',
                       F.sditem 'iditem', G.ikode 'kditem', G.inama 'namaitem', F.sdsatuan 'idsatuan', 
                       F.sdgudang 'idgudang', J.gnama 'gudang', 
                       F.sdproyek 'idproyek', K.pnama 'proyek', M.souid 'morderid',
                       F.sdcatatan 'catdetil', H.skode 'satuan', F.sdsodid 'orderdid', M.sounotransaksi 'nomororderd',
                       IFNULL(F.sdmasuk,0) 'qtydetil', 
                       IFNULL(F.sdharga,0) 'hargadetil',
                       IFNULL(F.sddiskon,0) 'diskon',
                       0 'persendiskon',
                       ((IFNULL(F.sdharga,0)-IFNULL(F.sddiskon,0))*IFNULL(F.sdmasuk,0)) 'subtotaldetil',
                       A.sudpid 'iddp', DATE_FORMAT(N.dptanggal,'%d-%m-%Y') 'tgldp', N.dpnotransaksi 'nomordp',
                       N.dpketerangan 'uraiandp', N.dpcdp 'coadp', O.cnama 'coadpname',
                       N.dppajakn 'tdppajak', N.dpnofaktupajak 'fakturpajakdp', N.dppajak 'pajakdp',
                       CASE WHEN N.dppajak=1 THEN (N.dpjumlah-N.dppajakn) ELSE N.dpjumlah END 'tdpjumlah' 
                    FROM fstoku A 
               LEFT JOIN bkontak B ON A.sukontak=B.kid
               LEFT JOIN bkontak C ON A.sukaryawan=C.kid 
               LEFT JOIN btermin D ON A.sutermin=D.tid
               LEFT JOIN bkontakatention E ON A.suattention=E.kaid 
               LEFT JOIN fstokd F ON A.suid=F.sdidsu 
               LEFT JOIN bitem G ON F.sditem=G.iid 
               LEFT JOIN bsatuan H ON F.sdsatuan=H.sid 
               LEFT JOIN esalesorderu I ON A.susouid=I.souid 
               LEFT JOIN bgudang J ON F.sdgudang=J.gid 
               LEFT JOIN bproyek K ON F.sdproyek=K.pid 
               LEFT JOIN esalesorderd L ON F.sdsodid=L.sodid
               LEFT JOIN esalesorderu M ON L.sodidsou=M.souid 
               LEFT JOIN ddp N ON A.sudpid=N.dpid  
               LEFT JOIN bcoa O ON N.dpcdp=O.cid             
                   WHERE A.susumber='".$transcode."'";

        if(!empty($_POST['id'])) {
            $query .= " AND A.suid='".$_POST['id']."'";
        }else{
            $query .= " AND A.sunotransaksi='".$_POST['nomor']."'";
        }

        $query .= " ORDER BY F.sdurutan ASC";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }

   function getdataorder(){
      if(empty($_POST['id'])) {
        echo _pesanError("Id transaksi tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.soditem 'item', C.inama 'namaitem', 
                       (A.sodorder-(A.sodmasuk-A.sodkeluar)) 'qty', 
                       A.sodharga 'harga', 
                       A.soddiskon 'diskon', 
                       A.soddiskonpersen 'persen',
                       ((A.sodharga-A.soddiskon)*(A.sodorder-(A.sodmasuk-A.sodkeluar))) 'jumlah', 
                       A.sodsatuan 'satuan', D.snama 'namasatuan', A.sodcatatan 'catatan', 
                       A.sodid 'id', B.sounotransaksi 'nomor', B.souid 'orderid'                       
                  FROM esalesorderd A
            INNER JOIN esalesorderu B ON B.souid=A.sodidsou
             LEFT JOIN bitem C ON A.soditem=C.iid
             LEFT JOIN bsatuan D ON A.sodsatuan=D.sid
                 WHERE A.sodidsou IN (".$_POST['id'].") AND A.sodorder-A.sodmasuk>0 ORDER BY A.sodid ASC,A.sodurutan ASC ";

      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

   function deletedp(){
      echo $this->M_PB_Terima_Barang->hapusUangMuka();          
   }

}