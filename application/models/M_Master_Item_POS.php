<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Master_Item_POS extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function tambahData()
    {
        $data = array(
                'ikode' => $_POST['kode'],
                'inama' => $_POST['nama'],
                'icomersialname' => $_POST['namaweb'],
                'ikategori' => $_POST['kategori'],
                'istatus' => $_POST['status'],
                'iserial' => $_POST['serial'],
                'iqtyperbox' => $_POST['qtyperbox'],
                'isatuand' => $_POST['satuand'],
                'isatuan' => $_POST['satuan'],
                'istockmaksimal' => $_POST['stokmaks'],
                'istockminimal' => $_POST['stokmin'],
                'istockreorder' => $_POST['stokreorder'],
                'imaxorder' => $_POST['maxorder'],
                'icreateu' => $this->session->id
        );

        $this->db->trans_start();
        $this->db->insert('bitem', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "rollback";
        } else {
            return "sukses";
        }
    }

    function ubahData()
    {
        $id = $_POST['id'];
        $data = array(
                'ikode' => $_POST['kode'],
                'inama' => $_POST['nama'],
                'icomersialname' => $_POST['namaweb'],
                'ikategori' => $_POST['kategori'],
                'istatus' => $_POST['status'],
                'iserial' => $_POST['serial'],
                'iqtyperbox' => $_POST['qtyperbox'],
                'isatuand' => $_POST['satuand'],
                'isatuan' => $_POST['satuan'],
                'istockmaksimal' => $_POST['stokmaks'],
                'istockminimal' => $_POST['stokmin'],
                'istockreorder' => $_POST['stokreorder'],
                'imaxorder' => $_POST['maxorder'],
                'imodifu' => $this->session->id
        );

        $this->db->trans_start();
        $this->db->where('iid', $id);
        $this->db->update('bitem', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "rollback";
        } else {
            return "sukses";
        }
    }

    function hapusData()
    {
        $id = $_POST['id'];

        $this->db->trans_start();
        $this->db->where('iid', $id);
        $this->db->delete('bitem');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "rollback";
        } else {
            return "sukses";
        }
    }

}
