<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_PB_Pembayaran_Hutang extends CI_Model {

    function __construct()
    {
        parent::__construct();
//        $this->load->library('Custom_Date');        
    }

    function ubahTransaksi(){
        $id = $_POST['id'];

        $sql="CALL SP_JURNAL_PEMBAYARAN_HUTANG_DEL(".$id.")";
        $this->db->query($sql);

        //Update Header Trans
        $data_header = array(
                        'piusumber' => $this->M_transaksi->prefixtrans(element('PB_Pembayaran_Hutang',NID)),
                        'piunotransaksi' => $_POST['nomor'],
                        'piutanggal' => tgl_database($_POST['tgl']),
                        'piukontak' => $_POST['kontak'],
                        'piuuraian' => $_POST['uraian'],
                        'piucoakas' => $_POST['coacr'],                
                        'piujmlkas' => $_POST['totalkas'],
                        'piutotalretur' => $_POST['totalretur'],                        
                        'piudiskon' => $_POST['totaldiskon'], 
                        'piutotalpiutang' => $_POST['totalbayar'],
                        'piupph' => $_POST['pph'],
                        'piustatuspph' => $_POST['statussetor'],
                        'piunilaibuktipotong' => $_POST['totalpajak'],
                        'piubuktipph' => $_POST['nomorbupot'],                        
                        'piujmlkasv' => 0,
                        'piustatus' => $_POST['status'],
                        'piunogiro' => $_POST['nomorgiro'],  
                        'piutgltempo' => $_POST['tglgiro'],
                        'piubank' => $_POST['bank'],
                        'piuuang' => $_POST['uangcr'],
                        'piukurs' => $_POST['kurscr'],
                        'piutipe' => $_POST['tipebayar'],                 
                        'piuselisihbayar' => $_POST['totalselisih'],                 
                        'piuakunselisih' => $_POST['coaselisih'],                                                                 
                        'piumodifu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->where('piuid', $id);
        $this->db->update('epembayaraninvoiceu',$data_header);

        //Delete Old Detil Trans Faktur
        $this->db->where('piiidu', $id);
        $this->db->delete('epembayaraninvoicei');

        //Delete Old Detil Trans Retur
        $this->db->where('piridu', $id);
        $this->db->delete('epembayaraninvoicer');

        // Insert Detil Trans Faktur
        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){
            //1=Faktur, 0=Uang Muka
            if($item->tipeinv==1){
                $data_detil = array(
                        'piiidu' => $id,
                        'piiurutan' => $r,
                        'piiidinvoice' => $item->idinvoice,
                        'piijmlbayar' => $item->jmlbayar,
                        'piijmlkasbank' => $item->jmlkasbank,
                        'piijmldiskon' => $item->jmldiskon,  
                        'piijmlretur' => $item->jmlretur,                                                                        
                        'piitipe' => $item->tipeinv                    
                );                
            }else{
                $data_detil = array(
                        'piiidu' => $id,
                        'piiurutan' => $r,
                        'piiiddp' => $item->idinvoice,
                        'piijmlbayar' => $item->jmlbayar,
                        'piijmlkasbank' => $item->jmlkasbank,
                        'piijmldiskon' => $item->jmldiskon,  
                        'piijmlretur' => $item->jmlretur,
                        'piitipe' => $item->tipeinv                    
                );                
            }            

            $this->db->insert('epembayaraninvoicei',$data_detil);                        
            $r++;
        }

        // Insert Detil Trans Retur
        $r=1;
        $d = json_decode($_POST['detilretur']);
        foreach($d as $item){
            $data_detil = array(
                    'piridu' => $id,
                    'pirurutan' => $r,
                    'piridreturpembelian' => $item->idretur,
                    'pirjmlbayar' => $item->jmlbayar
            );
            $this->db->insert('epembayaraninvoicer',$data_detil);                        
            $r++;
        }


        $sql="CALL SP_JURNAL_PEMBAYARAN_HUTANG_ADD(".$id.")";
        $this->db->query($sql);

        // USERLOG
        $uactivity = _anomor(element('PB_Pembayaran_Hutang',NID));
        $uactivity = $uactivity['keterangan'];        
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$this->input->post('nomor'),
            'ullevel'=> 2                                                                                    
        );
        $this->db->insert('auserlog',$userlog);                       

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $callback = array(    
                'pesan'=>'rollback',
                'nomor'=>$id
            );
            return json_encode($callback);            
        } else {
            $this->db->trans_commit();            
            $callback = array(    
                'pesan'=>'sukses',
                'nomor'=>$id
            );
            return json_encode($callback);            
        }

    }

    function tambahTransaksi()
    {

        if(empty($_POST['nomor'])){
            $nomor = $this->autonumber($_POST['tgl']);
        }else{
            $nomor = $_POST['nomor'];
        }        

        // Insert Header Trans
        $data_header = array(
                        'piusumber' => $this->M_transaksi->prefixtrans(element('PB_Pembayaran_Hutang',NID)),
                        'piunotransaksi' => $nomor,
                        'piutanggal' => tgl_database($_POST['tgl']),
                        'piukontak' => $_POST['kontak'],
                        'piuuraian' => $_POST['uraian'],
                        'piucoakas' => $_POST['coacr'],                
                        'piujmlkas' => $_POST['totalkas'],
                        'piutotalretur' => $_POST['totalretur'],                        
                        'piudiskon' => $_POST['totaldiskon'], 
                        'piutotalpiutang' => $_POST['totalbayar'],
                        'piupph' => $_POST['pph'],
                        'piustatuspph' => $_POST['statussetor'],
                        'piunilaibuktipotong' => $_POST['totalpajak'],
                        'piubuktipph' => $_POST['nomorbupot'],                        
                        'piujmlkasv' => 0,
                        'piustatus' => $_POST['status'],
                        'piunogiro' => $_POST['nomorgiro'],  
                        'piutgltempo' => $_POST['tglgiro'],
                        'piubank' => $_POST['bank'],
                        'piuuang' => $_POST['uangcr'],
                        'piukurs' => $_POST['kurscr'],
                        'piutipe' => $_POST['tipebayar'],                 
                        'piuselisihbayar' => $_POST['totalselisih'],                 
                        'piuakunselisih' => $_POST['coaselisih'],
                        'piucreateu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->insert('epembayaraninvoiceu',$data_header);
        $id = $this->db->insert_id();

        // Insert Detil Trans Faktur
        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){
            //1=Faktur, 0=Uang Muka
            if($item->tipeinv==1){
                $data_detil = array(
                        'piiidu' => $id,
                        'piiurutan' => $r,
                        'piiidinvoice' => $item->idinvoice,
                        'piijmlbayar' => $item->jmlbayar,
                        'piijmlkasbank' => $item->jmlkasbank,
                        'piijmldiskon' => $item->jmldiskon,  
                        'piijmlretur' => $item->jmlretur,                                                                        
                        'piitipe' => $item->tipeinv                    
                );                
            }else{
                $data_detil = array(
                        'piiidu' => $id,
                        'piiurutan' => $r,
                        'piiiddp' => $item->idinvoice,
                        'piijmlbayar' => $item->jmlbayar,
                        'piijmlkasbank' => $item->jmlkasbank,
                        'piijmldiskon' => $item->jmldiskon,  
                        'piijmlretur' => $item->jmlretur,
                        'piitipe' => $item->tipeinv                    
                );                
            }

            $this->db->insert('epembayaraninvoicei',$data_detil);                        
            $r++;
        }

        // Insert Detil Trans Retur
        $r=1;
        $d = json_decode($_POST['detilretur']);
        foreach($d as $item){
            $data_detil = array(
                    'piridu' => $id,
                    'pirurutan' => $r,
                    'piridreturpembelian' => $item->idretur,
                    'pirjmlbayar' => $item->jmlbayar
            );
            $this->db->insert('epembayaraninvoicer',$data_detil);                        
            $r++;
        }


        $sql="CALL SP_JURNAL_PEMBAYARAN_HUTANG_ADD(".$id.")";
        $this->db->query($sql);

        // USERLOG
        $uactivity = _anomor(element('PB_Pembayaran_Hutang',NID));
        $uactivity = $uactivity['keterangan'];        
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$nomor,
            'ullevel'=> 1                                                                                    
        );
        $this->db->insert('auserlog',$userlog);                       

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $callback = array(    
                'pesan'=>'rollback',
                'nomor'=>''
            );
            return json_encode($callback);            
        } else {
            $this->db->trans_commit();            
            $callback = array(    
                'pesan'=>'sukses',
                'nomor'=>$id
            );
            return json_encode($callback);            
        }
    }

    function hapusTransaksi(){

        $id = $_POST['id'];
        $nomor = $_POST['nomor'];

        $this->db->trans_begin();

        $sql="CALL SP_JURNAL_PEMBAYARAN_HUTANG_DEL(".$id.")";
        $this->db->query($sql);

        //hapus Header Transaksi
        $this->db->where('piuid', $id);
        $this->db->delete('epembayaraninvoiceu');

        //hapus Detil Transaksi Faktur
        $this->db->where('piiidu', $id);
        $this->db->delete('epembayaraninvoicei');

        //hapus Detil Transaksi Retur
        $this->db->where('piridu', $id);
        $this->db->delete('epembayaraninvoicer');

        // USERLOG
        $uactivity = _anomor(element('PB_Retur_Pembelian',NID));
        $uactivity = $uactivity['keterangan'];        
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$nomor,
            'ullevel'=> 0                                                                                    
        );
        $this->db->insert('auserlog',$userlog);                       

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }

    }

    function autonumber($tgl){
        $nomor = 0;
        $nomor1 = $this->M_transaksi->prefixtrans(element('PB_Pembayaran_Hutang',NID));
        $nomor2 = tgl_notrans($tgl);  

        $notrans_length = strlen($nomor1)+4;

        $sql = "SELECT MAX(RIGHT(piunotransaksi,4)) as 'maks' 
                  FROM epembayaraninvoiceu 
                 WHERE LEFT(piunotransaksi,".$notrans_length.")='".$nomor1.$nomor2."'";

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
        
        return $nomor;
    }

}