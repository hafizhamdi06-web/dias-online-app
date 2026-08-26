<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Admin_User extends CI_Model {

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
                'unamalengkap' => $_POST['namalengkap'],                
                'uactive' => $_POST['status']
        );        
        //$this->db->trans_begin();
        //$this->db->where('uid',$id);        
        //$this->db->update('auser',$data);

        //Hapus ausermenu sesuai id
        $this->db->where('auiduser', $id);
        $this->db->delete('aausermenu');

        //Insert detilmenu
        $r=1;
        $d = json_decode($_POST['detilmenu']);
        foreach($d as $item){
            $data_menu = array(
                    'auiduser' => $id,
                    'auidmenu' => $item->idmenu,
                    'auadd' => $item->tambah,
                    'auedit' => $item->edit,
                    'audell' => $item->delete,                    
                    'auprint' => $item->print,
                    'auapprove' => $item->buka
            );
            $this->db->insert('aausermenu',$data_menu);                        
            $r++;
        }                

        //Insert detilreport
        $r=1;
        $d = json_decode($_POST['detilreport']);
        foreach($d as $item){
            $data_report = array(
                    'auiduser' => $id,
                    'auidmenu' => $item->idmenu,
                    'auapprove' => $item->buka
            );
            $this->db->insert('aausermenu',$data_report);                        
            $r++;
        }                

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
        
        //$this->db->where('uid', $id);
        //$this->db->delete('auser');
        
        $this->db->where('auiduser', $id);
        $this->db->delete('aausermenu');

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

        $password = md5($_POST['pass']);   //hash('sha512',md5($_POST['pass']));        
        $data = array(
                'ukode' => $_POST['kode'],
                'unama' => $_POST['nama'],
                'unamalengkap' => $_POST['namalengkap'],                
                'upassword' => $password,
                'uactive' => $_POST['status']
        );        

        $this->db->trans_begin();
        $this->db->insert('auser',$data);
        $id = $this->db->insert_id();

        //Hapus ausermenu sesuai id
        $this->db->where('auiduser', $id);
        $this->db->delete('aausermenu');

        //Insert detilmenu
        $r=1;
        $d = json_decode($_POST['detilmenu']);
        foreach($d as $item){
            $data_menu = array(
                    'auiduser' => $id,
                    'auidmenu' => $item->idmenu,
                    'auadd' => $item->tambah,
                    'auedit' => $item->edit,
                    'audell' => $item->delete,                    
                    'auprint' => $item->print,
                    'auapprove' => $item->buka
            );
            $this->db->insert('aausermenu',$data_menu);                        
            $r++;
        }                

        //Insert detilreport
        $r=1;
        $d = json_decode($_POST['detilreport']);
        foreach($d as $item){
            $data_report = array(
                    'auiduser' => $id,
                    'auidmenu' => $item->idmenu,
                    'auapprove' => $item->buka
            );
            $this->db->insert('aausermenu',$data_report);                        
            $r++;
        }                

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    
}