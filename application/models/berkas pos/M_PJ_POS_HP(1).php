<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_PJ_POS_HP extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahTransaksi(){
        $id = $this->input->post('id'); 
        
        $surgerydp = 0 ;  
        if($_POST['surgerydptotal']!='0'){
            $surgerydp = 1 ;
        }    
        if($_POST['piutangjumlah']!='0'){
            $surgerydp = 1 ;
        }  
        
        
        
        $this->db->trans_start(); 

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
                        'subankdebit' => $_POST['debitbank'],'sudebitjenis' => $_POST['debitjenis'],'suattention' => $_POST['debitbanklain'],
                        
                        'sutotalkartukredit' => $_POST['kreditjumlah'],'sunokartukredit' => $_POST['kreditno'],'sunamakredit' => $_POST['kreditnama'],
                        'subankkredit' => $_POST['kreditbank'],'sukreditjenis' => $_POST['kreditjenis'],'sunofakturpajak' => $_POST['kreditbanklain'], 
                        
                        'sutotaltransfer' => $_POST['transferjumlah'],'sunotransfer' => $_POST['transferno'],'sunamatransfer' => $_POST['transfernama'],'subanktransfer' => $_POST['transferbank'] ,
                        'sutotalvoucher' => $_POST['voucherjumlah'],'sunovoucher' => $_POST['voucherno'],'sustatuskirim' => $_POST['voucherid'],
                        
                        'sutotaldp' => $_POST['dpjumlah'], 'sudp1' => $_POST['dpjumlah'], 'sudpid' => $_POST['dpid'], 'sujenisdp' => $_POST['dpjenis'], 
                        'sumerchantjenis' => $_POST['merchantjenis'],'sumerchantno' => $_POST['merchantno'],'sumerchantjumlah' => $_POST['merchantjumlah'], 
                        
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
                        'sulmcid' => $_POST['medid'],  
                        'sumodifu' => $this->session->id               
        );        

        $this->db->where('suid', $id);
        $this->db->update('fstoku_tes',$data_header_bkg);   
        
        
        $gudang = $_POST['cabang'] ;
        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){
             
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
                    'sddokter' => $item->dokter, 
                    'sdkaryawan' => $item->operator, 
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
                    'sdcetak' => $item->cetak ,
                    'SDVOUCERID' => $item->idvoucherwebdetil ,
                    'SDPOINTKELUAR' => $item->pointvoucherwebdetil ,
                    'SDSODID' => $item->medidd_sudahbayar   ,
                    'SDPRDID' => $item->medidu_sudahbayar  
            );
           
            
            $sql = "SELECT * FROM fstokd_tes  where sdidsu  = $id and sdurutan =  $r  "; 
            $query = $this->db->query($sql); 
            $row_cnt = $query->num_rows();
            
            if($row_cnt==0){
                  $this->db->insert('fstokd_tes',$data_detil_bkg);            
            } else {
               $this->db->where('sdidsu', $id); 
                $this->db->where('sdurutan', $r);
                $this->db->update('fstokd_tes',$data_detil_bkg); 
            }  

            $r++;
        } 

         
       
        // Insert Detil Trans
       
          $r--;
          $sql = "delete from fstokd_tes  where sdidsu  = $id and sdurutan >  $r  "; 
            $query = $this->db->query($sql); 
            
         
        // USERLOG
     //   $uactivity = _anomor(element('PJ_POS_HP',NID));
     //   $uactivity = $uactivity['keterangan'];        
      //  $userlog = array(
      ////      'uluser' => $this->session->id,
      //      'ulusername' => $this->session->nama,
      //      'ulcomputer' => $this->input->ip_address(),
      //      'ulactivity' => $uactivity.' '.$this->input->post('nomor'),
     //       'ullevel'=> 2                                                                                    
      //  );
      //  $this->db->insert('auserlog',$userlog);                       

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

    function tambahTransaksi()
    {
        $surgerydp = 0 ;
       //if(empty($_POST['nomor'])){
        //    $nomor = $this->autonumber($_POST['tgl']);
       // //}elseif($_POST['appcanceltransaksi']==1){
            $nomor = $this->autonumber($_POST['tgl']);
       // }else{
       //     $nomor = $_POST['nomor'];
        //}   
        
        if($_POST['surgerydptotal']!='0'){
            $surgerydp = 1 ;
        }   
        
        if($_POST['piutangjumlah']!='0'){
            $surgerydp = 1 ;
        }  
        
        
         //$id_lama = $this->input->post('id'); 

        // Insert Header Trans
        $gudang = $_POST['cabang'] ;
        $data_header = array(
                        'sunotransaksi' => $nomor,
                        'susumber' =>'IP',
                        'sutanggal' => tgl_database($_POST['tgl']),
                        'sukontak' => $_POST['kontak'],
                        'sukaryawan' => $_POST['karyawan'],
                         
                        'suuraian' => 'Penjualan - POS',
                        'sucatatan' => $_POST['catatan'],
                        'sutotaltransaksi' => $_POST['tsubtotal'],
                        'sutotalbayar' => $_POST['totalbayar'],
                        'sutotalsisa' => $_POST['totalsisa'], 
                        
                        'sutotalkas' => $_POST['kasjumlah'],
                        
                        'sutotalkartudebit' => $_POST['debitjumlah'],'sunokartudebit' => $_POST['debitno'],'sunamadebit' => $_POST['debitnama'],
                        'subankdebit' => $_POST['debitbank'],'sudebitjenis' => $_POST['debitjenis'],'suattention' => $_POST['debitbanklain'],
                        
                        'sutotalkartukredit' => $_POST['kreditjumlah'],'sunokartukredit' => $_POST['kreditno'],'sunamakredit' => $_POST['kreditnama'],
                        'subankkredit' => $_POST['kreditbank'],'sukreditjenis' => $_POST['kreditjenis'],'sunofakturpajak' => $_POST['kreditbanklain'], 
                        
                        'sutotaltransfer' => $_POST['transferjumlah'],'sunotransfer' => $_POST['transferno'],'sunamatransfer' => $_POST['transfernama'],'subanktransfer' => $_POST['transferbank'] ,
                        'sutotalvoucher' => $_POST['voucherjumlah'],'sunovoucher' => $_POST['voucherno'],'sustatuskirim' => $_POST['voucherid'],
                        
                        'sutotaldp' => $_POST['dpjumlah'], 'sudp1' => $_POST['dpjumlah'], 'sudpid' => $_POST['dpid'], 'sujenisdp' => $_POST['dpjenis'], 
                        'sumerchantjenis' => $_POST['merchantjenis'],'sumerchantno' => $_POST['merchantno'],'sumerchantjumlah' => $_POST['merchantjumlah'], 
                        
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
                        'sulmcid' => $_POST['medid'],  
                        
                        'suteman' => $_POST['teman'], 
                        
                                            
                        'sucreateu' => $this->session->id                
        );  
        
        // 'SUNOIPLAMA'  => $_POST['nomor'],  
        
        $this->db->trans_start();
        $this->db->insert('fstoku_tes',$data_header);
        $id = $this->db->insert_id();
        
            //if ($id=='')  
            //{
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
                 $this->db->update('fstoku_tes',$data_header);  
                 
                 
                 $x = $this->updatepoint($_POST['kontak']);
                }
            //}
            //else
            //{
            //    $data_update = array(
            //            'sustatus' => 9,
            //            'SUIPBARU' => $id,  
            //            );
            //    $this->db->where('suid', $id_lama);
            //    $this->db->update('fstoku_tes',$data_update);   
            //}

          
        // Insert Detil Trans
        $r=1;
        
        $d = json_decode($_POST['detil']);
        foreach($d as $item){
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
                    'sddokter' => $item->dokter, 
                    'sdkaryawan' => $item->operator, 
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
                    'sdcetak' => $item->cetak ,
                    'SDVOUCERID' => $item->idvoucherwebdetil ,
                    'SDPOINTKELUAR' => $item->pointvoucherwebdetil ,
                    'SDSODID' => $item->medidd_sudahbayar   ,
                    'SDPRDID' => $item->medidu_sudahbayar  
                    
                    
                    
            );
            $this->db->insert('fstokd_tes',$data_detil);   
            $idd = $this->db->insert_id();
            $r++;
                
            //if ($id=='')  
            //{
                if ( $item->idvoucherwebdetil !== '')
                {  
                 $data_vocer = array(
                            'PIDTRANSAKSIKELUAR' =>$idd 
                            ); 
                            $this->db->where('PVOUCHERPOINTID', $item->idvoucherwebdetil );
                            $this->db->update('bpoint',$data_vocer);  
                } 
           //}
        }

