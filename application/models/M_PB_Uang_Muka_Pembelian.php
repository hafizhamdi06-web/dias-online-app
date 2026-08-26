<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_PB_Uang_Muka_Pembelian extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahTransaksi(){
        $id = $_POST['id'];

        if($_POST['tipe']==1) {
            $bayar = $_POST['jumlah'];
        } else {
            $bayar = 0;
        }

        //Update Header Trans
        $data_header = array( 
                    'dptanggal' => tgl_database($_POST['tgl']),                        
                    'dpnotransaksi' => $_POST['nomor'],
                    'dpkontak' => $_POST['kontak'],
                    'dpjumlah' => $_POST['jumlah'],                           
                    'dpjumlahv' => 0,
                    'dpketerangan' => $_POST['uraian'],
                    'dpsumber' => $this->M_transaksi->prefixtrans(element('PB_Uang_Muka_Pembelian',NID)),
                    'dpcdp' => $_POST['coadp'],                                                        
                    'dpckas' => $_POST['coakas'],  
                    'dptermin' => $_POST['termin'],
                    'dptipe' => $_POST['tipe'],
                    'dpjumlahbayar' => $bayar,                                               
//                    'dppajak' => $_POST['pajakdp'],                                                                 
//                    'dppajakn' => $_POST['tdppajak'],                 
//                    'dpnofaktupajak' => $_POST['fakturpajakdp'],                                                        
                    'dpmodifu' => $this->session->id                           
        );        
        $this->db->trans_start();

        //Hapus Jurnal DP
        $sql = "CALL SP_JURNAL_UM_PEMBELIAN_DEL(".$id.",0)";
        $this->db->query($sql);

        $this->db->where('dpid', $id);
        $this->db->update('ddp',$data_header);

        // Create Jurnal DP
        $sql = "CALL SP_JURNAL_UM_PEMBELIAN_ADD(".$id.")";
        $this->db->query($sql);

        // USERLOG
        $uactivity = _anomor(element('PB_Uang_Muka_Pembelian',NID));
        $uactivity = $uactivity['keterangan'];        
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$this->input->post('nomor'),
            'ullevel'=> 2                                                                                    
        );
        $this->db->insert('auserlog',$userlog);                       

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
        if(empty($_POST['nomor'])){
            $nomor = $this->autonumber($_POST['tgl']);
        }else{
            $nomor = $_POST['nomor'];
        }        

        if($_POST['tipe']==1) {
            $bayar = $_POST['jumlah'];
        } else {
            $bayar = 0;
        }

        // Insert Header Trans
        $data_header = array(
                    'dptanggal' => tgl_database($_POST['tgl']),                        
                    'dpnotransaksi' => $nomor,
                    'dpkontak' => $_POST['kontak'],
                    'dpjumlah' => $_POST['jumlah'],                           
                    'dpjumlahv' => 0,
                    'dpketerangan' => $_POST['uraian'],
                    'dpsumber' => $this->M_transaksi->prefixtrans(element('PB_Uang_Muka_Pembelian',NID)),
                    'dpcdp' => $_POST['coadp'],                                                        
                    'dpckas' => $_POST['coakas'],  
                    'dptermin' => $_POST['termin'],
                    'dptipe' => $_POST['tipe'],
                    'dpjumlahbayar' => $bayar,                                               
//                    'dppajak' => $_POST['pajakdp'],                                                                 
//                    'dppajakn' => $_POST['tdppajak'],                 
//                    'dpnofaktupajak' => $_POST['fakturpajakdp'],                                                        
                    'dpcreateu' => $this->session->id               
        );        

        $this->db->trans_start();
        $this->db->insert('ddp',$data_header);
        $id = $this->db->insert_id();

        // Create Jurnal Uang Muka
        $sql = "CALL SP_JURNAL_UM_PEMBELIAN_ADD(".$id.")";
        $this->db->query($sql);

        // USERLOG
        $uactivity = _anomor(element('PB_Uang_Muka_Pembelian',NID));
        $uactivity = $uactivity['keterangan'];
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$nomor,
            'ullevel'=> 1                                    
        );
        $this->db->insert('auserlog',$userlog);                       

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

        $id = $this->input->post('id');
        $nomor = $this->input->post('nomor'); 

        $this->db->trans_start();

        //Hapus Jurnal DP
        $sql = "CALL SP_JURNAL_UM_PEMBELIAN_DEL(".$id.",1)";
        $this->db->query($sql);

        //hapus Header Transaksi
        $this->db->where('dpid', $id);
        $this->db->delete('ddp');

        // USERLOG
        $uactivity = _anomor(element('PB_Uang_Muka_Pembelian',NID));
        $uactivity = $uactivity['keterangan'];        
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$nomor,
            'ullevel'=> 0                                    
        );
        $this->db->insert('auserlog',$userlog);                       

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }

    }

    function autonumber($tgl){
        $nomor = 0;
        $nomor1 = $this->M_transaksi->prefixtrans(element('PB_Uang_Muka_Pembelian',NID));
        $nomor2 = tgl_notrans($tgl);  

        $notrans_length = strlen($nomor1)+4;

        $sql = "SELECT MAX(RIGHT(dpnotransaksi,4)) as 'maks' 
                  FROM ddp 
                 WHERE LEFT(dpnotransaksi,".$notrans_length.")='".$nomor1.$nomor2."'";
        
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