<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Master_Pajak extends CI_Model {

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
                'pnilai' => $_POST['nilai'],                
                'ptipe' => $_POST['tipe'],
                'pcoain' => $_POST['coain'],
                'pcoaout' => $_POST['coaout'],                                                                                                
                'pmodifu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->where('pid',$id);        
        $this->db->update('bpajak',$data);

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
        $this->db->delete('bpajak');

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
                'pnilai' => $_POST['nilai'], 
                'ptipe' => $_POST['tipe'],
                'pcoain' => $_POST['coain'],
                'pcoaout' => $_POST['coaout'],                               
                'pcreateu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->insert('bpajak',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    
}