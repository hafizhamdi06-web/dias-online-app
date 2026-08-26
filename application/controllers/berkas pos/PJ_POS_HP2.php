<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PJ_POS_HP2 extends CI_Controller {

   function __construct() { 
  		parent::__construct();
      if(!$this->session->has_userdata('nama')){
        redirect(base_url('exception'));
      }          
  		$this->load->model('M_transaksi');
      $this->load->model('M_PJ_POS_HP');
   }

   function savedata(){
      if($_POST['id']==''){
        echo $this->M_PJ_POS_HP->tambahTransaksi();
      }else{
        echo $this->M_PJ_POS_HP->ubahTransaksi();      
      }
   }

   function deletedata(){
      echo $this->M_PJ_POS_HP->hapusTransaksi();          
   }   

   function get_item() {
        $query  = "SELECT A.isatuan 'idsatuan', B.snama 'namasatuan', 
                          A.ihargajual1 'hargajual', A.idiskon 'diskon' , A.ikelompok2020 'kelompok2020'
                     FROM bitem A LEFT JOIN bsatuan B ON A.isatuan=B.sid
                    WHERE A.iid='".$_POST['id']."' ";
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }   

   function get_detail_pasien() {
   		if(empty($_POST['id'])) {
   			echo _pesanError("Id transaksi tidak ditemukan !");
  			exit;
   		}
        $query  = "SELECT A.knama 'nama', A.k1alamat 'alamat', A.k1telp1 'nohp', A.kidpasien 'pasienid' , B.ktnama 'namatipe', A.ktipe 'tipeid' , A.k1email 'email'
                     FROM bkontak A LEFT JOIN bkontaktipe B ON A.ktipe=B.ktid
                    WHERE A.kid='".$_POST['id']."' ";
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }                       

   function getdata(){
   		if(empty($_POST['id'])) {
   			echo _pesanError("Id transaksi tidak ditemukan !");
  			exit;
   		}

      $transcode ='IP' ; //$this->M_transaksi->prefixtrans(element('PJ_POS_HP',NID));        
   		$query = "SELECT A.suid 'id', A.sunotransaksi 'nomor', DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',
           						 A.sukontak 'kontakid', B.kkode 'kontakkode', B.knama 'kontak', A.suuraian 'uraian',
           						 A.sukaryawan 'idkaryawan', C.kkode 'kodekaryawan', C.knama 'namakaryawan',  
                       A.sucatatan 'catatan',                  
                       IFNULL(A.sutotaltransaksi,0) 'tsubtotal',
                       IFNULL(A.sutotalbayar,0) 'totalbayar', 
                       IFNULL(A.sutotalsisa,0) 'totalsisa', 
                       F.sditem 'iditem', G.ikode 'kditem', G.inama 'namaitem', G.ikelompok2020 'item_tipe2020',
                       F.sdsatuan 'idsatuan', H.skode 'satuan', 
                       IFNULL(F.sdkeluar,0) 'qtydetil', 
                       IFNULL(F.sdharga,0) 'hargadetil', 
                       IFNULL(F.sddiskon,0) 'diskondetil', 
                       IFNULL(F.sddiskonpersen,0) 'dis1detil', 
                       IFNULL(F.sddiskonpersen2,0) 'dis2detil', 
                       ((IFNULL(F.sdharga,0)-IFNULL(F.sddiskon,0))*IFNULL(F.sdkeluar,0)) 'subtotaldetil',
                       A.sutotalkas 'kasjumlah',
                       A.sutotalkartudebit 'debitjumlah', A.sunokartudebit 'debitno', A.sunamadebit 'debitnama',
                       A.subankdebit 'debitbank', A.sudebitjenis 'debitjenis', A.suattention 'debitbanklain',
                       A.sutotalkartukredit 'kreditjumlah', A.sunokartukredit 'kreditno', A.sunamakredit 'kreditnama',
                       A.subankkredit 'kreditbank', A.sukreditjenis 'kreditjenis', A.sunofakturpajak 'kreditbanklain',
                       A.sutotaltransfer 'transferjumlah', A.sunotransfer 'transferno', A.sunamatransfer 'transfernama',
                       A.subanktransfer 'transferbank' ,
                       A.sutotalvoucher 'voucherjumlah', A.sunovoucher 'voucherno', A.sustatuskirim 'voucherid',
                       A.sutotaldp  'dpjumlah', A.sudp1 'dpjumlah', A.sudpid 'dpid' , A.sujenisdp 'dpjenis', K.sunotransaksi 'dpno',
                       A.sucabang 'cabang', A.surekammedis 'rekammedis', A.sutotaltada 'totaltanpadp',
                       A.sumerchantjumlah  'merchantjumlah', A.sumerchantno 'merchantno' , A.sumerchantjenis 'merchantjenis',
                       A.surekhutang 'idtraining', L.kkode 'kodetraining', L.knama 'namatraining'  ,
                       A.sufarmasi 'idfarmasi', M.kkode 'kodefarmasi', M.knama 'namafarmasi'  ,
                       A.sufarmasiasisten 'idfarmasiasisten', N.kkode 'kodefarmasiasisten', N.knama 'namafarmasiasisten' ,
                       A.susalesmarketing 'idsalesmarketing', O.kkode 'kodesalesmarketing', O.knama 'namasalesmarketing'
                       
                    FROM fstoku_tes A 
               LEFT JOIN bkontak B ON A.sukontak=B.kid
               LEFT JOIN bkontak C ON A.sukaryawan=C.kid  
               LEFT JOIN fstokd_tes F ON A.suid=F.sdidsu 
               LEFT JOIN bitem G ON F.sditem=G.iid 
               LEFT JOIN bsatuan H ON F.sdsatuan=H.sid    
               LEFT JOIN fstokd J ON A.sudpid=J.sdid     
               LEFT JOIN fstoku K ON J.sdidsu=K.suid 
               LEFT JOIN bkontak L ON A.surekhutang=L.kid 
               LEFT JOIN bkontak M ON A.sufarmasi=M.kid 
               LEFT JOIN bkontak N ON A.sufarmasiasisten=N.kid 
               LEFT JOIN bkontak O ON A.susalesmarketing=O.kid 
               
                   WHERE A.susumber='".$transcode."' AND A.suid='".$_POST['id']."' ORDER BY F.sdurutan ASC ";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }
   
   
   function getdata_voucher(){  
          
   		$query = " select A.vid 'id', A.vnomor 'nomor', A.vnilai-A.vnilaipakai 'nilai', A.vitem 'item'   from bvoucher A 
   		            where A.vnilai-A.vnilaipakai > 0  and A.vkontak = '".$_POST['xid']."'
   		            order by A.vnomor   ";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }
   
   
   function getdata_dp(){  
       
         //  hSQL = "select SDID,SUNOTRANSAKSI,SUTANGGAL,KNAMA,((SDHARGA-SDDISKON )*SDKELUAR)-SDBAYARDP  from fstokd LEFT JOIN fstoku ON SUID=SDIDSU left join bkontak on KID = SUKONTAK LEFT JOIN bitem ON IID=SDITEM WHERE // (case when IBERLAKU > 0 then DATE_ADD(SUTANGGAL, INTERVAL IBERLAKU+1 DAY) > CURRENT_DATE() else SUTANGGAL > '2000/01/01'  end) and SUTANGGAL >= '2019/04/01' AND SUSUMBER = 'IP' AND ijenisitem=14 AND //((SDHARGA-SDDISKON )*SDKELUAR)-SDBAYARDP  <> 0  AND SUKONTAK =" & eFrmPOS2.txtKontak.Tag & "   order by SUNOTRANSAKSI DESC "

          
   		$query = " select A.sdid 'id', B.sunotransaksi 'nomor', ((A.sdharga-A.sddiskon)*A.sdkeluar)-A.sdbayardp 'nilai'  
                      from fstokd A left join fstoku B on B.suid=A.sdidsu left join bitem C on C.iid = A.sditem
                       where B.susumber = 'IP' and B.sustatus <> 9 and C.ijenisitem=14
                       and ((A.sdharga-A.sddiskon)*A.sdkeluar)-A.sdbayardp > 0  and B.sukontak = '".$_POST['xid']."'
   		            order by A.sdid limit 1    ";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }

}