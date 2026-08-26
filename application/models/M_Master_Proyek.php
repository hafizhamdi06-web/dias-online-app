<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Master_Proyek extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $_POST['id'];
        $data = array(
                'pkode' => $_POST['kode'],
                'pnama' => $_POST['nama'],
                'ppelanggan' => $_POST['pelanggan'],
                'pfee' => $_POST['nilai'],                
                'pmodifu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->where('pid',$id);        
        $this->db->update('bproyek',$data);

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
        $this->db->where('pid', $id);
        $this->db->delete('bproyek');

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
                'pkode' => $_POST['kode'],
                'pnama' => $_POST['nama'],
                'ppelanggan' => $_POST['pelanggan'],
                'pfee' => $_POST['nilai'],                
                'pcreateu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->insert('bproyek',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    
}