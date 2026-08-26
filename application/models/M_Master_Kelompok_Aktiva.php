<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Master_Kelompok_Aktiva extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $_POST['id'];
        $data = array(
                'akkode' => $_POST['kode'],
                'aknama' => $_POST['nama'],
                'akumur' => $_POST['umur'],                
                'akcoaaktiva' => $_POST['coaaktiva'],                
                'akcoadepresiasi' => $_POST['coadepresiasi'],
                'akcoadepresiasiakum' => $_POST['coadepresiasiakum'],
                'akcoawriteoff' => $_POST['coawriteoff'],                                                                
                'akmodifu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->where('akid',$id);        
        $this->db->update('baktivakelompok',$data);

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
        $this->db->where('akid', $id);
        $this->db->delete('baktivakelompok');

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
                'akkode' => $_POST['kode'],
                'aknama' => $_POST['nama'],
                'akumur' => $_POST['umur'],                
                'akcoaaktiva' => $_POST['coaaktiva'],                
                'akcoadepresiasi' => $_POST['coadepresiasi'],
                'akcoadepresiasiakum' => $_POST['coadepresiasiakum'],
                'akcoawriteoff' => $_POST['coawriteoff'],                                                                
                'akcreateu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->insert('baktivakelompok',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    
}