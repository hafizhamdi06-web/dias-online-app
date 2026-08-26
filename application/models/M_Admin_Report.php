<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class M_Admin_Report extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {

        $id = $this->input->post('id');

        $data = array(
                'arname' => $this->input->post('nama'),
                'arname2' => $this->input->post('nama2'),
                'arlink' => $this->input->post('link'),                
                'artitle' => $this->input->post('judul'), 
                'arpapersize' => $this->input->post('ukuran'),
                'arpaperorinted' => $this->input->post('orientasi'),                    
                'aractive' => $this->input->post('aktif'),
                'ardate1f' => $this->input->post('f1'),
                'ardate2f' => $this->input->post('f2'),
                'arkontakf' => $this->input->post('f3'),
                'arcoaf' => $this->input->post('f4'),          
                'arsourcef' => $this->input->post('f5'),     
                'aritemf' => $this->input->post('f6'),
                'arsaldof' => $this->input->post('f7'),
                'argudangf' => $this->input->post('f8'),                                
                'arnomorf' => $this->input->post('f9'),                                   
                'arptf' => $this->input->post('f10'),                                              
                'armarginleft' => $this->input->post('marginl'),
                'armargintop' => $this->input->post('margint')
        );

        $this->db->trans_begin();
        $this->db->where('arid',$id);        
        $this->db->update('aareport',$data);

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
        $id = $this->input->post('id');

        $this->db->trans_begin();
        $this->db->where('arid', $id);
        $this->db->delete('aareport');

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
                'arname' => $this->input->post('nama'),
                'arname2' => $this->input->post('nama2'),
                'arlink' => $this->input->post('link'),                
                'artitle' => $this->input->post('judul'), 
                'arpapersize' => $this->input->post('ukuran'),
                'arpaperorinted' => $this->input->post('orientasi'),                    
                'aractive' => $this->input->post('aktif'),
                'ardate1f' => $this->input->post('f1'),
                'ardate2f' => $this->input->post('f2'),
                'arkontakf' => $this->input->post('f3'),
                'arcoaf' => $this->input->post('f4'),          
                'arsourcef' => $this->input->post('f5'),     
                'aritemf' => $this->input->post('f6'),
                'arsaldof' => $this->input->post('f7'),                
                'argudangf' => $this->input->post('f8'),
                'arnomorf' => $this->input->post('f9'),     
                'arptf' => $this->input->post('f10'),                                                                 
                'armarginleft' => $this->input->post('marginl'),
                'armargintop' => $this->input->post('margint')
        );

        $this->db->trans_begin();
        $this->db->insert('aareport',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    
}