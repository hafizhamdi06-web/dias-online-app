<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Master_Uang extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $_POST['id'];
        $data = array(
                'ukode' => $_POST['kode'],
                'unama' => $_POST['nama'],
                'usimbol' => $_POST['simbol'],                
                'umodifu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->where('uid',$id);        
        $this->db->update('buang',$data);

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

        if($id==1) return "Mata uang sistem tidak bisa dihapus";

        $this->db->trans_begin();
        $this->db->where('uid', $id);
        $this->db->delete('buang');

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
                'ukode' => $_POST['kode'],
                'unama' => $_POST['nama'],
                'usimbol' => $_POST['simbol'],                
                'ucreateu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->insert('buang',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    
}