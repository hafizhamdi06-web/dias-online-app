<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Admin_Menu extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $this->input->post('id');
        $data = array(
                'mnama' => $this->input->post('nama'),
                'mtype' => $this->input->post('tipe'),
                'mdescription' => $this->input->post('keterangan'),     
                'mparent' => $this->input->post('induk'),                    
                'mlink' => $this->input->post('link'),  
                'mcaption1' => $this->input->post('caption1'),
                'micon' => $this->input->post('icon'),
                'murutan' => $this->input->post('urutan'),
                'mreport' => $this->input->post('report'),
                'mcatatan' => $this->input->post('catatan'),                  
                'mactive' => $this->input->post('aktif')                                                                                   
        );        
        $this->db->trans_start();
        $this->db->where('mid',$id);        
        $this->db->update('aamenu',$data);
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }
    }    

    function hapusData()
    {
        $id = $this->input->post('id');

        $this->db->trans_begin();
        $this->db->where('mid', $id);
        $this->db->delete('aamenu');

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
                'mnama' => $this->input->post('nama'),
                'mtype' => $this->input->post('tipe'),
                'mdescription' => $this->input->post('keterangan'),     
                'mparent' => $this->input->post('induk'),                    
                'mlink' => $this->input->post('link'),  
                'mcaption1' => $this->input->post('caption1'),
                'micon' => $this->input->post('icon'),
                'murutan' => $this->input->post('urutan'),
                'mreport' => $this->input->post('report'),  
                'mcatatan' => $this->input->post('catatan'),                 
                'mactive' => $this->input->post('aktif')                                                                                   
        );        
        $this->db->trans_start();
        $this->db->insert('aamenu',$data);
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }
    }    
}