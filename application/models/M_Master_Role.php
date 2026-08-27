<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Master_Role extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
        $id = $_POST['id'];
        $data = array(
                'ARIDMENU' => empty($_POST['idmenu']) ? 0 : $_POST['idmenu'],
                'ARNAMAROLE' => $_POST['nama']
        );
        $this->db->trans_begin();
        $this->db->where('ARID',$id);
        $this->db->update('aarole',$data);

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
        $this->db->where('ARID', $id);
        $this->db->delete('aarole');

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
                'ARIDMENU' => empty($_POST['idmenu']) ? 0 : $_POST['idmenu'],
                'ARNAMAROLE' => $_POST['nama']
        );
        $this->db->trans_begin();
        $this->db->insert('aarole',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();
            return "sukses";
        }
    }
}
