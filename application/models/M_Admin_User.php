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
                'uactive' => $_POST['status'],
                'ukid' => empty($_POST['ukid']) ? null : $_POST['ukid'],
                'ucabang' => empty($_POST['ucabang']) ? null : $_POST['ucabang'],
                'unomor' => empty($_POST['unomor']) ? null : $_POST['unomor'],
                'ucabangpilih' => empty($_POST['ucabangpilih']) ? null : $_POST['ucabangpilih']
        );
        $this->db->trans_begin();
        $this->db->where('uid',$id);
        $this->db->update('auser',$data);

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

        //Hapus aauserrole sesuai id
        $this->db->where('AURIDUSER', $id);
        $this->db->delete('aauserrole');

        //Insert rolepilih
        $d = json_decode($_POST['rolepilih']);
        foreach($d as $idrole){
            $data_role = array(
                    'AURIDUSER' => $id,
                    'AURIDROLE' => $idrole,
                    'AURSTATUS' => 1
            );
            $this->db->insert('aauserrole',$data_role);
        }

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();
            return "sukses";
        }
    }

    function ubahSatuMenu()
    {
        $iduser = $_POST['id'];
        $idmenu = $_POST['idmenu'];

        $data = array(
                'auiduser' => $iduser,
                'auidmenu' => $idmenu,
                'auadd' => $_POST['tambah'],
                'auedit' => $_POST['edit'],
                'audell' => $_POST['delete'],
                'auprint' => $_POST['print'],
                'auapprove' => $_POST['buka']
        );

        $this->db->where('auiduser', $iduser);
        $this->db->where('auidmenu', $idmenu);
        $existing = $this->db->get('aausermenu')->row();

        $this->db->trans_begin();

        if ($existing) {
            $this->db->where('auiduser', $iduser);
            $this->db->where('auidmenu', $idmenu);
            $this->db->update('aausermenu', $data);
        } else {
            $this->db->insert('aausermenu', $data);
        }

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return json_encode(array('pesan' => 'rollback'));
        } else {
            $this->db->trans_commit();
            return json_encode(array('pesan' => 'sukses'));
        }
    }

    function ubahGudangPilihan()
    {
        $iduser = $_POST['id'];
        $ucabangpilih = empty($_POST['ucabangpilih']) ? null : $_POST['ucabangpilih'];

        $this->db->trans_begin();

        $this->db->where('uid', $iduser);
        $this->db->update('auser', array('ucabangpilih' => $ucabangpilih));

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return json_encode(array('pesan' => 'rollback'));
        } else {
            $this->db->trans_commit();
            return json_encode(array('pesan' => 'sukses'));
        }
    }

    function ubahRolePilihan()
    {
        $iduser = $_POST['id'];
        $d = json_decode($_POST['rolepilih']);

        $this->db->trans_begin();

        $this->db->where('AURIDUSER', $iduser);
        $this->db->delete('aauserrole');

        foreach ($d as $idrole) {
            $data_role = array(
                'AURIDUSER' => $iduser,
                'AURIDROLE' => $idrole,
                'AURSTATUS' => 1
            );
            $this->db->insert('aauserrole', $data_role);
        }

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return json_encode(array('pesan' => 'rollback'));
        } else {
            $this->db->trans_commit();
            return json_encode(array('pesan' => 'sukses'));
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
                'uactive' => $_POST['status'],
                'ukid' => empty($_POST['ukid']) ? null : $_POST['ukid'],
                'ucabang' => empty($_POST['ucabang']) ? null : $_POST['ucabang'],
                'unomor' => empty($_POST['unomor']) ? null : $_POST['unomor'],
                'ucabangpilih' => empty($_POST['ucabangpilih']) ? null : $_POST['ucabangpilih']
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

        //Insert rolepilih
        $d = json_decode($_POST['rolepilih']);
        foreach($d as $idrole){
            $data_role = array(
                    'AURIDUSER' => $id,
                    'AURIDROLE' => $idrole,
                    'AURSTATUS' => 1
            );
            $this->db->insert('aauserrole',$data_role);
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