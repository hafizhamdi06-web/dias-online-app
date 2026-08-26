<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Master_Gudang extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $_POST['id'];
        $this->db->trans_begin();

        if($_POST['def']==1) {
            $data = array(
                    'gdefault' => 0
            );            
            $this->db->update('bgudang',$data);
        }

        $data = array(
                'gkode' => $_POST['kode'],
                'gnama' => $_POST['nama'],
                'gdivisi' => $_POST['divisi'],
                'gdefault' => $_POST['def'],
                'galamat1' => $_POST['alamat'],
                'gkota' => $_POST['kota'],
                'gpropinsi' => $_POST['provinsi'],
                'gnegara' => $_POST['negara'],
                'gtelp' => $_POST['telp'],
                'gfax' => $_POST['faks'],
                'gkontak' => $_POST['kontak'],                                               
                'gmodifu' => $this->session->id                
        );        
        $this->db->where('gid',$id);        
        $this->db->update('bgudang',$data);

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
        $this->db->where('gid', $id);
        $this->db->delete('bgudang');

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
        $this->db->trans_begin();
        if($_POST['def']==1) {
            $data = array(
                    'gdefault' => 0
            );            
            $this->db->update('bgudang',$data);
        }

        $data = array(
                'gkode' => $_POST['kode'],
                'gnama' => $_POST['nama'],
                'gdivisi' => $_POST['divisi'],
                'gdefault' => $_POST['def'],
                'galamat1' => $_POST['alamat'],
                'gkota' => $_POST['kota'],
                'gpropinsi' => $_POST['provinsi'],
                'gnegara' => $_POST['negara'],
                'gtelp' => $_POST['telp'],
                'gfax' => $_POST['faks'],
                'gkontak' => $_POST['kontak'],                                               
                'gcreateu' => $this->session->id                
        );        
        $this->db->insert('bgudang',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    
}