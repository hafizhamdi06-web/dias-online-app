<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_PJ_POS_HP extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }


    function getRiwayatHariIni(){
        $idkaryawan = @$_SESSION['idkaryawan'];
        $query = "SELECT A.suid 'id', A.sunotransaksi 'nomor', DATE_FORMAT(A.sucreated,'%H:%i') 'jam',
                          COALESCE(B.knama,'-') 'pasien', IFNULL(A.sutotaltransaksi,0) 'total'
                     FROM fstoku A
                LEFT JOIN bkontak B ON A.sukontak = B.kid
                    WHERE A.susumber = 'IP' AND A.sustatus <> 9
                      AND A.sukaryawan = '".$idkaryawan."'
                      AND DATE(A.sutanggal) = CURDATE()
                 ORDER BY A.sutanggal DESC";
        $sql = $this->db->query($query);
        return json_encode(array('data' => $sql->result_array()));
    }


    function transfer_transaksi_cancel($idu){
        
         $sql = "SELECT * 
                  FROM fstoku 
                 WHERE suid = $idu  ";
                 
                  $CI =& get_instance();
                 
        $datareport = $CI->M_transaksi->get_data_query($sql);
        $datareport = json_decode($datareport);
        foreach ($datareport->data as $res) {  
            
             $data_header_bkg = array(                         
                                    
                        'sunotransaksi' => $res->SUNOTRANSAKSI,
                        'susumber' => $res->SUSUMBER,
                        'sutanggal' => $res->SUTANGGAL,
                        'sukontak' => $res->SUKONTAK,
                        'sukaryawan' => $res->SUKARYAWAN,
                         
                        'suuraian' => $res->SUURAIAN,
                        'sucatatan' => $res->SUCATATAN,
                        'sutotaltransaksi' => $res->SUTOTALTRANSAKSI,
                        'sutotalbayar' => $res->SUTOTALBAYAR,
                        'sutotalsisa' => $res->SUTOTALSISA,
                        
                        'sutotalkas' => $res->SUTOTALKAS,
                        
                        'sutotalkartudebit' => $res->SUTOTALKARTUDEBIT,'sunokartudebit' => $res->SUNOKARTUDEBIT,'sunamadebit' => $res->SUNAMADEBIT,
                        'subankdebit' => $res->SUBANKDEBIT,'sudebitjenis' => $res->SUDEBITJENIS,'suattention' => $res->SUATTENTION,
                        
                        'sutotalkartukredit' => $res->SUTOTALKARTUKREDIT,'sunokartukredit' => $res->SUNOKARTUKREDIT,'sunamakredit' => $res->SUNAMAKREDIT,
                        'subankkredit' => $res->SUBANKKREDIT,'sukreditjenis' => $res->SUKREDITJENIS,'sunofakturpajak' => $res->SUNOFAKTURPAJAK, 
                        
                        'sutotaltransfer' => $res->SUTOTALTRANSFER,'sunotransfer' => $res->SUNOTRANSFER,'sunamatransfer' => $res->SUNAMATRANSFER,'subanktransfer' => $res->SUBANKTRANSFER ,
                        'sutotalvoucher' => $res->SUTOTALVOUCHER,'sunovoucher' => $res->SUNOVOUCHER,'sustatuskirim' => $res->SUSTATUSKIRIM,
                        
                        'sutotaldp' =>$res->SUTOTALDP, 'sudp1' => $res->SUDP1, 'sudpid' => $res->SUDPID, 'sujenisdp' => $res->SUJENISDP, 
                        'sumerchantjenis' => $res->SUMERCHANTJENIS,'sumerchantno' => $res->SUMERCHANTNO,'sumerchantjumlah' => $res->SUMERCHANTJUMLAH, 
                        
                        'sucabang' => $res->SUCABANG,  
                        'surekammedis' => $res->SUREKAMMEDIS,    
                        'sutotaltada ' => $res->SUTOTALTADA,
                        
                        'surekhutang' => $res->SUREKHUTANG,  'sufarmasi' => $res->SUFARMASI, 'sufarmasiasisten' => $res->SUFARMASIASISTEN,  'susalesmarketing' => $res->SUSALESMARKETING,
                        'sukliniklain' => $res->SUKLINIKLAIN,
                        'sudkkwalkin' => $res->SUDKKWALKIN,
                        
                        'sunilaipiutang' => $res->SUNILAIPIUTANG,
                        
                        'susurgerydpidu' => $res->SUSURGERYDPIDU,
                        'susurgerydptotal' => $res->SUSURGERYDPTOTAL,
                        'susurgerydppembayaran' => $res->SUSURGERYDPPEMBAYARAN,
                        'susurgerydppiutang' => $res->SUSURGERYDPPIUTANG,
                        'susurgerydp' => $res->SUSURGERYDP,
                        
                        'sukodetele' => $res->SUKODETELE, 
                        'sureviewnilai' => $res->SUREVIEWNILAI,
                        'sureviewcatatan' => $res->SUREVIEWCATATAN,
                        
                        'suidmedlib' => $res->SUIDMEDLIB,
                        'sulmcid' => $res->SULMCID, 
                        
                        'suteman' => $res->SUTEMAN,
                        'SUNOIPLAMA'  =>$res->SUNOIPLAMA,
                                            
                        'sucreateu' => $res->SUCREATEU, 
                        'sumodifu' => $res->SUMODIFU , 
                        'sualamat' => $res->SUALAMAT               
                    );        
            
                    $this->db->insert('fstoku_cancel',$data_header_bkg); 
                    $id = $this->db->insert_id();
            
            
        }
        
        
        
         $sql = "SELECT * 
                  FROM fstokd 
                 WHERE sdidsu = $idu  ";
                 
                  $CI =& get_instance();
                 
        $datareport = $CI->M_transaksi->get_data_query($sql);
        $datareport = json_decode($datareport);
        foreach ($datareport->data as $res) {  
            
             $data_header_bkg = array(                         
                                    
                        'sdurutan' => $res->SDURUTAN,
                    'sdidsu' => $id, 
                    'sditem' => $res->SDITEM,
                    'sdkeluar' => $res->SDKELUAR, 
                    'sdkeluard' => $res->SDKELUARD,
                    'sdharga' => $res->SDHARGA,
                    'sddiskonpersen' => $res->SDDISKONPERSEN,   
                    'sddiskonpersen2' => $res->SDDISKONPERSEN2,  
                    'sddiskon' => $res->SDDISKON,
                    'sdsatuan' => $res->SDSATUAN,
                    'sdsatuand' => $res->SDSATUAND,
                    'sdgudang' => $res->SDGUDANG, 
                    'sddokter' => $res->SDDOKTER,
                    'sdkaryawan' =>  $res->SDKARYAWAN, 
                    'sdnoref' => $res->SDNOREF, 
                    'sdlantai2' => $res->SDLANTAI2, 
                    'sdcatatankoli' => $res->SDCATATANKOLI,
                    'sdkedatangan' => $res->SDKEDATANGAN, 
                    'sdidpotongstok' => $res->SDIDPOTONGSTOK,
                    'sdsodurutan' => $res->SDSODURUTAN,
                    'sddaripaket' => $res->SDDARIPAKET, 
                    'sdidpromo' => $res->SDIDPROMO, 
                    'sdreferal' => $res->SDREFERAL, 
                    'sdpro' => $res->SDPRO,
                    'sdprorecom' => $res->SDPRORECOM,
                    'SDMEDIDU' => $res->SDMEDIDU,
                    'SDMEDIDD' => $res->SDMEDIDD,
                    'SDPROIDU' => $res->SDPROIDU,
                    'SDPROIDD' => $res->SDPROIDD,
                    'sddiskon2' => $res->SDDISKON2,
                    'sdcetak' => $res->SDCETAK,
                    'SDVOUCERID' => $res->SDVOUCERID,
                    'SDPOINTKELUAR' => $res->SDPOINTKELUAR,
                    'SDSODID' => $res->SDSODID,
                    'SDPRDID' => $res->SDPRDID               
                    );        
            
                     $this->db->insert('fstokd_cancel',$data_header_bkg); 
        }
        
          $sql = "SELECT * 
                  FROM fstokdiscv 
                 WHERE SDVIDU = $idu  ";
                 
                  $CI =& get_instance();
                 
        $datareport = $CI->M_transaksi->get_data_query($sql);
        $datareport = json_decode($datareport);
        foreach ($datareport->data as $res) {  
            
             $data_header_bkg = array(                         
                       
                     'SDVURUTAN' =>   $res->SDVURUTAN ,
                    'SDVIDVOUCHER' => $res->SDVIDVOUCHER ,
                    'SDVIDU' => $id,
                    'SDVNILAI' => $res->SDVNILAI ,
                    'SDVURUTANITEM' => $res->SDVURUTANITEM ,
                    'SDVITEM1' => $res->SDVITEM1 , 
                    'SPVD1BON' => $res->SPVD1BON ,   
                    'SDVITEM2' => $res->SDVITEM2 ,  
                    'SDVRUPIAH' => $res->SDVRUPIAH ,
                    'SDVNILAI2' => $res->SDVNILAI2 ,
                    'SDVFREEITEM' => $res->SDVFREEITEM ,
                       
                       
                    );        
            
                    $this->db->insert('fstokdiscv_cancel',$data_header_bkg); 
        }
        
        
    }

    private function _isSelfApprover($jenis)
    {
        $roleName = 'Approve '.$jenis;
        $r = $this->db->query(
            "SELECT 1 FROM aauserrole B
               INNER JOIN aarole C ON B.AURIDROLE = C.ARID
              WHERE B.AURIDUSER = '".$this->session->id."'
                AND B.AURSTATUS = 1
                AND C.ARNAMAROLE = '".$this->db->escape_str($roleName)."'
              LIMIT 1"
        )->row();
        return $r ? true : false;
    }

    private function _cekApprovalOverduePOS($id)
    {
        $row = $this->db->query("SELECT sutanggal, sunotransaksi FROM fstoku WHERE suid='".$id."'")->row();
        if (!$row) return true;

        $selisihHari = (strtotime(date('Y-m-d')) - strtotime($row->sutanggal)) / 86400;
        if ($selisihHari < 1) return true;

        if ($this->_isSelfApprover('Edit POS Overdue')) return true;

        $approved = $this->db->query(
            "SELECT APID FROM aapersetujuan
              WHERE APIDUSERMINTA='".$this->session->id."'
                AND APJENIS='Edit POS Overdue'
                AND APREFERENSI='".$this->db->escape_str($row->sunotransaksi)."'
                AND APSTATUS=1
                AND APTGLEXPIRED > '".date('Y-m-d H:i:s')."'
              ORDER BY APID DESC LIMIT 1"
        )->row();

        return $approved ? true : false;
    }

    private function _cekPersetujuanHargaPOS($iditem)
    {
        if ($this->_isSelfApprover('Buka Kunci Harga POS')) return true;

        $approved = $this->db->query(
            "SELECT APID FROM aapersetujuan
              WHERE APIDUSERMINTA='".$this->session->id."'
                AND APJENIS='Buka Kunci Harga POS'
                AND APREFERENSI='".$this->db->escape_str($iditem)."'
                AND APSTATUS=1
                AND APTGLEXPIRED > '".date('Y-m-d H:i:s')."'
              ORDER BY APID DESC LIMIT 1"
        )->row();

        return $approved ? true : false;
    }

    private function _cekSemuaHargaUnlockPOS($d)
    {
        foreach ($d as $item) {
            if (!empty($item->hargaunlocked) && $item->hargaunlocked == 1) {
                if (!$this->_cekPersetujuanHargaPOS($item->item)) {
                    return false;
                }
            }
        }
        return true;
    }

    function ubahTransaksi(){
        $id = $this->input->post('id');

        if (!$this->_cekApprovalOverduePOS($id)) {
            return json_encode(array('pesan'=>'butuh_persetujuan'));
        }


        $surgerydp = 0 ;  
        if($_POST['surgerydptotal']!='0'){
            $surgerydp = 1 ;
        }    
        if($_POST['piutangjumlah']!='0'){
            $surgerydp = 1 ;
        }  
        
        $debitbank = $_POST['debitbank'] ;  if( empty($_POST['debitbank']) ||$_POST['debitbank']== 'null' ) $debitbank='' ;
        $kreditbank = $_POST['kreditbank'] ;  if( empty($_POST['kreditbank']) ||$_POST['kreditbank']== 'null' ) $kreditbank='' ;
        $transferbank = $_POST['transferbank'] ;  if( empty($_POST['transferbank']) ||$_POST['transferbank']== 'null' ) $transferbank='' ;
        $merchantjenis = $_POST['merchantjenis'] ;  if( empty($_POST['merchantjenis']) ||$_POST['merchantjenis']== 'null' ) $merchantjenis='' ;
         
        
        
        $this->db->trans_start(); 
        
       
        $x = $this->transfer_transaksi_cancel($id);

        $data_header_bkg = array(                         
                        
                        'sukontak' => $_POST['kontak'],
                        'sukaryawan' => $_POST['karyawan'],
                         
                        'suuraian' => 'Penjualan - POS',
                        'sucatatan' => $_POST['catatan'],
                        'sutotaltransaksi' => $_POST['tsubtotal'],
                        'sutotalbayar' => $_POST['totalbayar'],
                        'sutotalsisa' => $_POST['totalsisa'], 
                        
                        'sutotalkas' => $_POST['kasjumlah'],
                        
                        'sutotalkartudebit' => $_POST['debitjumlah'],'sunokartudebit' => $_POST['debitno'],'sunamadebit' => $_POST['debitnama'],
                        'subankdebit' => $debitbank,'sudebitjenis' => $_POST['debitjenis'],'suattention' => $_POST['debitbanklain'],
                        
                        'sutotalkartukredit' => $_POST['kreditjumlah'],'sunokartukredit' => $_POST['kreditno'],'sunamakredit' => $_POST['kreditnama'],
                        'subankkredit' => $kreditbank,'sukreditjenis' => $_POST['kreditjenis'],'sunofakturpajak' => $_POST['kreditbanklain'], 
                        
                        'sutotaltransfer' => $_POST['transferjumlah'],'sunotransfer' => $_POST['transferno'],'sunamatransfer' => $_POST['transfernama'],'subanktransfer' => $transferbank ,
                        'sutotalvoucher' => $_POST['voucherjumlah'],'sunovoucher' => $_POST['voucherno'],'sustatuskirim' => $_POST['voucherid'],
                        
                        'sutotaldp' => $_POST['dpjumlah'], 'sudp1' => $_POST['dpjumlah'], 'sudpid' => $_POST['dpid'], 'sujenisdp' => $_POST['dpjenis'], 
                        'sumerchantjenis' => $merchantjenis,'sumerchantno' => $_POST['merchantno'],'sumerchantjumlah' => $_POST['merchantjumlah'], 
                        
                        'sucabang' => $_POST['cabang'],  
                        'surekammedis' => $_POST['rekammedis'],    
                        'sutotaltada ' => $_POST['totaltanpadp'],
                        
                        'surekhutang' => $_POST['training'],  'sufarmasi' => $_POST['farmasi'], 'sufarmasiasisten' => $_POST['farmasiasisten'],  'susalesmarketing' => $_POST['salesmarketing'],
                        'sukliniklain' => $_POST['kliniklain'], 
                        'sudkkwalkin' => $_POST['dkkwalkin'],
                        
                        'sunilaipiutang' => $_POST['piutangjumlah'],
                        
                        'susurgerydpidu' => $_POST['surgerydpidu'],
                        'susurgerydptotal' => $_POST['surgerydptotal'],
                        'susurgerydppembayaran' => $_POST['surgerydppembayaran'],
                        'susurgerydppiutang' => $_POST['surgerydppiutang'],
                        'susurgerydp' => $surgerydp,
                        
                        'sukodetele' => $_POST['kodetele'], 
                        'sureviewnilai' => $_POST['reviewnilai'],
                        'sureviewcatatan' => $_POST['reviewcatatan'],
                        
                        'suidmedlib' => $_POST['idmedlib'], 
                        'sulmcid' => $_POST['lmcidpro'], 
                        'suteman' => $_POST['teman'], 
                        'sualamat' => $_POST['alasanedit'], 
                        
                        
                        
                        'sumodifu' => $this->session->id               
        );        

        $this->db->where('suid', $id);
        $this->db->update('fstoku',$data_header_bkg);   
        
        
        $gudang = $_POST['cabang'] ;
        $r=1;
        $d = json_decode($_POST['detil']);

        if (!$this->_cekSemuaHargaUnlockPOS($d)) {
            $this->db->trans_rollback();
            return json_encode(array('pesan'=>'butuh_persetujuan_harga'));
        }

        if ($gudang == 18) {
            foreach ($d as $item) {
                if (empty($item->diskonunlocked) || $item->diskonunlocked != 1) {
                    $item->dis1 = 0;
                    $item->dis2 = 0;
                    $item->diskon = 0;
                    $item->diskon2 = 0;
                }
            }
        }
        foreach($d as $item){
            $dokter=$item->dokter ;
            if($dokter=='')  $dokter= NULL ;
            $operator=$item->operator ;
            if($operator=='')  $operator= NULL ;

            $cetak=$item->cetak;
            if($cetak=='') $cetak=1;


            $data_detil_bkg = array(
                    'sdurutan' => $r,
                    'sdidsu' => $id,
                    'sdsumber' => 'IP',
                    'sditem' => $item->item, 
                    'sdkeluar' => $item->qty, 
                    'sdkeluard' => $item->qty, 
                    'sdharga' => $item->harga, 
                    'sddiskonpersen' => $item->dis1,   
                    'sddiskonpersen2' => $item->dis2,   
                    'sddiskon' => $item->diskon , 
                    'sdsatuan' => $item->satuan , 
                    'sdsatuand' => $item->satuan, 
                    'sdgudang' => $gudang,  
                    'sddokter' => $dokter,
                    'sdkaryawan' =>  $operator,  
                    'sdnoref' => $item->noref, 
                    'sdlantai2' => $item->noic, 
                    'sdcatatankoli' => $item->nopaketdetil, 
                    'sdkedatangan' => $item->kedatanganke, 
                    'sdidpotongstok' => $item->paket,
                    'sdsodurutan' => $item->idpaketdetil, 
                    'sddaripaket' => $item->daripaket, 
                    'sdidpromo' => $item->promo, 
                    'sdreferal' => $item->referal, 
                    'sdpro' => $item->aos, 
                    'sdprorecom' => $item->recom,
                    'SDMEDIDU' => $item->medidu,
                    'SDMEDIDD' => $item->medidd,
                    'SDPROIDU' => $item->proidu,
                    'SDPROIDD' => $item->proidd ,
                    'sddiskon2' => $item->diskon2,
                    'sdcetak' => $cetak ,
                    'SDVOUCERID' => $item->idvoucherwebdetil ,
                    'SDPOINTKELUAR' => $item->pointvoucherwebdetil ,
                    'SDSODID' => $item->medidd_sudahbayar   ,
                    'SDPRDID' => $item->medidu_sudahbayar  
            );
           
            
            $sql = "SELECT * FROM fstokd  where sdidsu  = $id and sdurutan =  $r  "; 
            $query = $this->db->query($sql); 
            $row_cnt = $query->num_rows();
            
            if($row_cnt==0){
                  $this->db->insert('fstokd',$data_detil_bkg);            
            } else {
               $this->db->where('sdidsu', $id); 
                $this->db->where('sdurutan', $r);
                $this->db->update('fstokd',$data_detil_bkg); 
            }  

            $r++;
        }  
       
        // Insert Detil Trans
       
          $r--;
          $sql = "delete from fstokd  where sdidsu  = $id and sdurutan >  $r  "; 
            $query = $this->db->query($sql); 
            
        
       // save voucher
      
        
        $r=1;
        $d = json_decode($_POST['detil_v']);
        foreach($d as $item){
            
            $data_detil_bkg = array( 
                    'SDVURUTAN' => $r,
                    'SDVIDVOUCHER' => $item->vid,
                    'SDVIDU' => $id,
                    'SDVNILAI' => $item->vnilai, 
                    'SDVURUTANITEM' => $item->vbaris, 
                    'SDVITEM1' => $item->vitem, 
                    'SPVD1BON' => $item->vuntuk1bon,   
                    'SDVITEM2' => $item->vitem2,   
                    'SDVRUPIAH' => $item->vpersentase , 
                    'SDVNILAI2' => $item->vnilai2 , 
                    'SDVFREEITEM' => $item->vfreeitem 
            );
           
            
            $sql = "SELECT * FROM fstokdiscv  where SDVIDU  = $id and SDVURUTAN =  $r  "; 
            $query = $this->db->query($sql); 
            $row_cnt = $query->num_rows();
            
            if($row_cnt==0){
                  $this->db->insert('fstokdiscv',$data_detil_bkg);            
            } else {
               $this->db->where('SDVIDU', $id); 
                $this->db->where('SDVURUTAN', $r);
                $this->db->update('fstokdiscv',$data_detil_bkg); 
            }  

            $r++;
        }  
       
        // Insert Detil Trans
       
          $r--;
          $sql = "delete from fstokdiscv  where SDVIDU  = $id and SDVURUTAN >  $r  "; 
            $query = $this->db->query($sql); 
            
        
       
       
       // edn save voucher



        // USERLOG
        $uactivity = _anomor(element('PJ_Penjualan_Tunai',NID));
        $uactivity = $uactivity['keterangan'];
        $nomorAsli = $this->input->post('nomor');
        if (empty($nomorAsli)) {
            $rowNomor = $this->db->select('sunotransaksi')->where('suid', $id)->get('fstoku')->row();
            $nomorAsli = $rowNomor ? $rowNomor->sunotransaksi : '';
        }
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$nomorAsli,
            'ullevel'=> 2
        );
        $this->db->insert('aauserlog',$userlog);

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            $callback = array(    
                'pesan'=>'rollback',
                'nomor'=>$id
            );
            return json_encode($callback);            
        } else {
            
           
        
            $callback = array(    
                'pesan'=>'sukses',
                'nomor'=>$id
           );
            return json_encode($callback);            
        }

    }
    
     function kirim_no_ip($nomornya) {
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
        
       //$result = json_decode($response, true);  
       
        return $response ; 
    }


    function tambahTransaksi()
    {
        $surgerydp = 0 ; 
        $nomor = $this->autonumber($_POST['tgl']); 
        
        if($_POST['surgerydptotal']!='0'){
            $surgerydp = 1 ;
        }   
        
        if($_POST['piutangjumlah']!='0'){
            $surgerydp = 1 ;
        }  
        
        $debitbank = $_POST['debitbank'] ;  if( empty($_POST['debitbank']) ||$_POST['debitbank']== 'null' ) $debitbank='' ;
        $kreditbank = $_POST['kreditbank'] ;  if( empty($_POST['kreditbank']) ||$_POST['kreditbank']== 'null' ) $kreditbank='' ;
        $transferbank = $_POST['transferbank'] ;  if( empty($_POST['transferbank']) ||$_POST['transferbank']== 'null' ) $transferbank='' ;
        $merchantjenis = $_POST['merchantjenis'] ;  if( empty($_POST['merchantjenis']) ||$_POST['merchantjenis']== 'null' ) $merchantjenis='' ;
         
        
        
        
        
      
        
         $id_lama = $this->input->post('id');  

        // Insert Header Trans
        $gudang = $_POST['cabang'] ;
        $data_header = array(
                        'sunotransaksi' => $nomor,
                        'susumber' =>'IP',
                        'sutanggal' => tgl_database($_POST['tgl']),
                        'sukontak' =>  $_POST['kontak'],
                        'sukaryawan' => $_POST['karyawan'],
                         
                        'suuraian' => 'Penjualan - POS',
                        'sucatatan' => $_POST['catatan'],
                        'sutotaltransaksi' => $_POST['tsubtotal'],
                        'sutotalbayar' => $_POST['totalbayar'],
                        'sutotalsisa' => $_POST['totalsisa'], 
                        
                        'sutotalkas' => $_POST['kasjumlah'],
                        
                        'sutotalkartudebit' => $_POST['debitjumlah'],'sunokartudebit' => $_POST['debitno'],'sunamadebit' => $_POST['debitnama'],
                        'subankdebit' => $debitbank,'sudebitjenis' => $_POST['debitjenis'],'suattention' => $_POST['debitbanklain'],
                        
                        'sutotalkartukredit' => $_POST['kreditjumlah'],'sunokartukredit' => $_POST['kreditno'],'sunamakredit' => $_POST['kreditnama'],
                        'subankkredit' => $kreditbank,'sukreditjenis' => $_POST['kreditjenis'],'sunofakturpajak' => $_POST['kreditbanklain'], 
                        
                        'sutotaltransfer' => $_POST['transferjumlah'],'sunotransfer' => $_POST['transferno'],'sunamatransfer' => $_POST['transfernama'],'subanktransfer' => $transferbank ,
                        'sutotalvoucher' => $_POST['voucherjumlah'],'sunovoucher' => $_POST['voucherno'],'sustatuskirim' => $_POST['voucherid'],
                        
                        'sutotaldp' => $_POST['dpjumlah'], 'sudp1' => $_POST['dpjumlah'], 'sudpid' => $_POST['dpid'], 'sujenisdp' => $_POST['dpjenis'], 
                        'sumerchantjenis' => $merchantjenis,'sumerchantno' => $_POST['merchantno'],'sumerchantjumlah' => $_POST['merchantjumlah'], 
                        
                        'sucabang' => $_POST['cabang'],  
                        'surekammedis' => $_POST['rekammedis'],    
                        'sutotaltada ' => $_POST['totaltanpadp'],
                        
                        'surekhutang' => $_POST['training'],  'sufarmasi' => $_POST['farmasi'], 'sufarmasiasisten' => $_POST['farmasiasisten'],  'susalesmarketing' => $_POST['salesmarketing'],
                        'sukliniklain' => $_POST['kliniklain'], 
                        'sudkkwalkin' => $_POST['dkkwalkin'],
                        
                        'sunilaipiutang' => $_POST['piutangjumlah'],
                        
                        'susurgerydpidu' => $_POST['surgerydpidu'],
                        'susurgerydptotal' => $_POST['surgerydptotal'],
                        'susurgerydppembayaran' => $_POST['surgerydppembayaran'],
                        'susurgerydppiutang' => $_POST['surgerydppiutang'],
                        'susurgerydp' => $surgerydp,
                        
                        'sukodetele' => $_POST['kodetele'], 
                        'sureviewnilai' => $_POST['reviewnilai'],
                        'sureviewcatatan' => $_POST['reviewcatatan'],
                        
                        'suidmedlib' => $_POST['idmedlib'], 
                        'sulmcid' => $_POST['lmcidpro'],  
                        
                        'suteman' => $_POST['teman'], 
                        'SUNOIPLAMA'  => $_POST['nomorlama'],
                                            
                        'sucreateu' => $this->session->id            
        );  
        
        
        
        $this->db->trans_start();
        $this->db->insert('fstoku',$data_header);
        $id = $this->db->insert_id();
        
        
      
            if ($id_lama=='')
            {
               //masukan point
                if ( $_POST['totaltanpadp']> 100000 && ($_POST['kontaktipe']==12 || $_POST['kontaktipe'] ==14 || $_POST['kontaktipe']==19) )
                { 
                    $nilai=$_POST['totaltanpadp'];
                    $sisahasil=$nilai % 10000;  
                    $point=($nilai-$sisahasil)/10000;
                     
                    
                    $data_header = array(
                                'PIDPASIEN' =>$_POST['kontak'],
                                'PNILAIMASUK' => $_POST['totaltanpadp'],
                                'PMASUK' => $point,
                                'PIDTRANSAKSIMASUK' => $id            
                    );    
                 
                $this->db->insert('bpoint',$data_header); 
                
                 $data_header = array(
                                'sustatustada' => 1     
                    );  
                 $this->db->where('suid', $id);
                 $this->db->update('fstoku',$data_header);  
                 
                 
                 
                }
            }
            
          
        // Insert Detil Trans
        $r=1;

        $d = json_decode($_POST['detil']);

        if (!$this->_cekSemuaHargaUnlockPOS($d)) {
            $this->db->trans_rollback();
            return json_encode(array('pesan'=>'butuh_persetujuan_harga'));
        }

        if ($gudang == 18) {
            foreach ($d as $item) {
                if (empty($item->diskonunlocked) || $item->diskonunlocked != 1) {
                    $item->dis1 = 0;
                    $item->dis2 = 0;
                    $item->diskon = 0;
                    $item->diskon2 = 0;
                }
            }
        }
        foreach($d as $item){
            $dokter=$item->dokter ;
            if($dokter=='')  $dokter= NULL ; 
            $operator=$item->operator ;
            if($operator=='')  $operator= NULL ;  
            
              $cetak=$item->cetak;
            if($cetak=='') $cetak=1;
            
            $data_detil = array(
                    'sdidsu' => $id,
                    'sdurutan' => $r,
                    'sdsumber' => 'IP',
                    'sditem' => $item->item, 
                    'sdkeluar' => $item->qty, 
                    'sdkeluard' => $item->qty, 
                    'sdharga' => $item->harga, 
                    'sddiskonpersen' => $item->dis1,   
                    'sddiskonpersen2' => $item->dis2,   
                    'sddiskon' => $item->diskon , 
                    'sdsatuan' => $item->satuan , 
                    'sdsatuand' => $item->satuan, 
                    'sdgudang' => $gudang,  
                    'sddokter' => $dokter,
                    'sdkaryawan' =>  $operator,
                    'sdnoref' => $item->noref, 
                    'sdlantai2' => $item->noic, 
                    'sdcatatankoli' => $item->nopaketdetil, 
                    'sdkedatangan' => $item->kedatanganke, 
                    'sdidpotongstok' => $item->paket,
                    'sdsodurutan' => $item->idpaketdetil, 
                    'sddaripaket' => $item->daripaket, 
                    'sdidpromo' => $item->promo, 
                    'sdreferal' => $item->referal, 
                    'sdpro' => $item->aos, 
                    'sdprorecom' => $item->recom,
                    'SDMEDIDU' => $item->medidu,
                    'SDMEDIDD' => $item->medidd,
                    'SDPROIDU' => $item->proidu,
                    'SDPROIDD' => $item->proidd ,
                    'sddiskon2' => $item->diskon2,
                    'sdcetak' => $cetak ,
                    'SDVOUCERID' => $item->idvoucherwebdetil ,
                    'SDPOINTKELUAR' => $item->pointvoucherwebdetil ,
                    'SDSODID' => $item->medidd_sudahbayar   ,
                    'SDPRDID' => $item->medidu_sudahbayar  
                    
                    
                    
            );
            $this->db->insert('fstokd',$data_detil);   
            $idd = $this->db->insert_id();
            $r++;
              
             if ($id_lama=='')
            {
                if ( $item->idvoucherwebdetil !== '')
                {  
                 $data_vocer = array(
                            'PIDTRANSAKSIKELUAR' =>$idd 
                            ); 
                            $this->db->where('PVOUCHERPOINTID', $item->idvoucherwebdetil );
                            $this->db->update('bpoint',$data_vocer);  
                } 
            }
            
            
        }
   
          
        if ($id_lama!='')
            // jika cancel transksi
            {
               
                 
                   $sql = "delete from bpoint where PIDTRANSAKSIMASUK  = $id_lama  "; 
                   $query = $this->db->query($sql); 
                  
                   $sql = "update fstokd set sdcancel = 1 where sdidsu  = $id_lama  "; 
                   $query = $this->db->query($sql);  
                  
                   $sql = "update fstoku set  sudp1=0, sutotaldp=0, sustatus = 9 , SUIPBARU = $id  where suid = $id_lama  "; 
                   $query = $this->db->query($sql);   
                  
                   $sql = "delete from fstokd   where sdidsu in (select suid from fstoku where susumber = 'AL' and SUIDUALKES = $id_lama )  "; 
                   $query = $this->db->query($sql);    
                  
                   $sql = "delete from fstoku   where susumber = 'AL' and SUIDUALKES = $id_lama  "; 
                   $query = $this->db->query($sql); 
                   
 

            }
        
        $x = $this->updatepoint($_POST['kontak']);

// idpaket tidak dipakai lagi
//"SDIDTINDAKAN1" = idpaket , 
//"SDPENDING" = pending trans, "" , "SDPENGULANGAN", "", "SDHARGA0",
//"SDUSERRUBAHDISKON", "SDPRIORITAS", "SDNOTADA", "SDTINDAKAN", "SDQTPAKAI=jumlahshoot",  
//", "SDQTYIKUTKEPALA", "SDBARISKEPALA", "", "SDURUTANAWAL", "", 
//, "SDKEPALAALKES"
        
         //kirim email
         
         
         
       // save voucher
      
        
        $r=1;
        $d = json_decode($_POST['detil_v']);
        foreach($d as $item){
            
            $data_detil_bkg = array( 
                    'SDVURUTAN' => $r,
                    'SDVIDVOUCHER' => $item->vid,
                    'SDVIDU' => $id,
                    'SDVNILAI' => $item->vnilai, 
                    'SDVURUTANITEM' => $item->vbaris, 
                    'SDVITEM1' => $item->vitem, 
                    'SPVD1BON' => $item->vuntuk1bon,   
                    'SDVITEM2' => $item->vitem2,   
                    'SDVRUPIAH' => $item->vpersentase , 
                    'SDVNILAI2' => $item->vnilai2 , 
                    'SDVFREEITEM' => $item->vfreeitem 
            ); 
            
            $this->db->insert('fstokdiscv',$data_detil_bkg);   
            $r++;
        }  
        
       
       // edn save voucher
       
        
        //USERLOG
          
        
       $uactivity = _anomor(element('PJ_Penjualan_Tunai',NID));
       $uactivity = $uactivity['keterangan'];        
       $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$nomor,
            'ullevel'=> 1                                                                                    
        );
        $this->db->insert('aauserlog',$userlog);   
 

        $this->db->trans_complete(); 
        

        if($this->db->trans_status() === FALSE){
            $callback = array(    
                'pesan'=>'rollback',
                'nomor'=>''
            );
            return json_encode($callback);            
        } else { 
        
            $callback = array(    
                'pesan'=>'sukses',
                'nomor'=>$id
            );
            return json_encode($callback);            
        }
    }

    function hapusTransaksi(){

        $id = $_POST['id']; 
        
        $this->db->trans_start(); 
        

        //hapus Detil Transaksi Penjualan
        $this->db->where('sdidsu', $id);
        $this->db->delete('fstokd'); 
        
        //hapus Header Transaksi Penjualan
        $this->db->where('suid', $id);
        $this->db->delete('fstoku'); 
        
         $sql = "delete from bpoint where PIDTRANSAKSIMASUK  = $id  "; 
                   $query = $this->db->query($sql); 
                  

        // USERLOG
        $uactivity = _anomor(element('PJ_Penjualan_Tunai',NID));
        $uactivity = $uactivity['keterangan'];        
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '. $this->input->post('nomor') ,
            'ullevel'=> 0                                                                                    
        );
        $this->db->insert('aauserlog',$userlog);                       

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }

    }

    function kirimulangpoint(){

        $id = $_POST['id']; 
        $idteman = $_POST['idteman'];  
        $this->db->trans_start(); 
        

                    $nilai=10000000;
                    $sisahasil=$nilai % 10000;  
                    $point=($nilai-$sisahasil)/10000;
                     
                    
                    $data_header = array(
                                'PIDPASIEN' =>$idteman,
                                'PNILAIMASUK' => $nilai,
                                'PMASUK' => $point,
                                'PIDTRANSAKSIMASUK' => $id            
                    );    
                 
                $this->db->insert('bpoint',$data_header);     
                
                $x = $this->updatepoint($idteman);

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }

    }
    
   


    function autonumber($tgl){
        //$cabang  = @$_SESSION['cabang'] ;
        $cabang  = @$_SESSION['cabang'] ;
        $kodecabang  = @$_SESSION['kodecabang'] ;
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
        return $nomor;
    }            

    

    function updatepoint($idkontak){ 

        $sql = "SELECT sum(pmasuk-pkeluar) as point from bpoint where pidpasien = '".$idkontak."' " ; 
        $query = $this->db->query($sql);
        foreach ($query->result() as $res) { 
            $point=$res->point ;
        } 
        
                $data_header = array(
                                'kpoint' => $point
                    );  
                 $this->db->where('kid', $idkontak);
                 $this->db->update('bkontak',$data_header);   
        return $idkontak;
    } 
    
    
    function updatekupon(){
        
         $id = $this->input->post('id');  
         $jumlahkupon = $this->input->post('jumlahkupon');   
        
        $this->db->trans_start(); 
         
         
        
         
        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){ 
            
            $data_detil_bkg = array( 
                    'SVKURUTAN' => $r,
                    'SVKIDU' => $id, 
                    'SVKIDVOUCHER' => $item->item, 
                    'SVKJUMLAHVOUCHER' => $jumlahkupon, 
                    'SVKJENIS' => 0 
            );
           
            
            $sql = "SELECT * FROM fstokkuponv  where SVKIDU  = $id and SVKURUTAN =  $r  "; 
            $query = $this->db->query($sql); 
            $row_cnt = $query->num_rows();
            
            if($row_cnt==0){
                  $this->db->insert('fstokkuponv',$data_detil_bkg);            
            } else {
               $this->db->where('SVKIDU', $id); 
                $this->db->where('SVKURUTAN', $r);
                $this->db->update('fstokkuponv',$data_detil_bkg); 
            }  
            
             

            $r++;
        }  
        
                 
                
                $data_header = array(
                                'sujumlahkupon' => $jumlahkupon
                    );  
                 $this->db->where('suid', $id);
                 $this->db->update('fstoku',$data_header);   
        
        
        
        $this->db->trans_complete(); 

        if($this->db->trans_status() === FALSE){
            $callback = array(    
                'pesan'=>'rollback',
                'nomor'=>$id
            );
            return json_encode($callback);            
        } else {
            
           
        
            $callback = array(    
                'pesan'=>'sukses',
                'nomor'=>$id
           );
            return json_encode($callback);            
        } 
        
        
    }
    
    
    
    function updatekupon2(){
        $id = $this->input->post('id');  
        $jumlahkupon = $this->input->post('jumlahkupon');  
        
        $this->db->trans_start(); 
        
        
        $gudang = $_POST['cabang'] ;
        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){ 
            
            $data_detil_bkg = array( 
                    'SVKURUTAN' => $r,
                    'SVKIDU' => $id, 
                    'SVKIDVOUCHER' => $item->item, 
                    'SVKJUMLAHVOUCHER' => $jumlahkupon, 
                    'SVKJENIS' => 0 
            );
           
            
            $sql = "SELECT * FROM fstokkuponv  where SVKIDU  = $id and SVKURUTAN =  $r  "; 
            $query = $this->db->query($sql); 
            $row_cnt = $query->num_rows();
            
            if($row_cnt==0){
                  $this->db->insert('fstokkuponv',$data_detil_bkg);            
            } else {
               $this->db->where('SVKIDU', $id); 
                $this->db->where('SVKURUTAN', $r);
                $this->db->update('fstokkuponv',$data_detil_bkg); 
            }  

            $r++;
        }  
       
        // Insert Detil Trans
       
          $r--;
          $sql = "delete from fstokkuponv  where SVKIDU  = $id and SVKURUTAN >  $r  "; 
          $query = $this->db->query($sql); 
            
        
        
        
         
        // USERLOG
        $uactivity = _anomor(element('PJ_Penjualan_Tunai',NID));
        $uactivity = $uactivity['keterangan'];        
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$this->input->post('nomor'),
            'ullevel'=> 2                                                                                    
        );
        $this->db->insert('aauserlog',$userlog);  

        $this->db->trans_complete(); 

        if($this->db->trans_status() === FALSE){
            $callback = array(    
                'pesan'=>'rollback',
                'nomor'=>$id
            );
            return json_encode($callback);            
        } else {
            
           
        
            $callback = array(    
                'pesan'=>'sukses',
                'nomor'=>$id
           );
            return json_encode($callback);            
        }

    }
    
   

      

}