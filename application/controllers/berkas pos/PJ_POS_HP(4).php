<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PJ_POS_HP extends CI_Controller {

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
      }
      elseif($_POST['id']!='' &&  $_POST['appcanceltransaksi']=='1'){
        echo $this->M_PJ_POS_HP->tambahTransaksi();
      }
      else{
        echo $this->M_PJ_POS_HP->ubahTransaksi();      
      }
   }

   function deletedata(){
      echo $this->M_PJ_POS_HP->hapusTransaksi();          
   }   

   function kirimulangpoint(){
      echo $this->M_PJ_POS_HP->kirimulangpoint();          
   }   
   
   
   

   function get_item() {
        $query  = "SELECT A.isatuan 'idsatuan', B.snama 'namasatuan', A.iid 'iditem', A.inama 'namaitem', 
                          A.ihargajual1 'hargajual', A.idiskon 'diskon' , A.ikelompok2020 'kelompok2020' , jwajibdokter 'wajibdokter', jbisaeditharga 'bisaeditharga',ISUBKATEGORI 'cetak', ijenisitem 'jenisitem'
                     FROM bitem A LEFT JOIN bsatuan B ON A.isatuan=B.sid LEFT JOIN bitemjenis on ijenisitem=JID
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
                        , DATE_FORMAT(coalesce(f_tanggal_akhir (A.kid)),'%d-%m-%Y')  as tglakhir 
                        ,  case coalesce(f_tanggal_akhir (A.kid)) when '' then '' else coalesce(coalesce(f_tanggal_akhir (A.kid))  + INTERVAL 1 YEAR) end 'tglexpired'
                        ,  DATE_FORMAT(coalesce(A.kcreated),'%d-%m-%Y') 'tglbuat', DATE_FORMAT(current_date,'%d-%m-%Y') 'tglsekarang'
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
                       F.sditem 'iditem', G.ikode 'kditem', G.inama 'namaitem', G.ikelompok2020 'item_tipe2020', AA.jwajibdokter 'wajibdokter',
                       F.sdsatuan 'idsatuan', H.skode 'satuan', 
                       IFNULL(F.sdkeluar,0) 'qtydetil', 
                       IFNULL(F.sdharga,0) 'hargadetil', 
                       IFNULL(F.sddiskon,0) 'diskondetil', 
                       IFNULL(F.sddiskon2,0) 'diskondetil2',
                       IFNULL(F.sddiskonpersen,0) 'dis1detil', 
                       IFNULL(F.sddiskonpersen2,0) 'dis2detil', F.sdcetak 'cetak',
                       ((IFNULL(F.sdharga,0)-IFNULL(F.sddiskon,0))*IFNULL(F.sdkeluar,0)) 'subtotaldetil', F.sddaripaket 'daripaket', 
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
                       A.susalesmarketing 'idsalesmarketing', O.kkode 'kodesalesmarketing', O.knama 'namasalesmarketing' ,
                       case A.sudkkwalkin when 0 then 'Hanya Beli' when 1 then 'DKK' when 2 then 'Walk In' end 'namadkkwalkin', A.sudkkwalkin 'iddkkwalkin',
                       A.sukliniklain 'idkliniklain', P.kkode 'kodekliniklain', P.knama 'namakliniklain' ,
                       A.sunilaipiutang 'piutangjumlah', A.susurgerydpidu 'surgerydpidu', A.susurgerydptotal 'surgerydptotal', A.susurgerydppembayaran 'surgerydppembayaran', 
                       A.susurgerydppiutang 'surgerydppiutang', Q.sunotransaksi 'surgerydpno' , 
                       A.sukodetele 'kodetele' , A.sureviewnilai 'reviewnilai', A.sureviewcatatan 'reviewcatatan', 
                       F.sddokter 'iddokter', R.knama 'dokter',  F.sdkaryawan 'idoperator', S.knama 'operator',  F.sdreferal 'idreferal', T.knama 'referal',  
                       F.sdpro 'idaos', U.knama 'aos', F.sdprorecom 'idrecom', V.knama 'recom',
                       X.mpdid 'idpromo', Y.mpukode 'promo', W.puid 'idpaket', W.pukode 'paket',
                       F.sdnoref 'noref', F.sdlantai2 'noic', F.sdcatatankoli 'nopaket', F.sdkedatangan 'kedatanganke', F.sdsodurutan 'idpaketdetil',
                       A.SUIDMEDLIB 'idmedlib', Z.medCode 'kodemedlib',
                       F.SDMEDIDU 'medidu', F.SDMEDIDD 'medidd',
                       A.suteman 'idteman', AB.kkode 'kodeteman',  concat(AB.knama,' (',AB.kidpasien,') ') 'namateman',
                       F.SDVOUCERID 'idvoucherwebdetil',F.SDPOINTKELUAR 'pointvoucherwebdetil', AC.VPIDVOUCHER 'novoucher' ,
                       F.SDSODID 'medidd_sudahbayar', F.SDPRDID 'medidu_sudahbayar' , A.sulmcid 'lmcid' 
                    FROM fstoku A 
               LEFT JOIN bkontak B ON A.sukontak=B.kid
               LEFT JOIN bkontak C ON A.sukaryawan=C.kid  
               LEFT JOIN fstokd F ON A.suid=F.sdidsu 
               LEFT JOIN bitem G ON F.sditem=G.iid 
               LEFT JOIN bsatuan H ON F.sdsatuan=H.sid    
               LEFT JOIN fstokd J ON A.sudpid=J.sdid     
               LEFT JOIN fstoku K ON J.sdidsu=K.suid 
               LEFT JOIN bkontak L ON A.surekhutang=L.kid 
               LEFT JOIN bkontak M ON A.sufarmasi=M.kid 
               LEFT JOIN bkontak N ON A.sufarmasiasisten=N.kid 
               LEFT JOIN bkontak O ON A.susalesmarketing=O.kid 
               LEFT JOIN bkontak P ON A.sukliniklain=P.kid    
               LEFT JOIN fstoku Q ON A.susurgerydpidu=Q.suid 
               LEFT JOIN bkontak R ON F.sddokter=R.kid    
               LEFT JOIN bkontak S ON F.sdkaryawan=S.kid     
               LEFT JOIN bkontak T ON F.sdreferal=T.kid     
               LEFT JOIN bkontak U ON F.sdpro=U.kid     
               LEFT JOIN bkontak V ON F.sdprorecom=V.kid      
               LEFT JOIN epaketu W ON F.sdidpotongstok=W.puid      
               LEFT JOIN emasterpromod X ON F.sdidpromo=X.mpdid      
               LEFT JOIN emasterpromou Y ON X.mpdidU=Y.mpuid  
               LEFT JOIN official_nmw.ops_medical Z ON A.SUIDMEDLIB=Z.medId 
               LEFT JOIN bitemjenis AA on G.ijenisitem=AA.JID      
               LEFT JOIN bkontak AB ON A.suteman=AB.kid            
               LEFT JOIN bvoucherpoint AC ON AC.vpid=F.sdvoucerid  
               LEFT JOIN fstokdiscv AD on AD.SDVIDU=A.suid
               
                   WHERE A.susumber='".$transcode."' AND A.suid='".$_POST['id']."' ORDER BY F.sdurutan ASC ";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }
   
   function getdata_detail_v(){
   		if(empty($_POST['id'])) {
   			echo _pesanError("Id transaksi tidak ditemukan !");
  			exit;
   		}

        $transcode ='IP' ;     
   		$query = "SELECT   SDVURUTAN, SDVIDVOUCHER, SDVIDU, SDVNILAI, SDVURUTANITEM, SDVITEM1, SPVD1BON, SDVITEM2, SDVRUPIAH, SDVNILAI2, SDVFREEITEM , VNOMOR
                    FROM fstoku A   
               inner JOIN fstokdiscv AD on AD.SDVIDU=A.suid
               left join bvoucher on vid=SDVIDVOUCHER
               
                   WHERE A.susumber='".$transcode."' AND A.suid='".$_POST['id']."' ORDER BY SDVURUTAN ASC ";
       
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
          
   		$query = " select A.sdid 'id', B.sunotransaksi 'nomor', IFNULL(((A.sdharga-A.sddiskon)*A.sdkeluar)-A.sdbayardp,0) 'nilai'  
                      from fstokd A left join fstoku B on B.suid=A.sdidsu left join bitem C on C.iid = A.sditem
                       where B.susumber = 'IP' and B.sustatus <> 9 and C.ijenisitem=14
                       and ((A.sdharga-A.sddiskon)*A.sdkeluar)-A.sdbayardp > 0  and B.sukontak = '".$_POST['xid']."'
   		            order by A.sdid limit 1    ";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }
   
   
   function getdata_surgerydp(){  
         
   		$query = " select A.suid 'id', A.sunotransaksi 'nomor', A.sutotaltransaksi 'surgerydptotal'  , A.sunilaipiutang 'nilaipiutang'  , A.sutotaltransaksi-A.sunilaipiutang 'surgerydppembayaran',
   		                F.sditem 'iditem', G.ikode 'kditem', G.inama 'namaitem', G.ikelompok2020 'item_tipe2020', jwajibdokter 'wajibdokter' , 
                       F.sdsatuan 'idsatuan', H.skode 'satuan', 
                       IFNULL(F.sdkeluar,0) 'qtydetil', 
                       IFNULL(F.sdharga,0) 'hargadetil', 
                       IFNULL(F.sddiskon,0) 'diskondetil', 
                       IFNULL(F.sddiskonpersen,0) 'dis1detil', 
                       IFNULL(F.sddiskonpersen2,0) 'dis2detil', 
                       ((IFNULL(F.sdharga,0)-IFNULL(F.sddiskon,0))*IFNULL(F.sdkeluar,0)) 'subtotaldetil'
                       
                      from  fstoku A  left join fstokd F on A.suid=F.sdidsu 
                      LEFT JOIN bitem G ON F.sditem=G.iid 
                      LEFT JOIN bsatuan H ON F.sdsatuan=H.sid   
                      LEFT JOIN bitemjenis I on G.ijenisitem=I.JID
                      
                       where A.susumber = 'IP' and A.sustatus <> 9  
                       and  A.sunilaipiutang-A.susurgerydppiutang > 0  and A.sukontak = '".$_POST['xid']."'
   		               order by A.suid desc limit 1    ";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }
   
   
   
   
   function get_detail_paket(){ 
       $cabang  = @$_SESSION['cabang'] ;
    	if(empty($_POST['idpaket'])) {
   			echo _pesanError("Id Paket tidak ditemukan !");
  			exit;
   		}   
    	//if(empty($_POST['nopaket'])) {
   		//	echo _pesanError("No Paket tidak ditemukan !");
  		//	exit;
   		//}      
    	if(empty($_POST['kontak'])) {
   			echo _pesanError("Pasien tidak ditemukan !");
  			exit;
   		}    
           
   		$query = " select C.iid 'iditem', C.ikode 'kditem', C.inama 'namaitem', C.ikelompok2020 'item_tipe2020', F.jwajibdokter 'wajibdokter' , 
                       D.sid 'idsatuan', D.skode 'satuan',  
                       IFNULL(B.pdqty,0) 'qtydetil', B.PDQTYTINDAKAN 'qtydetiltindakan',
                       IFNULL(C.ihargajual1,0) 'hargadetil', 
                       (C.ihargajual1*B.pddiskonpersen1/100) + ((C.ihargajual1 - (C.ihargajual1*B.pddiskonpersen1/100))*B.pddiskonpersen2/100)  'diskondetil', 
                       IFNULL(B.pddiskonpersen1,0) 'dis1detil', 
                       IFNULL(B.pddiskonpersen2,0) 'dis2detil', 
                       ROUND(
                       ( C.ihargajual1 - (C.ihargajual1*B.pddiskonpersen1/100) - ((C.ihargajual1 - (C.ihargajual1*B.pddiskonpersen1/100))*B.pddiskonpersen2/100)) * B.pdqty  
                       ,0)'subtotaldetil' 
                       , A.puid 'idpaket', A.pukode 'kdpaket', A.punama 'namapaket'  , B.pdid 'idpaketdetil'
                       , case when A.pujumlah>1 then coalesce((
                         select sdkedatangan from fstokd 
                         inner join fstoku on suid=sdidsu  
                         inner join epaketu on puid=sdidpotongstok 
                         where  susumber ='IP' and sustatus <>9  
                         and sukontak = '".$_POST['kontak']."' and sdcatatankoli = '".$_POST['nopaket']."'  
                         AND ((pusemuacabang=0 and sucabang = '".$cabang."') or pusemuacabang=1) and sdidpotongstok = '".$_POST['idpaket']."' 
                         order by sdkedatangan desc  limit 1  
                       ),99) else 99 end as kedatangan, case when A.pujumlah>1 then 1 else 0 end as daripaket,
                       B.pdpilihan 'pilihan',
                       PUJENISPELANGGAN 'jenispasien' , PUKONSULSAJA 'konsulsaja', PUBERDUA 'berdua', PUPASIENBARU 'pasienbarusaja',
                       
                       case when A.pujumlah>1 and  A.puumur>0 then coalesce((
                       select DATE_FORMAT(sutanggal,'%d-%m-%Y')  
                       from fstokd left join fstoku on suid=sdidsu where sdcatatankoli='".$_POST['nopaket']."'  and sdidpotongstok = '".$_POST['idpaket']."'
                       and sukontak = '".$_POST['kontak']."'
                       order by sutanggal  limit 1 
                       )) else '' end 'tanggalakhir', DATE_FORMAT(current_date,'%d-%m-%Y')  'tanggalsekarang' , A.puumur 'umurmax'
                       
                      FROM epaketu A
                      inner join epaketd B on A.puid=B.pdidu 
                      inner join bitem C on C.iid=B.pditem 
                      inner JOIN bsatuan D ON D.sid=C.isatuan   
                      left JOIN bitemkelompok E ON E.ikid=C.ikelompokbaru   
                      LEFT JOIN bitemjenis F on C.ijenisitem=F.JID
                       where A.puid = '".$_POST['idpaket']."' 
   		                 ";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }
   
   
  //            Set Rs3 = xData.BukaRS3("SELECT IKELOMPOK2020,emasterpromou.*,IHARGAALKES, IBERLAKU,IID, emasterpromod.*, ISATUAN, IKIDJENISBARU, ICUSTOM9, IKODE,INAMA,SNAMA,ISATUAND,IHARGAJUAL1,IDISKON,IJENISITEM,ITIPEITEM,IKATEGORI,ISTOCKMAKSIMAL,    " & xKolomGudang & " as STOK, IDISKON  FROM emasterpromod inner join emasterpromou on mpuid=mpdidu inner join   bitem on ikode = MPDKELITEM1  inner JOIN bsatuan ON SID=ISATUAN   inner JOIN bitemkelompok ON IKID=IKELOMPOKBARU  WHERE MPDID= " & txtNamaPromo.Tag, adOpenStatic, adLockOptimistic)
 
   
   function get_detail_promo(){ 
    	if(empty($_POST['idpromo'])) {
   			echo _pesanError("Id Promo tidak ditemukan !");
  			exit;
   		} 
       $cabang  = @$_SESSION['cabang'] ;   
           
   		$query = " select MPUJENISPROMO 'jenispromo',
   		               C.iid 'iditem', C.ikode 'kditem', C.inama 'namaitem', C.ikelompok2020 'item_tipe2020', F.jwajibdokter 'wajibdokter' , 
                       D.sid 'idsatuan', D.skode 'satuan',  
   		               CB.iid 'iditem2', IFNULL(CB.ikode,'') 'kditem2', CB.inama 'namaitem2', CB.ikelompok2020 'item_tipe20202', FB.jwajibdokter 'wajibdokter2' , 
                       DB.sid 'idsatuan2', DB.skode 'satuan2',   
   		               CC.iid 'iditem3', IFNULL(CC.ikode,'') 'kditem3', CC.inama 'namaitem3', CC.ikelompok2020 'item_tipe20203', FC.jwajibdokter 'wajibdokter3' , 
                       DC.sid 'idsatuan3', DC.skode 'satuan3',  
   		               CD.iid 'iditem4', IFNULL(CD.ikode,'') 'kditem4', CD.inama 'namaitem4', CD.ikelompok2020 'item_tipe20204', FD.jwajibdokter 'wajibdokter4' , 
                       DD.sid 'idsatuan4', DD.skode 'satuan4',  
                       
                       
                       IFNULL(B.MPDMINIMALQTY,0) 'qtydetil',  
                       IFNULL(B.MPDTOTALINVOICE1,0) 'qtydetil1',  IFNULL(B.MPDDISKON,0) 'dis1detil', IFNULL(B.MPDDISKON2,0) 'dis2detil',   IFNULL(C.ihargajual1,0) 'hargadetil',  
                       
                       IFNULL(B.MPDTOTALINVOICE2,0) 'qtydetil2',  IFNULL(B.MPDDISKONITEM2,0) 'dis1detil2', IFNULL(B.MPDDISKONITEM22,0) 'dis2detil2',    IFNULL(CB.ihargajual1,0) 'hargadetil2', 
                       
                       IFNULL(B.MPDMINIMALQTY3,0) 'qtydetil3',  IFNULL(B.MPDDISKON1KE3,0) 'dis1detil3', IFNULL(B.MPDDISKON2KE3,0) 'dis2detil3',    IFNULL(CC.ihargajual1,0) 'hargadetil3', 
                       
                       IFNULL(B.MPDMINIMALQTY4,0) 'qtydetil4',  IFNULL(B.MPDDISKON1KE4,0) 'dis1detil4', IFNULL(B.MPDDISKON2KE4,0) 'dis2detil4',    IFNULL(CD.ihargajual1,0) 'hargadetil4', 
                       
                       B.mpdid 'idpromo', A.mpukode 'kdpromo', A.mpunama 'namapromo', B.mpdpilihan1 'pilihan1' , B.mpdpilihan2 'pilihan2' , B.mpdpilihan3 'pilihan3' , B.mpdpilihan4 'pilihan4' ,
                       
                       MPUMINIMALTRANSAKSI 'minimaltotaltransaksi' , MPDMAX 'maxpasien' ,
                       case when mpdmax > 0 then 
                       ( select  count(sditem) as jumlah 
                        from fstokd ZA inner join fstoku ZB on ZB.suid=ZA.sdidsu 
                        where ZA.sdidpromo = B.mpdid and sucabang = '".$cabang."' and sutanggal = current_date 
                       ) else 0 end 'jumlahambil' , mpdpakaijam, 'pakaijam',
                       TIME_FORMAT(MPDJAM1, '%H:%i') 'jam1', TIME_FORMAT(MPDJAM2, '%H:%i') 'jam2', TIME_FORMAT(current_time, '%H:%i') 'jamsekarang'
                       
                       
                      
                      FROM emasterpromou A
                      inner join emasterpromod B on A.mpuid=B.mpdidu 
                      inner join bitem C on C.ikode=B.MPDKELITEM1  
                      left join bitem CB on CB.ikode=B.MPDKELITEM2  
                      left join bitem CC on CC.ikode=B.MPDKELITEM3 
                      left join bitem CD on CD.ikode=B.MPDKELITEM4
                      inner JOIN bsatuan D ON D.sid=C.isatuan   
                      left JOIN bsatuan DB ON DB.sid=CB.isatuan  
                      left JOIN bsatuan DC ON DC.sid=CC.isatuan   
                      left JOIN bsatuan DD ON DD.sid=CD.isatuan   
                      LEFT JOIN bitemjenis F on C.ijenisitem=F.JID 
                      LEFT JOIN bitemjenis FB on CB.ijenisitem=FB.JID 
                      LEFT JOIN bitemjenis FC on CC.ijenisitem=FC.JID 
                      LEFT JOIN bitemjenis FD on CD.ijenisitem=FD.JID 
                      
                      
                       
                      
                       where B.mpdid = '".$_POST['idpromo']."' 
   		                 ";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }
   
   
    function get_detail_pro(){ 
    	if(empty($_POST['medid'])) {
   			echo _pesanError("Id PRO tidak ditemukan !");
  			exit;
   		} 
       
           
   		$query = "  select  medCode
                           ,lmdQuantity 'qtydetil', ihargajual1 'hargadetil', ifnull(( lmdPrice- lmdDiscount ) / lmdprice * 100,0) 'dis1detil', 0 'dis2detil', ifnull(lmdPrice-lmdDiscount,0) 'diskondetil' ,lmdGrandPrice 'subtotaldetil'
                           , lmcCreateTime,lmcId,1,case when lmcPaymentStatus=0 then 0 else 1 end 'statuspayment'
                        , lmdId, lmdType, PUJUMLAH  
                       , F.kid 'iddokter',F.knama 'namadokter' , F.kid 'idoperator',F.knama 'namaoperator'    
                       , pro.kid 'idpro',pro.knama 'namapro' 
                       , iid 'iditem',ikode 'namaitem' , ikelompok2020 'item_tipe2020' , H.jwajibdokter 'wajibdokter' , case ijenisitem when 0 then 1 else 0 end 'produk'
                       , sid 'idsatuan',skode 'satuan' 
                       , puid 'idpaket', pukode 'kdpaket', punama 'namapaket'  , pdid 'idpaketdetil'
                       , case when pujumlah>1 then 1 else 0 end as daripaket 
                       , mpdid 'idpromo', mpukode 'kdpromo', mpunama 'namapromo'
                       , lmcId 'proidu', lmdId 'proidd'
                       
                       from official_nmw.ops_medical A 
                       left join official_nmw.log_medlib_cart B on A.medId=B.lmcMedicalId  
                       left join bkontak C on C.kid=B.lmcPatientKID  
                       left join official_nmw.log_medlib_detail D on D.lmdCartId=B.lmcId 
                       left join bitem E on E.iid=D.lmdItemId  
                       left join bsatuan G on G.sid=isatuan
                       left join official_nmw.data_users udok on udok.usrid=A.Medcreateid  
                       left join bkontak F on F.kid=udok.usrkid   
                       left join emasterpromod on MPDID=lmdItemTypeId and D.lmdItemType = 'promo'  
                       left join emasterpromou on MPDIDU=MPUID  
                       left join epaketd on PDID=lmdItemTypeId and D.lmdItemType = 'package'  
                       left join epaketu on PDIDU=PUID  
                       left join official_nmw.data_users upro on upro.usrid=B.lmccreateid  
                       left join bkontak pro on pro.kid=upro.usrkid  
                      LEFT JOIN bitemjenis H on E.ijenisitem=H.JID  
                      
                      
                       WHERE coalesce(lmcDiasIP,'') = '' AND lmcPaymentStatus=0 and  lmdSelected=1  and medCode in (".$_POST['medid'].")   
                      
                        
   		                 ";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }
   
   
   
   
   
    function get_detail_web(){ 
    	if(empty($_POST['webid'])) {
   			echo _pesanError("Id WEB tidak ditemukan !");
  			exit;
   		} 
        
                $query = "select  
                      ivdQty 'qtydetil', case when iid=2560 then ihargaweb else ihargajual1 end'hargadetil' 
                    , case when IHARGAJUAL1=ivdPrice then 0 else coalesce(IDISKON,0)/100*IHARGAJUAL1 end 'dis1detil', 0 'dis2detil' 
                    , case when IHARGAJUAL1=ivdPrice then 0 else  coalesce(IDISKON,0) end 'diskondetil'
                    , ivhGrandTotal 'subtotaldetil'
                    , iid 'iditem',ikode 'namaitem' , ikelompok2020 'item_tipe2020', H.jwajibdokter 'wajibdokter'  
                    , sid 'idsatuan',skode 'satuan' 
                    , F.kid 'iddokter',F.knama 'namadokter'  
                    , case when ivhShippingPrice='-' then 0 else coalesce(ivhShippingPrice,0) end 'hargaongkir' 
                    , ivdId 'medidd', ivhId 'medidu', payStatus 'statusbayar', ivhCode 'norefmerchant', medcode 'noref' ,rsvRtpId 'tiperesep', ivhGrandTotal 'totaltransaksidanbayar'
                    , medcode 'kodemedlib', medid 'idmedlib'
                       
                       from official_nmw.ops_invoice_detail 
                       inner join official_nmw.ops_invoice_header on ivdIvhId=ivhId 
                        inner join bitem on iid = ivdIID 
                        inner join bsatuan on isatuan=sid 
                        inner join official_nmw.ops_payment on payIvhCode=ivhCode 
                        inner join official_nmw.ops_medical on medId=ivhMedId 
                        inner join official_nmw.data_users on usrId=medCreateId    
                        inner join bkontak on kid=ivhkid
                        left join bkontak F on F.kid=usrkid    
                        left join official_nmw.data_voucher on ops_invoice_header.ivhVchId = data_voucher.vchId 
                        left join official_nmw.ops_reservation on rsvMedId=medId 
                        left join official_nmw.ops_reservation_type on rsvRtpId=rtpId  
                        LEFT JOIN bitemjenis H on ijenisitem=H.JID  
                      
                       WHERE payStatus = 1 And ivhStatusDIAS = 0 and ivhCode in (".$_POST['webid'].")  
                        
   		                 ";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }
    
     
    function get_detail_web_blmiv(){ 
    	if(empty($_POST['webid'])) {
   			echo _pesanError("Id WEB tidak ditemukan !");
  			exit;
   		} 
        
                $query = "select  
                      mdcqty 'qtydetil', 
                      case when pasien.ktipe = 12 and (coalesce(data_pos_nmw_2023.f_tanggal_akhir (pasien.kid)) <> '' or  date_add(coalesce(data_pos_nmw_2023.f_tanggal_akhir (pasien.kid)), interval 1 year) >= curdate() )then ihargaweb else ihargajual1 end 'hargadetilx' , ihargajual1 'hargadetil'
                    , case when pasien.ktipe = 12 and (coalesce(data_pos_nmw_2023.f_tanggal_akhir (pasien.kid)) <> '' or  date_add(coalesce(data_pos_nmw_2023.f_tanggal_akhir (pasien.kid)), interval 1 year) >= curdate() ) then coalesce(IDISKON,0) else 0 end 'dis1detil'
                    , 0 'dis2detil' 
                    , case when pasien.ktipe = 12 and (coalesce(data_pos_nmw_2023.f_tanggal_akhir (pasien.kid)) <> '' or  date_add(coalesce(data_pos_nmw_2023.f_tanggal_akhir (pasien.kid)), interval 1 year) >= curdate() )  then coalesce(IDISKON,0)/100*IHARGAJUAL1 else 0 end 'diskondetil'
                    , case when pasien.ktipe = 12 and (coalesce(data_pos_nmw_2023.f_tanggal_akhir (pasien.kid)) <> '' or  date_add(coalesce(data_pos_nmw_2023.f_tanggal_akhir (pasien.kid)), interval 1 year) >= curdate() )then ihargaweb*mdcqty else ihargajual1*mdcqty end 'subtotaldetil'
                    , iid 'iditem',ikode 'namaitem' , ikelompok2020 'item_tipe2020', H.jwajibdokter 'wajibdokter'  
                    , sid 'idsatuan',skode 'satuan' 
                    , F.kid 'iddokter',F.knama 'namadokter'  
                    , 0 'hargaongkir' 
                    , mdcid 'medidd', medid 'medidu', payStatus 'statusbayar',  medcode 'noref' , rsvRtpId 'tiperesep'   
                    , medcode 'kodemedlib', medid 'idmedlib'
                       
                       from official_nmw.ops_medical
                          left join official_nmw.ops_medical_medicine on medid=mdcmedid 
                          left join bitem on data_pos_nmw_2023.bitem.iid = ops_medical_medicine.mdcIID 
                          left join official_nmw.data_users on usrid = medcreateid 
                          left join bkontak F on usrkid =F.kid  
                          left join official_nmw.ops_invoice_header on medId=ivhMedId 
                          left join official_nmw.ops_reservation on rsvMedId=medId 
                          left join official_nmw.ops_reservation_type on rsvRtpId=rtpId    
                          left join official_nmw.ops_payment on payIvhCode=ivhCode 
                          left join bkontak pasien on medKID =pasien.kid 
                          LEFT JOIN official_nmw.ops_reservation_date  ON rsvRsdId = rsdId  
                          left join official_nmw.ops_invoice_detail on ivdIvhId=IvhId  
                        LEFT JOIN bitemjenis H on ijenisitem=H.JID 
                       left join bsatuan G on G.sid=isatuan 
                      
                      where   coalesce(ikode,'') <> '' and (coalesce( payStatus ,0) = 0 or (coalesce(payStatus ,0)=1 and coalesce(ivdIID ,0)=2560 ))  
                      and TIMESTAMPDIFF(MONTH, rsddate, NOW())<=1 and medcode in (".$_POST['webid'].")  
                        
   		                 ";
 
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }
   
   
   
   
   
   function getdata_voucherweb(){  
    	if(empty($_POST['novocer'])) {
   			echo _pesanError("No Voucher Kosong !");
  			exit;
   		} 
       
       // Set Rs1 = xData.BukaRS("select bvoucherpoint.*,MPVDITEM1,IKODE,KNAMA,MPUJENIS from bvoucherpoint left join bkontak on vpkontak=kid left join emasterpromovd on VPIDPROMOD=MPVDID left join bitem on iid=mpvditem1  left join emasterpromovu on mpvuid=mpvdidu where VPIDVOUCHER = '" & txtNoVoucherTADA & "' ") ' and  VPKONTAK = " & eFrmPOS2.txtKontak.Tag)
          
   		$query = " select  mpvditem1 'item1',ikode 'kodeitem', knama 'namapasien', mpujenis 'jenis' , 
   		            DATE_FORMAT(VPTANGGALEXPIRED,'%d-%m-%Y')  'tglexpired ', DATE_FORMAT(current_date,'%d-%m-%Y') 'tglhariini', iid 'iditem'
   		            , vpkontak 'kontak', vpstatus 'status', vpid 'idvoucher' ,   vpjumlahpoint 'jumlahpoint', vpdiskon 'diskonpersen'
               		from bvoucherpoint 
               		left join bkontak on vpkontak=kid 
               		left join emasterpromovd on VPIDPROMOD=MPVDID 
               		left join bitem on iid=mpvditem1  
               		left join emasterpromovu on mpvuid=mpvdidu 
               		where VPIDVOUCHER = '".$_POST['novocer']."'  " ;
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }
    
    
   
   
   function getdata_bukapassword(){  
     
        $jenis = $_POST['jenis'] ;
        $username = $_POST['username'] ;
        $password = $_POST['password'] ;
   		//$password = hash('sha512',md5($password));
   		$password = md5($password);
   		
   		if ($jenis == 'editharga')
   		{
   		    $idmenu = 673 ;
            $query  = " select UNAMA,upassword,UID from ausermenu LEFT JOIN auser ON AUIDUSER=UID where AUIDMENU = '".$idmenu."' and AUAPPROVE = 1 and ukode = '".$username."' and upassword = '".$password."' ";
   		} else 	if ($jenis == 'appcanceltransaksi')
   		{
   		     
            $query  = " select UNAMA,upassword,UID from auser left join auuserrole on AURIDUSER=uid where AURIDROLE=15 and AURSTATUS=1 and ukode = '".$username."' and upassword = '".$password."' ";
            //$query  = " select UNAMA,upassword,UID from ausermenu LEFT JOIN auser ON AUIDUSER=UID where AUIDMENU = '".$idmenu."' and AUAPPROVE = 1 and ukode = '".$username."' and upassword = '".$password."' ";
   		} 	
               		
              
        
        
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }
    
    
       

   function getdata_bukapassword3() { 
       
                $username = $_POST['username'] ;
                $password = $_POST['password'] ;
           		$password = hash('sha512',md5($password));
                $jenis = $_POST['jenis'] ;
           		
       
        $query  = " select UNAMA,upassword,UID from ausermenu LEFT JOIN auser ON AUIDUSER=UID where   unama = '".$username."'  "; 
        $cek = $this->M_transaksi->get_data_query($query);  
        if($cek->num_rows() > 0) { 
            
            
                $query  = " select UNAMA,upassword,UID from ausermenu LEFT JOIN auser ON AUIDUSER=UID where AUIDMENU = 255 and AUAPPROVE = 1 and  unama = '".$username."'  "; 
                $cek1 = $this->M_transaksi->get_data_query($query);  
                if($cek1->num_rows() > 0) {  
                    $query  = " select UNAMA,upassword,UID from ausermenu LEFT JOIN auser ON AUIDUSER=UID where AUIDMENU = 255 and AUAPPROVE = 1 and unama = '".$username."' and upassword = '".$password."' ";
                    header('Content-Type: application/json');
                    echo $this->M_transaksi->get_data_query($query); 
                }
                else 
                {
                   echo _pesanError("User tidak memiliki hak buka akses !");
          			exit; 
                    
                }    
                    
        }
        else 
        {
           echo _pesanError("User tidak dikenali !");
  			exit; 
            
        }
    }                       



   
   function getdata_diskonvocer(){  
    	if(empty($_POST['idvoucher'])) {
   			echo _pesanError("ID Voucher Kosong !");
  			exit;
   		}    		
               		
        $query  = "SELECT   vid AS 'id',vnomor AS 'nomor', vnilai AS 'nilai', vitem AS 'item' , vjenis AS 'jenis', 
                  v1transaksi 'v1transaksi', vproduksaja 'produksaja', vtglexpired 'tglexpired', vitem2 'vitem2', vrupiah 'rupiah', vnilai2, ikode
                  from bvoucher left join bitem on iid=vfreeitem 
                  where vid = '".$_POST['idvoucher']."'   
                  ";
                  
                  
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }
   
   
   function kirim_email() {
    	if(empty($_POST['noip'])) {
   			echo _pesanError("No IP Kosong !");
  			exit;
   		}    		
       
       $nomornya = $_POST['noip'] ;
       
        $data = array(
        'ip' => $nomornya 
        );
    $json_data = json_encode($data);
    
    $url = 'https://api.nmwclinic.co.id/legacy/invoice/v1/queue';
    $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: 25c9b278bd695ad19a86a2ce0cb21b4ac1e018b494030e9f886b8a819c544ba2' 
        ));   
      $response = curl_exec($ch);
    
            if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
        
       curl_close($ch); 
       
      
    
       $result = json_decode($response); 
       
       $message=$result->message ; 
       $status=$result->status; 
       $errors=''; 
        
       $statusnilai=0;
       if ($result->status == 'true') {
          $statusnilai=1;  
       } 
       else
       {
            $errors2=$result->errors[0]; 
            $errors=$errors2->message;
       }
    
       $logemail = array(
            'LENOIP' => $nomornya,
            'LESTATUS' => $statusnilai,
            'LERESPON' => $response                                      
        );
        $this->db->insert('zlogemail',$logemail);  
       
       
       
       $callback = array(     
                'status'=>$status, 
                'message'=>$message,  
                'errors'=> $errors
           ); 
       
       
        
        echo json_encode($callback);   
    }
    
    
     

   function get_cek_diskon_karyawanx() {
   		if(empty($_POST['id'])) {
   			echo _pesanError("Data Pasien Karyawan Kosong !");
  			exit;
   		}
   		
   		//Set Rs1 = xData.BukaRS("select sum(sdkeluar) as jumlah from fstokd left join fstoku on suid=sdidsu LEFT JOIN bitem ON IID=SDITEM where sddiskonpersen=100 and susumber = 'IP' and sukontak=" & txtKontak.Tag & "   and year(sutanggal) = " & Year(txtTanggal) & " and month(sutanggal) = " & Month(txtTanggal)) 
               
        $query  = "SELECT coalesce(sum(sdkeluar),0) as jumlah
                from fstokd left join fstoku on suid=sdidsu LEFT JOIN bitem ON IID=SDITEM where sddiskonpersen=100 and susumber = 'IP' and sustatus<>9 and sukontak='".$_POST['id']."'
                and year(sutanggal) = year(current_date) and month(sutanggal) = month(current_date)  ";
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }     
    
    function get_cek_diskon_karyawan() {
   		if(empty($_POST['id'])) {
   			echo _pesanError("Data Pasien Karyawan Kosong !");
  			exit;
   		}
   		
   		//Set Rs1 = xData.BukaRS("select sum(sdkeluar) as jumlah from fstokd left join fstoku on suid=sdidsu LEFT JOIN bitem ON IID=SDITEM where sddiskonpersen=100 and susumber = 'IP' and sukontak=" & txtKontak.Tag & "   and year(sutanggal) = " & Year(txtTanggal) & " and month(sutanggal) = " & Month(txtTanggal)) 
               
        $query  = "SELECT 1 as jumlah ";
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }    
   

   function get_cek_diskon_karyawan_35() {
   		if(empty($_POST['id'])) {
   			echo _pesanError("Data Pasien Karyawan Kosong !");
  			exit;
   		}
   		
   		//Set Rs1 = xData.BukaRS("select sum(sdkeluar) as jumlah from fstokd left join fstoku on suid=sdidsu LEFT JOIN bitem ON IID=SDITEM where sddiskonpersen=100 and susumber = 'IP' and sukontak=" & txtKontak.Tag & "   and year(sutanggal) = " & Year(txtTanggal) & " and month(sutanggal) = " & Month(txtTanggal)) 
               
        $query  = "SELECT coalesce(sum(sdkeluar),0) as jumlah
                from fstokd left join fstoku on suid=sdidsu LEFT JOIN bitem ON IID=SDITEM where sddiskonpersen=35 and susumber = 'IP' and sustatus<>9 and sukontak='".$_POST['id']."'
                and year(sutanggal) = year(current_date) and month(sutanggal) = month(current_date)  ";
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }    
   
      
    
    function getnomorip2(){
        $cabang  = @$_SESSION['cabang'] ;
        $tgl=$this->input->post('tgl');
        alert($tgl);
        $nomor = 0;
        $nomor1 = $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai',NID));
        $nomor2 = tgl_notrans($tgl);  

        $notrans_length = strlen($nomor1)+4;

        $sql = "SELECT MAX(RIGHT(sunotransaksi,4)) as 'maks' 
                  FROM fstoku 
                 WHERE LEFT(ipunotransaksi,".$notrans_length.")='".$nomor1.$nomor2."' and susumber = 'IP'and sucabang='".$cabang."' ";

        $query = $this->db->query($sql);
        foreach ($query->result() as $res) {
            $nomor = number_format($res->maks)+1;
        }

        switch(strlen($nomor)){
        case 1:
          $nomor=$nomor1.$nomor2."000".$nomor;
          break;
        case 2:
          $nomor=$nomor1.$nomor2."00".$nomor;
          break;
        case 3:
          $nomor=$nomor1.$nomor2."0".$nomor;
          break;
        case 4:
          $nomor=$nomor1.$nomor2.$nomor;
          break;
        }
        
        $query = "select '".$nomor."' as no from buang where uid=1 " ; 
          header('Content-Type: application/json');
          echo $this->M_transaksi->get_data_query($query);
    }            

function getnomorip(){
        $cabang  = @$_SESSION['cabang'] ;
        $kodecabang  = @$_SESSION['kodecabang'] ;
        //alert($cabang);
       //alert($kodecabang);
        $tgl=$this->input->post('tgl');
        $nomor = 0;
        $nomor1 = $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai',NID));
        $nomor2 = tgl_notrans($tgl);  

        $notrans_length = strlen($nomor1)+4;

        $sql = "SELECT MAX(RIGHT(sunotransaksi,4)) as 'maks' 
                  FROM fstoku 
                 WHERE MID(sunotransaksi,4,".$notrans_length.")='".$nomor1.$nomor2."' and sucabang='".$cabang."'  ";

        $query = $this->db->query($sql);

        foreach ($query->result() as $res) {
            $nomor = number_format($res->maks)+1;
        }

        switch(strlen($nomor)){
        case 1:
          $nomor=$nomor1.$nomor2."000".$nomor;
          break;
        case 2:
          $nomor=$nomor1.$nomor2."00".$nomor;
          break;
        case 3:
          $nomor=$nomor1.$nomor2."0".$nomor;
          break;
        case 4:
          $nomor=$nomor1.$nomor2.$nomor;
          break;
        }
          $nomor=$kodecabang."-".$nomor ;
          $query = "select '".$nomor."' as no from buang where uid=1 " ; 
          header('Content-Type: application/json');
          echo $this->M_transaksi->get_data_query($query);
    }


}