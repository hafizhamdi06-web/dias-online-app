<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Master_Satuan extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $_POST['id'];
        $data = array(
                'skode' => $_POST['kode'],
                'snama' => $_POST['nama'],
                'snilai' => $_POST['nilai'],
                'ssatuandasar' => $_POST['sdasar'],                                
                'smodifu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->where('sid',$id);        
        $this->db->update('bsatuan',$data);

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
        $this->db->where('sid', $id);
        $this->db->delete('bsatuan');

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
                'skode' => $_POST['kode'],
                'snama' => $_POST['nama'],
                'snilai' => $_POST['nilai'],
                'ssatuandasar' => $_POST['sdasar'],
                'screateu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->insert('bsatuan',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    
}