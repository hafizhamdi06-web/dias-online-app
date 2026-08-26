<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Master_Bank extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $_POST['id'];
        $data = array(
                'bkode' => $_POST['kode'],
                'bnama' => $_POST['nama'],
                'bmodifu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->where('bid',$id);        
        $this->db->update('bbank',$data);

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
        $this->db->where('bid', $id);
        $this->db->delete('bbank');

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
                'bkode' => $_POST['kode'],
                'bnama' => $_POST['nama'],
                'bcreateu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->insert('bbank',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    
}