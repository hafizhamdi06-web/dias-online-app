<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Master_Jenis_Penyesuaian extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $_POST['id'];
        $data = array(
                'jkode' => $_POST['kode'],
                'jnama' => $_POST['nama'],
                'jakunbiaya' => $_POST['coa'],                
                'jmodifu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->where('jid',$id);        
        $this->db->update('bjenispenyesuaian',$data);

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
        $this->db->where('jid', $id);
        $this->db->delete('bjenispenyesuaian');

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
                'jkode' => $_POST['kode'],
                'jnama' => $_POST['nama'],
                'jakunbiaya' => $_POST['coa'],                
                'jcreateu' => $this->session->id                  
        );        
        $this->db->trans_begin();
        $this->db->insert('bjenispenyesuaian',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    
}