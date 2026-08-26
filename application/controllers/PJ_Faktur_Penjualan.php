<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PJ_Faktur_Penjualan extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
		$this->load->model('M_transaksi');
    $this->load->model('M_PJ_Faktur_Penjualan');    
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_PJ_Faktur_Penjualan->tambahTransaksi();
    }else{
      echo $this->M_PJ_Faktur_Penjualan->ubahTransaksi();      
    }
   }

   function deletedata(){
    echo $this->M_PJ_Faktur_Penjualan->hapusTransaksi();          
   }

   function savedatadua(){
    if($_POST['id']==''){
      echo $this->M_PJ_Faktur_Penjualan->tambahTransaksiDua();
    }else{
      echo $this->M_PJ_Faktur_Penjualan->ubahTransaksiDua();      
    }
   }

   function deletedatadua(){
    echo $this->M_PJ_Faktur_Penjualan->hapusTransaksiDua();          
   }   

   function get_item() {
      $query  = "SELECT A.isatuan AS 'idsatuan', B.snama 'namasatuan', 
                        (SELECT kpenlevelharga FROM bkontak WHERE kid='".$_POST['kontak']."') 'level', 
                        A.ihargajual1 'hargajual',A.ihargajual2 'hargajual2',
                        A.ihargajual3 'hargajual3',A.ihargajual4 'hargajual4',      
                        (SELECT gid FROM bgudang WHERE gdefault=1 LIMIT 1) 'idgudang',
                        (SELECT gnama FROM bgudang WHERE gdefault=1 LIMIT 1) 'gudang'       
                   FROM bitem A LEFT JOIN bsatuan B ON A.isatuan=B.sid
                  WHERE A.iid='".$this->input->post('id')."'";
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
    }                   

   function getdata(){
      $transcode = $this->M_transaksi->prefixtrans(element('PJ_Faktur_Penjualan',NID));        
      $query = "SELECT A.ipuid 'id', A.ipunotransaksi 'nomor', DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
                       A.ipukontak 'kontakid', B.kkode 'kontakkode', B.knama 'kontak', A.ipuuraian 'uraian',
                       A.ipukaryawan 'idkaryawan', C.kkode 'kodekaryawan', C.knama 'namakaryawan', 
                       A.iputermin 'idtermin', D.tkode 'termin', A.ipucatatan 'catatan', A.ipunobkg 'bkgid', 
                       I.sunotransaksi 'nomorbkg', A.ipualamat 'alamat', A.ipuattention 'idperson', 
                       E.kanama 'person', A.ipujenispajak 'pajak', A.ipustatus 'status', A.ipunofakturpajak 'nofakturpjk', 
                       DATE_FORMAT(A.iputglpajak,'%d-%m-%Y') 'tglpajak', A.ipugudang 'idgudang', J.gnama 'gudang',
                       IFNULL(A.iputotalpajak,0) 'tpajak', 
                       IFNULL(A.iputotalpph22,0) 'tpph22',                        
                       IFNULL(A.ipujumlahdp,0) 'tdp', 
                       IFNULL(A.iputotaltransaksi,0) 'totaltrans', 
                       F.ipditem 'iditem', G.ikode 'kditem', G.inama 'namaitem',  
                       F.ipdcatatan 'catdetil', F.ipdsatuan 'idsatuan', H.skode 'satuan', 
                       IFNULL(F.ipdkeluar,0) 'qtydetil', 
                       IFNULL(F.ipdharga,0) 'hargadetil', 
                       IFNULL(F.ipddiskon,0) 'diskon', 
                       IFNULL(F.ipddiskonp,0) 'persendiskon',
                       ((IFNULL(F.ipdkeluar,0)*IFNULL(F.ipdharga,0))-(IFNULL(F.ipdkeluar,0)*IFNULL(F.ipddiskon,0))) 'subtotaldetil',
                       IFNULL(A.ipuiddp,0) 'iddp',F.ipdsju 'btguid',F.ipdsjd 'btgdid',K.sunotransaksi 'btgnotrans',K.sudpid 'btgnodp'  
                  FROM einvoicepenjualanu A 
             LEFT JOIN bkontak B ON A.ipukontak=B.kid
             LEFT JOIN bkontak C ON A.ipukaryawan=C.kid 
             LEFT JOIN btermin D ON A.iputermin=D.tid
             LEFT JOIN bkontakatention E ON A.ipuattention=E.kaid 
             LEFT JOIN einvoicepenjualand F ON A.ipuid=F.ipdidipu 
             LEFT JOIN bitem G ON F.ipditem=G.iid 
             LEFT JOIN bsatuan H ON F.ipdsatuan=H.sid 
             LEFT JOIN fstoku I ON A.ipunobkg=I.suid 
             LEFT JOIN bgudang J ON A.ipugudang=J.gid 
             LEFT JOIN fstoku K ON F.ipdsju=K.suid 
                 WHERE A.ipusumber='".$transcode."' AND A.ipusaldoawal <> '1' ";

        if(!empty($_POST['id'])) {
            $query .= " AND A.ipuid='".$_POST['id']."'";
        }else{
            $query .= " AND A.ipunotransaksi='".$_POST['nomor']."'";
        }

        $query .= " ORDER BY F.ipdurutan ASC";

        $query2 ="SELECT A.ipuid 'id', C.dpid 'iddp', C.dpnotransaksi 'nomordp', 
                         DATE_FORMAT(C.dptanggal,'%d-%m-%Y') 'tgldp',
                         IFNULL(C.dpjumlah,0) 'totaldp', 
                         IFNULL(C.dppakaiiv,0) 'sisa',
                         IFNULL(B.idpjumlahdp,0) 'totalbayar'      
                    FROM einvoicepenjualanu A
               LEFT JOIN einvoicepenjualandp B ON A.ipuid=B.idpidu 
               LEFT JOIN ddp C ON B.idpiddp=C.dpid 
                   WHERE A.ipusumber='".$transcode."'";

        if(!empty($_POST['id'])) {
            $query2 .= " AND A.ipuid='".$_POST['id']."'";
        }else{
            $query2 .= " AND A.ipunotransaksi='".$_POST['nomor']."'";
        }

        $query2 .= " ORDER BY B.idpiddp ASC";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query_second($query,$query2);
   }

   function getdatadua(){
        $transcode = $this->M_transaksi->prefixtrans(element('PJ_Faktur_Penjualan',NID));            
        $query = "SELECT  A.ipuid 'id', A.ipunotransaksi 'nomor', 
              DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
                A.ipukontak 'kontakid', B.kkode 'kontakkode', 
                  B.knama 'kontak', A.ipuuraian 'uraian', A.ipukaryawan 'idkaryawan', 
                  C.kkode 'kodekaryawan', C.knama 'namakaryawan', 
                  A.iputermin 'idtermin', D.tkode 'termin', A.ipucatatan 'catatan', 
                  A.ipunobkg 'bkgid', A.ipualamat 'alamat', A.ipuattention 'idperson', 
                  E.kanama 'person', A.ipujenispajak 'pajak', A.ipustatus 'status',       
                  A.ipunofakturpajak 'nofakturpjk', 
                  DATE_FORMAT(A.iputglpajak,'%d-%m-%Y') 'tglpajak',
                  IFNULL(A.iputotalpajak,0) 'tpajak',
                  IFNULL(A.iputotalpph22,0) 'tpph22',                   
                  IFNULL(A.ipujumlahdp,0) 'tdp', 
                  IFNULL(A.iputotaltransaksi,0) 'totaltrans',
                  F.sditem 'iditem', G.ikode 'kditem', G.inama 'namaitem',  
                  F.sdcatatan 'catdetil', F.sdsatuan 'idsatuan', H.skode 'satuan',
                  F.sdgudang 'idgudangdetil', I.gnama 'gudangdetil', 
                  IFNULL(F.sdkeluar,0) 'qtydetil', 
                  IFNULL(F.sdharga,0) 'hargadetil', 
                  IFNULL(F.sddiskon,0) 'diskon', 
                  ((IFNULL(F.sdkeluar,0)*IFNULL(F.sdharga,0))-
                   (IFNULL(F.sdkeluar,0)*IFNULL(F.sddiskon,0))) 'subtotaldetil',
                  J.sodid 'sodid', J.sodidsou 'souid', K.sounotransaksi 'orderno' 
            FROM  einvoicepenjualanu A 
       LEFT JOIN bkontak B ON A.ipukontak=B.kid
       LEFT JOIN bkontak C ON A.ipukaryawan=C.kid 
       LEFT JOIN btermin D ON A.iputermin=D.tid
       LEFT JOIN bkontakatention E ON A.ipuattention=E.kaid        
       LEFT JOIN fstokd F ON A.ipunobkg=F.sdidsu  
       LEFT JOIN bitem G ON F.sditem=G.iid 
       LEFT JOIN bsatuan H ON F.sdsatuan=H.sid 
       LEFT JOIN bgudang I ON F.sdgudang=I.gid     
       LEFT JOIN esalesorderd J ON F.sdsodid=J.sodid  
       LEFT JOIN esalesorderu K ON J.sodidsou=K.souid     
           WHERE A.ipusumber='".$transcode."' AND A.ipusaldoawal <> '1' ";

        if(!empty($_POST['id'])) {
            $query .= " AND A.ipuid='".$_POST['id']."'";
        }else{
            $query .= " AND A.ipunotransaksi='".$_POST['nomor']."'";
        }

        $query .= " ORDER BY F.sdurutan ASC";

        $query2 ="SELECT A.ipuid 'id', C.dpid 'iddp', C.dpnotransaksi 'nomordp', 
                         DATE_FORMAT(C.dptanggal,'%d-%m-%Y') 'tgldp',
                         IFNULL(C.dpjumlah,0) 'totaldp', 
                         IFNULL(C.dppakaiiv,0) 'sisa',
                         IFNULL(B.idpjumlahdp,0) 'totalbayar'      
                    FROM einvoicepenjualanu A
               LEFT JOIN einvoicepenjualandp B ON A.ipuid=B.idpidu 
               LEFT JOIN ddp C ON B.idpiddp=C.dpid 
                   WHERE A.ipusumber='".$transcode."'";

        if(!empty($_POST['id'])) {
            $query2 .= " AND A.ipuid='".$_POST['id']."'";
        }else{
            $query2 .= " AND A.ipunotransaksi='".$_POST['nomor']."'";
        }

        $query2 .= " ORDER BY B.idpiddp ASC";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query_second($query,$query2);

   }

   function getdatapengiriman(){
      if(empty($_POST['id'])) {
        echo _pesanError("Id transaksi tidak ditemukan !");
        exit;
      }

      $transcode = $this->M_transaksi->prefixtrans(element('PJ_Surat_Jalan',NID));        
      $query = "SELECT A.suid 'id', A.sunotransaksi 'nomor', DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',
                       A.sukontak 'kontakid', B.kkode 'kontakkode', B.knama 'kontak', A.suuraian 'uraian',
                       M.soukaryawan 'idkaryawan', P.kkode 'kodekaryawan', P.knama 'namakaryawan', A.sunoref 'noref', 
                       A.susouid 'orderid', I.sounotransaksi 'nomororder',
                       A.sunoreftransaksi 'noreftrans', M.soutermin 'idtermin', Q.tkode 'termin', A.sucatatan 'catatan', 
                       A.sualamat 'alamat', A.suattention 'idperson', E.kanama 'person', M.soupajak 'pajak', 
                       A.sustatus 'status', A.sunofakturpajak 'nofakturpjk', DATE_FORMAT(A.sutglpajak,'%d-%m-%Y') 'tglpajak',
                       IFNULL(A.sutotalpajak,0) 'tpajak', 
                       IFNULL(A.sutotalpph22,0) 'tpph22',                        
                       IFNULL(A.sutotaldp,0) 'tdp', 
                       IFNULL(A.sutotaltransaksi,0) 'totaltrans', 
                       IFNULL(A.sutotalsisa,0) 'tsisa', F.sdid 'sdid', F.sdidsu 'sdidsu',
                       F.sditem 'iditem', G.ikode 'kditem', G.inama 'namaitem', F.sdsatuan 'idsatuan', 
                       F.sdgudang 'idgudang', J.gnama 'gudang', 
                       F.sdproyek 'idproyek', K.pnama 'proyek',
                       F.sdcatatan 'catdetil', H.skode 'satuan', F.sdsodid 'orderdid', M.sounotransaksi 'nomororderd',
                       IFNULL(F.sdkeluar-F.sdfaktur,0) 'qtydetil', 
                       IF(F.sdharga=0,G.ihargajual1,F.sdharga) 'hargadetil',
                       IFNULL(F.sdhargainvoice,0) 'hargainvoice',                       
                       IFNULL(F.sddiskon,0) 'diskon',
                       IFNULL(F.sddiskoninvoice,0) 'diskoninvoice',                       
                       0 'persendiskon',
                       ((IF(F.sdharga=0,G.ihargajual1,F.sdharga)-IFNULL(F.sddiskon,0))*IFNULL(F.sdkeluar-F.sdfaktur,0)) 'subtotaldetil',
                       ((IFNULL(F.sdhargainvoice,0)-IFNULL(F.sddiskoninvoice,0))*IFNULL(F.sdkeluar-F.sdfaktur,0)) 'subtotalinvoice',
                       IFNULL(A.sudpid,0) 'iddp', DATE_FORMAT(N.dptanggal,'%d-%m-%Y') 'tgldp', N.dpnotransaksi 'nomordp',
                       N.dpketerangan 'uraiandp', N.dpcdp 'coadp', O.cnama 'coadpname',
                       N.dppajakn 'tdppajak', N.dpnofaktupajak 'fakturpajakdp', N.dppajak 'pajakdp',
                       CASE WHEN N.dppajak=1 THEN (N.dpjumlah-N.dppajakn) ELSE N.dpjumlah END 'tdpjumlah',
                       0 'dipakaidp' 
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
               LEFT JOIN bkontak P ON M.soukaryawan=P.kid
               LEFT JOIN btermin Q ON M.soutermin=Q.tid  
                   WHERE A.susumber='".$transcode."' AND A.suid IN (".$_POST['id'].") AND F.sdkeluar-F.sdfaktur > 0  ORDER BY F.sdurutan ASC ";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }   

   function getdatauangmuka(){
      if(empty($_POST['id'])) {
        echo _pesanError("Id transaksi tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.dpid 'id', A.dpnotransaksi 'nomor', DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tgl',
                       IFNULL(A.dpjumlah,0) 'totaltrans', IFNULL(A.dppakaiiv,0) 'terbayar',
                       IFNULL(A.dpjumlah,0)-IFNULL(A.dppakaiiv,0) 'totalbayar'
                  FROM ddp A WHERE A.dpnotransaksi IN (".$_POST['id'].")";

      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

   function getdataorder(){
      if(empty($_POST['id'])) {
        echo _pesanError("Id transaksi tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.soditem 'item', C.inama 'namaitem', 
                       (A.sodorder-A.sodkeluar) 'qty', 
                       A.sodharga 'harga', 
                       A.soddiskon 'diskon', 
                       A.soddiskonpersen 'persen',
                       ((A.sodharga-A.soddiskon)*(A.sodorder-A.sodkeluar)) 'jumlah', 
                       A.sodsatuan 'satuan', D.snama 'namasatuan', A.sodcatatan 'catatan', 
                       A.sodid 'id', B.sounotransaksi 'nomor', B.souid 'orderid', B.soutermin 'idtermin',
                       E.tkode 'termin', B.soupajak 'pajak', B.soukaryawan 'idkaryawan', F.knama 'karyawan',
                       B.soutotalpajak 'totalpajak', B.soutotalpph22 'totalpph22'                                                                  
                  FROM esalesorderd A
            INNER JOIN esalesorderu B ON B.souid=A.sodidsou
             LEFT JOIN bitem C ON A.soditem=C.iid
             LEFT JOIN bsatuan D ON A.sodsatuan=D.sid
             LEFT JOIN btermin E ON B.soutermin=E.tid
             LEFT JOIN bkontak F ON B.soukaryawan=F.kid 
                 WHERE A.sodidsou IN (".$_POST['id'].") AND A.sodorder-A.sodkeluar>0 ORDER BY A.sodid ASC,A.sodurutan ASC ";

      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }


}