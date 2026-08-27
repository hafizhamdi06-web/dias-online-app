<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Master_Lain extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
        $id = $_POST['id'];
        $data = array(
                'lkode' => $_POST['kode'],
                'lnama' => $_POST['nama'],
                'lketerangan' => $_POST['keterangan'],
                'ltipe' => $_POST['tipe'],
                'lgudangid' => empty($_POST['gudang']) ? null : $_POST['gudang'],
                'lgudangnama' => empty($_POST['gudang']) ? null : $this->_namaGudang($_POST['gudang']),
                'lmodifu' => $this->session->id
        );
        $this->db->trans_begin();
        $this->db->where('lid',$id);
        $this->db->update('blain',$data);

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
        $this->db->where('lid', $id);
        $this->db->delete('blain');

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
                'lkode' => $_POST['kode'],
                'lnama' => $_POST['nama'],
                'lketerangan' => $_POST['keterangan'],
                'ltipe' => $_POST['tipe'],
                'lgudangid' => empty($_POST['gudang']) ? null : $_POST['gudang'],
                'lgudangnama' => empty($_POST['gudang']) ? null : $this->_namaGudang($_POST['gudang']),
                'lcreateu' => $this->session->id
        );
        $this->db->trans_begin();
        $this->db->insert('blain',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();
            return "sukses";
        }
    }

    function _namaGudang($id)
    {
        $this->db->select('gnama');
        $row = $this->db->get_where('bgudang', array('gid' => $id))->row();
        return $row ? $row->gnama : null;
    }
}