// idpaket tidak dipakai lagi
//"SDIDTINDAKAN1" = idpaket , 
//"SDPENDING" = pending trans, "" , "SDPENGULANGAN", "", "SDHARGA0",
//"SDUSERRUBAHDISKON", "SDPRIORITAS", "SDNOTADA", "SDTINDAKAN", "SDQTPAKAI=jumlahshoot",  
//", "SDQTYIKUTKEPALA", "SDBARISKEPALA", "", "SDURUTANAWAL", "", 
//, "SDKEPALAALKES"
        
        // USERLOG
       //$uactivity = _anomor(element('PJ_POS_HP',NID));
       // $uactivity = $uactivity['keterangan'];        
       // $userlog = array(
       //     'uluser' => $this->session->id,
       //     'ulusername' => $this->session->nama,
        //    'ulcomputer' => $this->input->ip_address(),
        //    'ulactivity' => $uactivity.' '.$nomor,
        //    'ullevel'=> 1                                                                                    
        //);
       // $this->db->insert('auserlog',$userlog);                       

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
        $this->db->delete('fstokd_tes'); 
        
        //hapus Header Transaksi Penjualan
        $this->db->where('suid', $id);
        $this->db->delete('fstoku_tes'); 

        // USERLOG
        //$uactivity = _anomor(element('PJ_POS_HP',NID));
       // $uactivity = $uactivity['keterangan'];        
        //$userlog = array(
       //     'uluser' => $this->session->id,
        //    'ulusername' => $this->session->nama,
       //     'ulcomputer' => $this->input->ip_address(),
       /////     'ulactivity' => $uactivity.' '.$nomor,
        //    'ullevel'=> 0                                                                                    
       // );
       // $this->db->insert('auserlog',$userlog);                       

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
        $cabang  = @$_SESSION['cabang'] ;
        $cabang  = @$_SESSION['cabang'] ;
        $nomor = 0;
        $nomor1 = $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai',NID));
        $nomor2 = tgl_notrans($tgl);  

        $notrans_length = strlen($nomor1)+4;

       $sql = "SELECT MAX(RIGHT(sunotransaksi,4)) as 'maks' 
                  FROM fstoku_tes 
                 WHERE LEFT(sunotransaksi,".$notrans_length.")='".$nomor1.$nomor2."' and sucabang='".$cabang."'  ";

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

      

}