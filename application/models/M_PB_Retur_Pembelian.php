<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_PB_Retur_Pembelian extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahTransaksi(){
        $id = $_POST['id'];
        $idbkg = $this->ambilidbkg($id);
        if(empty($idbkg)) {
            $idbkg = 0;
        }

        //Update Header Trans Retur Pembelian
        $data_header = array(
                        'ipusumber' => $this->M_transaksi->prefixtrans(element('PB_Retur_Pembelian',NID)),
                        'ipunotransaksi' => $_POST['nomor'],
                        'iputanggal' => tgl_database($_POST['tgl']),
                        'ipukontak' => $_POST['kontak'],
                        'ipuuraian' => $_POST['uraian'],
                        'ipuattention' => $_POST['person'],   
                        'ipujenispajak' => $_POST['pajak'],
                        'ipustatus' => $_POST['status'], 
                        'ipukaryawan' => $_POST['karyawan'], 
                        'iputotalpajak' => $_POST['totalpajak'],
                        'iputotalpph22' => $_POST['totalpph22'],                        
                        'iputotaltransaksi' => $_POST['totaltrans'],                        
                        'ipumodifu' => $this->session->id                
        );        
        $this->db->trans_start();

        $sql="CALL SP_JURNAL_RETUR_PEMBELIAN_DEL(".$id.")";
        $this->db->query($sql);

        $sql = "CALL SP_HITUNG_HPP_DEL(".$idbkg.")";
        $this->db->query($sql);

        $this->db->where('ipuid', $id);
        $this->db->update('einvoicepenjualanu',$data_header);

        // Insert Header Trans BKG
        $data_header_bkg = array(
                        'susumber' => $this->M_transaksi->prefixtrans(element('PB_Retur_Pembelian',NID)),
                        'sutanggal' => tgl_database($_POST['tgl']),                        
                        'sunotransaksi' => $_POST['nomor'],
                        'sukontak' => $_POST['kontak'],
                        'suattention' => $_POST['person'],                           
                        'suuraian' => $_POST['uraian'],                        
                        'sustatus' => $_POST['status'],
                        'sumodifu' => $this->session->id               
        );        
        $this->db->where('suid', $idbkg);
        $this->db->update('fstoku',$data_header_bkg);        

        //Delete Old Detil Trans
        $this->db->where('ipdidipu', $id);
        $this->db->delete('einvoicepenjualand');
        $this->db->where('sdidsu', $idbkg);
        $this->db->delete('fstokd');

        // Insert Detil Trans
        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){
            $data_detil = array(
                    'ipdidipu' => $id,
                    'ipdurutan' => $r,
                    'ipditem' => $item->item,
                    'ipdkeluar' => $item->qty,
                    'ipdkeluard' => $item->qty,                    
                    'ipdharga' => $item->harga,
                    'ipddiskon' => $item->diskon,
                    'ipdsatuan' => $item->satuan,
                    'ipdsatuand' => $item->satuan,
                    'ipddiskonp' => $item->persen,
                    'ipdgudang' => $item->gudang,
                    'ipdproyek' => $item->proyek,
                    'ipdcatatan' => $item->catatan
            );
            $this->db->insert('einvoicepenjualand',$data_detil);         

            $data_detil_bkg = array(
                    'sdidsu' => $idbkg,
                    'sdurutan' => $r,
                    'sdsumber' => $this->M_transaksi->prefixtrans(element('PB_Retur_Pembelian',NID)),                    
                    'sditem' => $item->item,
                    'sdkeluar' => $item->qty,
                    'sdkeluard' => $item->qty,                    
                    'sdsatuan' => $item->satuan,
                    'sdsatuand' => $item->satuan,
                    'sdcatatan' => $item->catatan,
                    'sdgudang' => $item->gudang,
                    'sdproyek' => $item->proyek
            );
            $this->db->insert('fstokd',$data_detil_bkg);

            $r++;
        }

        $sql = "CALL SP_HITUNG_HPP_ADD(".$idbkg.")";
        $this->db->query($sql);

        $sql="CALL SP_JURNAL_RETUR_PEMBELIAN_ADD(".$id.",".$idbkg.")";
        $this->db->query($sql);

        // USERLOG
        $uactivity = _anomor(element('PB_Retur_Pembelian',NID));
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

        // Insert Header Trans Retur
        $data_header = array(
                        'ipusumber' => $this->M_transaksi->prefixtrans(element('PB_Retur_Pembelian',NID)),
                        'ipunotransaksi' => $nomor,
                        'iputanggal' => tgl_database($_POST['tgl']),
                        'ipukontak' => $_POST['kontak'],
                        'ipuuraian' => $_POST['uraian'],
                        'ipuattention' => $_POST['person'],   
                        'ipujenispajak' => $_POST['pajak'],
                        'iputipepenjualan' => 1, 
                        'ipustatus' => 1, 
                        'ipukaryawan' => $_POST['karyawan'], 
                        'iputotalpajak' => $_POST['totalpajak'],
                        'iputotalpph22' => $_POST['totalpph22'], 
                        'iputotaltransaksi' => $_POST['totaltrans'],                        
                        'ipucreateu' => $this->session->id                
        );        
        $this->db->trans_start();
        $this->db->insert('einvoicepenjualanu',$data_header);
        $id = $this->db->insert_id();

        // Insert Header Trans BKG
        $data_header_bkg = array(
                        'susumber' => $this->M_transaksi->prefixtrans(element('PB_Retur_Pembelian',NID)),
                        'sutanggal' => tgl_database($_POST['tgl']),                        
                        'sunotransaksi' => $nomor,
                        'sukontak' => $_POST['kontak'],
                        'suattention' => $_POST['person'],                           
                        'suuraian' => $_POST['uraian'],                        
                        'sustatus' => 1,
                        'sucreateu' => $this->session->id               
        );        
        $this->db->insert('fstoku',$data_header_bkg);
        $idbkg = $this->db->insert_id();

        //Update idbkg di header retur
        $nobkgretur = array(
                    'ipunobkg' => $idbkg
        );
        $this->db->where('ipuid', $id);
        $this->db->update('einvoicepenjualanu',$nobkgretur);        

        // Insert Detil Trans
        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){
            $data_detil = array(
                    'ipdidipu' => $id,
                    'ipdurutan' => $r,
                    'ipditem' => $item->item,
                    'ipdkeluar' => $item->qty,
                    'ipdkeluard' => $item->qty,                    
                    'ipdharga' => $item->harga,
                    'ipddiskon' => $item->diskon,
                    'ipdsatuan' => $item->satuan,
                    'ipdsatuand' => $item->satuan,
                    'ipddiskonp' => $item->persen,
                    'ipdgudang' => $item->gudang,
                    'ipdproyek' => $item->proyek,                    
                    'ipdcatatan' => $item->catatan
            );
            $this->db->insert('einvoicepenjualand',$data_detil);  

            $data_detil_bkg = array(
                    'sdidsu' => $idbkg,
                    'sdurutan' => $r,
                    'sdsumber' => $this->M_transaksi->prefixtrans(element('PB_Retur_Pembelian',NID)),                    
                    'sditem' => $item->item,
                    'sdkeluar' => $item->qty,
                    'sdkeluard' => $item->qty,                    
                    'sdsatuan' => $item->satuan,
                    'sdsatuand' => $item->satuan,
                    'sdcatatan' => $item->catatan,
                    'sdgudang' => $item->gudang,
                    'sdproyek' => $item->proyek,                    
            );
            $this->db->insert('fstokd',$data_detil_bkg);

            $r++;
        }

        $sql = "CALL SP_HITUNG_HPP_ADD(".$idbkg.")";
        $this->db->query($sql);

        $sql="CALL SP_JURNAL_RETUR_PEMBELIAN_ADD(".$id.",".$idbkg.")";
        $this->db->query($sql);

        // USERLOG
        $uactivity = _anomor(element('PB_Retur_Pembelian',NID));
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

        $id = $_POST['id'];
        $nomor = $_POST['nomor'];

        $idbkg = $this->ambilidbkg($id);

        if(empty($idbkg)){
            $idbkg=0;
        }

        $this->db->trans_start();

        $sql = "CALL SP_HITUNG_HPP_DEL(".$idbkg.")";
        $this->db->query($sql);

        $sql="CALL SP_JURNAL_RETUR_PEMBELIAN_DEL(".$id.")";
        $this->db->query($sql);

        //hapus Header Transaksi Retur
        $this->db->where('ipuid', $id);
        $this->db->delete('einvoicepenjualanu');

        //hapus Header Transaksi BKG
        $this->db->where('suid', $idbkg);
        $this->db->delete('fstoku');

        //hapus Detil Transaksi Retur
        $this->db->where('ipdidipu', $id);
        $this->db->delete('einvoicepenjualand');

        //hapus Detil Transaksi BKG
        $this->db->where('sdidsu', $idbkg);
        $this->db->delete('fstokd');

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

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }

    }

    function autonumber($tgl){
        $nomor = 0;
        $nomor1 = $this->M_transaksi->prefixtrans(element('PB_Retur_Pembelian',NID));
        $nomor2 = tgl_notrans($tgl);  

        $notrans_length = strlen($nomor1)+4;

        $sql = "SELECT MAX(RIGHT(ipunotransaksi,4)) as 'maks' 
                  FROM einvoicepenjualanu 
                 WHERE LEFT(ipunotransaksi,".$notrans_length.")='".$nomor1.$nomor2."'";

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

    function ambilidbkg($id){
        $this->db->where('ipuid', $id);
        $hasil = $this->db->get('einvoicepenjualanu');

        foreach ($hasil->result() as $row) {
            $nomor = $row->IPUNOBKG;
            return $nomor;                    
        }                   
    }

}