<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PJ_Terima_Retur extends CI_Controller {

   function __construct() { 
      parent::__construct();
      if(!$this->session->has_userdata('nama')){
        redirect(base_url('exception'));
      }          
      $this->load->model('M_transaksi');
      $this->load->model('M_PJ_Terima_Retur');
   }

   function savedata(){
      if($_POST['id']==''){
        echo $this->M_PJ_Terima_Retur->tambahTransaksi();
      }else{
        echo $this->M_PJ_Terima_Retur->ubahTransaksi();      
      }
   }

   function deletedata(){
      echo $this->M_PJ_Terima_Retur->hapusTransaksi();          
   }

   function get_item() {
      $query  = "SELECT A.isatuan AS 'idsatuan', B.snama 'namasatuan', A.ihargajual1 'harga' 
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

      $transcode = $this->M_transaksi->prefixtrans(element('PJ_Terima_Retur',NID));        
   		$query = "SELECT A.suid 'id', A.sunotransaksi 'nomor', DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',
           						 A.sukontak 'kontakid', B.kkode 'kontakkode', B.knama 'kontak', A.suuraian 'uraian',
           						 A.sukaryawan 'idkaryawan', C.kkode 'kodekaryawan', C.knama 'namakaryawan', A.sunoref 'noref', 
                       A.susouid 'orderid', I.sounotransaksi 'nomororder', A.sugudangtujuan 'idgudang', N.gnama 'gudang',
                       A.sunoreftransaksi 'noreftrans', A.sutermin 'idtermin', D.tkode 'termin', A.sucatatan 'catatan', 
                       A.sualamat 'alamat', A.suattention 'idperson', E.kanama 'person', A.supajak 'pajak', 
                       A.sustatus 'status', A.sunofakturpajak 'nofakturpjk', DATE_FORMAT(A.sutglpajak,'%d-%m-%Y') 'tglpajak',
                       IFNULL(A.sutotalpajak,0) 'tpajak', 
                       IFNULL(A.sutotaldp,0) 'tdp', 
                       IFNULL(A.sutotaltransaksi,0) 'totaltrans', 
                       IFNULL(A.sutotalsisa,0) 'tsisa',
                       F.sditem 'iditem', G.ikode 'kditem', G.inama 'namaitem', F.sdsatuan 'idsatuan', 
                       F.sdgudang 'idgudangd', J.gnama 'gudangd', 
                       F.sdproyek 'idproyek', K.pnama 'proyek',
                       F.sdcatatan 'catdetil', H.skode 'satuan', F.sdpbdid 'pbdid', F.sdsodid 'orderdid', M.sounotransaksi 'nomororderd',
                       IFNULL(F.sdmasuk,0) 'qtydetil', 
                       IFNULL(F.sdharga,0) 'hargadetil',
                       IFNULL(F.sddiskon,0) 'diskon',
                       0 'persendiskon',
                       ((IFNULL(F.sdharga,0)-IFNULL(F.sddiskon,0))*IFNULL(F.sdmasuk,0)) 'subtotaldetil'
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
               LEFT JOIN bgudang N ON A.sugudangtujuan=N.gid               
                   WHERE A.susumber='".$transcode."' AND A.suid='".$_POST['id']."' ORDER BY F.sdurutan ASC ";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }

   function getdatasuratjalan(){
      if(empty($_POST['id'])) {
        echo _pesanError("Id transaksi tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.sditem 'item', C.inama 'namaitem', 
                       (A.sdkeluar-(SELECT IFNULL(SUM(sdmasuk),0) FROM fstokd WHERE sdpbdid=A.sdid)) 'qty', 
                       A.sdharga 'harga', 
                       A.sddiskon 'diskon', 
                       A.sddiskonpersen 'persen',
                       ((A.sdharga-A.sddiskon)*(A.sdkeluar-(SELECT IFNULL(SUM(sdmasuk),0) FROM fstokd WHERE sdpbdid=A.sdid))) 'jumlah', 
                       A.sdsatuan 'satuan', D.snama 'namasatuan', A.sdcatatan 'catatan', 
                       A.sdid 'idpbd', A.sdsodid 'idsod', F.sounotransaksi 'nomorso',
                       A.sdproyek 'idproyek', G.pnama 'proyek',
                       A.sdgudang 'idgudang', H.gnama 'gudang'
                  FROM fstokd A
            INNER JOIN fstoku B ON B.suid=A.sdidsu
             LEFT JOIN bitem C ON A.sditem=C.iid
             LEFT JOIN bsatuan D ON A.sdsatuan=D.sid
             LEFT JOIN esalesorderd E ON A.sdsodid=E.sodid
             LEFT JOIN esalesorderu F ON E.sodidsou=F.souid
             LEFT JOIN bproyek G ON A.sdproyek=G.pid
             LEFT JOIN bgudang H ON A.sdgudang=H.gid               
                 WHERE A.sdidsu IN (".$_POST['id'].") ORDER BY A.sdid ASC,A.sdurutan ASC ";

      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

}