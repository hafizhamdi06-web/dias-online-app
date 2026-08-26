<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Master_Aktiva extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $_POST['id'];
        $umur = ($_POST['utahun']*12)+$_POST['ubulan'];
        $data = array(
                'akode' => $_POST['kode'],
                'anama' => $_POST['nama'],
                'anomor' => $_POST['serial'],
                'adivisi' => $_POST['divisi'],  
                'akelompok' => $_POST['kelompok'],                                                
                'alokasi' => $_POST['lokasi'],
                'acatatan' => $_POST['lokasi'],                    
                'atglbeli' => tgl_database($_POST['tglbeli']),  
                'atglpakai' => tgl_database($_POST['tglpakai']),
                'ahargabeli' => $_POST['hargabeli'],     
                'abebanperbulan' => $_POST['bebanpenyusutan'],
                'anilairesidu' => $_POST['residu'],  
                'atipepenyusutan' => $_POST['metode'],                                                                      
                'ajmlaktiva' => $_POST['qty'],  
                'atgl15' => $_POST['tgl15'],                                                      
                'aaktivatidakberwujud' => $_POST['intangible'],
                'aumur' => $umur,                        
                'acoaaktiva' => $_POST['coaaktiva'],
                'acoadepresiasiakum' => $_POST['coaakum'],
                'acoadepresiasi' => $_POST['coabiaya'],
                'acoawriteoff' => $_POST['coawo'],  
                'amodifu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->where('aid',$id);        
        $this->db->update('baktiva',$data);

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
        $this->db->where('aid', $id);
        $this->db->delete('baktiva');

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
        $umur = ($_POST['utahun']*12)+$_POST['ubulan'];
        $data = array(
                'akode' => $_POST['kode'],
                'anama' => $_POST['nama'],
                'anomor' => $_POST['serial'],
                'adivisi' => $_POST['divisi'],  
                'akelompok' => $_POST['kelompok'],                                                
                'alokasi' => $_POST['lokasi'],
                'acatatan' => $_POST['lokasi'],                    
                'atglbeli' => tgl_database($_POST['tglbeli']),  
                'atglpakai' => tgl_database($_POST['tglpakai']),
                'ahargabeli' => $_POST['hargabeli'],     
                'abebanperbulan' => $_POST['bebanpenyusutan'],
                'anilairesidu' => $_POST['residu'],  
                'atipepenyusutan' => $_POST['metode'],                                                                      
                'ajmlaktiva' => $_POST['qty'],  
                'atgl15' => $_POST['tgl15'],                                                      
                'aaktivatidakberwujud' => $_POST['intangible'],
                'aumur' => $umur,                        
                'acoaaktiva' => $_POST['coaaktiva'],
                'acoadepresiasiakum' => $_POST['coaakum'],
                'acoadepresiasi' => $_POST['coabiaya'],
                'acoawriteoff' => $_POST['coawo'],  
                'acreateu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->insert('baktiva',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    
}