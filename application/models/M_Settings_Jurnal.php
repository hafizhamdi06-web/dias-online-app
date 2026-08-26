<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Settings_Jurnal extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $_POST['id'];
        $data = array(
                'cckode' => $_POST['kode'],
                'ccketerangan' => $_POST['ket'],
                'cccoa' => $_POST['coa'],
                'c' => '89'
        );        
        $this->db->trans_begin();
        $this->db->where('ccid',$id);        
        $this->db->update('cconfigcoa',$data);

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
        $this->db->where('ccid', $id);
        $this->db->delete('cconfigcoa');

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
                'cckode' => $_POST['kode'],
                'ccketerangan' => $_POST['keterangan'],
                'cccoa' => $_POST['coa'],
                'c' => '89'                
        );        
        $this->db->trans_begin();
        $this->db->insert('cconfigcoa',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    
}