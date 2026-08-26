<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Settings_Nomor extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $_POST['id'];
        $data = array(
                'NKODE' => $_POST['kode'],
                'NKETERANGAN' => $_POST['keterangan'],
                'NTABEL' => $_POST['tabel'],
                'NFLDTANGGAL' => $_POST['tanggal'],
                'NFLDSUMBER' => $_POST['sumber'],
                'NFLDNOTRANSAKSI' => $_POST['notrans'],
                'NFLDURAIAN' => $_POST['uraian'],                                
                'NFLDTOTALTRANS' => $_POST['totaltrans'],
                'NFLDKONTAK' => $_POST['kontak'],
                'NFLDID' => $_POST['idtrans'],
                'NFA' => $_POST['nfa'],
                'NFMENU' => $_POST['menu']
        );        
        $this->db->trans_begin();
        $this->db->where('NID',$id);        
        $this->db->update('aanomor',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    

    function hapusData()
    {
        $id = $_POST['id'];

        $this->db->trans_begin();
        $this->db->where('NID', $id);
        $this->db->delete('aanomor');

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }    	
    }

    function tambahData()
    {

        $data = array(
                'NKODE' => $_POST['kode'],
                'NKETERANGAN' => $_POST['keterangan'],
                'NTABEL' => $_POST['tabel'],
                'NFLDTANGGAL' => $_POST['tanggal'],
                'NFLDSUMBER' => $_POST['sumber'],
                'NFLDNOTRANSAKSI' => $_POST['notrans'],
                'NFLDURAIAN' => $_POST['uraian'],                                
                'NFLDTOTALTRANS' => $_POST['totaltrans'],
                'NFLDKONTAK' => $_POST['kontak'],
                'NFLDID' => $_POST['idtrans'],
                'NFA' => $_POST['nfa'],
                'NFMENU' => $_POST['menu']
        );        
        $this->db->trans_begin();
        $this->db->insert('aanomor',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    
}