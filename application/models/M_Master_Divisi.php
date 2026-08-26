<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Master_Divisi extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $_POST['id'];
        $data = array(
                'dkode' => $_POST['kode'],
                'dnama' => $_POST['nama'],
                'dmodifu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->where('did',$id);        
        $this->db->update('bdivisi',$data);

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
        $this->db->where('did', $id);
        $this->db->delete('bdivisi');

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
                'dkode' => $_POST['kode'],
                'dnama' => $_POST['nama'],
                'dcreateu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->insert('bdivisi',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    
}