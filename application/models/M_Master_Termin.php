<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Master_Termin extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $_POST['id'];
        $data = array(
                'tkode' => $_POST['kode'],
                'tnama' => $_POST['nama'],
                'ttempo' => $_POST['tempo'],                
                'tmodifu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->where('tid',$id);        
        $this->db->update('btermin',$data);

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
        $this->db->where('tid', $id);
        $this->db->delete('btermin');

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
                'tkode' => $_POST['kode'],
                'tnama' => $_POST['nama'],
                'ttempo' => $_POST['tempo'],                
                'tcreateu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->insert('btermin',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    
}