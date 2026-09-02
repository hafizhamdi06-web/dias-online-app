<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Master_Pasien extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    private function _fields()
    {
        $tglkontrak = ($this->input->post('tglkontrak') != '') ? tgl_database($this->input->post('tglkontrak')) : null;
        $tgllahir   = ($this->input->post('tgllahir') != '')   ? tgl_database($this->input->post('tgllahir'))   : null;

        return array(
            'kkode'             => $this->input->post('kode'),
            'knama'             => $this->input->post('nama'),
            'ktipe'             => $this->input->post('kategori') ?: 14,
            'knomember'         => $this->input->post('nomember'),
            'kidpasien'         => $this->input->post('idpasien'),
            'knoktp'            => $this->input->post('noktp'),
            'kcabang'           => $this->input->post('cabang') ?: 0,
            'ktglkontrak'       => $tglkontrak,
            'ktgllahir'         => $tgllahir,
            'kpekerjaan'        => $this->input->post('pekerjaan'),
            'ktempatlahir'      => $this->input->post('tempatlahir'),
            'k1alamat'          => $this->input->post('alamat'),
            'k1kota'            => $this->input->post('kota'),
            'k1kecamatan'       => $this->input->post('kecamatan'),
            'k1telp1'           => $this->input->post('telp'),
            'k1email'           => $this->input->post('email'),
            'kcard'             => $this->input->post('nokartu'),
            'kkodelama'         => $this->input->post('kodetada'),
            'kkaryawan'         => $this->input->post('karyawan') ?: null,
            'kkaryawantraining' => $this->input->post('karyawantraining') ?: null,
            'kreff'             => $this->input->post('insider'),
            'kmarketingsource'  => $this->input->post('marketingsource') ?: null,
            'kbarulama'         => $this->input->post('barulama') ?: 0,
            'kjeniskelamin'     => $this->input->post('kelamin') ?: 0,
            'kaktif'            => $this->input->post('aktif') ?: 0,
        );
    }

    function ubahData()
    {
        $id = $this->input->post('id');
        $data = $this->_fields();
        $data['kmodifu'] = $this->session->id;

        $this->db->trans_begin();
        $this->db->where('kid', $id);
        $this->db->update('bkontak', $data);

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
        $this->db->where('kid', $id);
        $this->db->update('bkontak', array('kaktif' => 0, 'kmodifu' => $this->session->id));

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
        $data = $this->_fields();
        $data['kcreateu'] = $this->session->id;

        $this->db->trans_begin();
        $this->db->insert('bkontak', $data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();
            return "sukses";
        }
    }
}
