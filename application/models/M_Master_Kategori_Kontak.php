<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Master_Kategori_Kontak extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $_POST['id'];

        if ($id==2 || $id==4 || $id==6) return "sukses";

        $data = array(
                'ktnama' => $_POST['nama'],
                'ktmodifu' => $this->session->id                
        );        
        $this->db->trans_start();
        $this->db->where('ktid',$id);        
        $this->db->update('bkontaktipe',$data);
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }
    }    

    function hapusData()
    {
        $id = $_POST['id'];

        if ($id==2 || $id==4 || $id==6) return "Error : Kategori default sistem tidak bisa dihapus";

        $this->db->trans_start();
        $this->db->where('ktid', $id);
        $this->db->delete('bkontaktipe');
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }    	
    }

    function tambahData()
    {
        $data = array(
                'ktnama' => $_POST['nama'],
                'ktcreateu' => $this->session->id                
        );        
        $this->db->trans_start();
        $this->db->insert('bkontaktipe',$data);
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }
    }    
}