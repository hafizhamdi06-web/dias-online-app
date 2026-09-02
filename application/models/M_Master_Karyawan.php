<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Master_Karyawan extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    private function _chk($name)
    {
        return $this->input->post($name) ? 1 : 0;
    }

    private function _fields()
    {
        $tgllahir = ($this->input->post('tgllahir') != '') ? tgl_database($this->input->post('tgllahir')) : null;
        $tgljoin  = ($this->input->post('tgljoin') != '')  ? tgl_database($this->input->post('tgljoin'))  : null;

        return array(
            'kkode'            => $this->input->post('kode'),
            'knama'            => $this->input->post('nama'),
            'ktipe'            => $this->input->post('kategori') ?: 4,
            'kjeniskaryawan'   => $this->input->post('jeniskaryawan') ?: 0,
            'kcabang'          => $this->input->post('cabang') ?: 0,
            'kaktif'           => $this->_chk('aktif'),

            // Tab Seting POS
            'kdoktersmy'       => $this->_chk('doktersmy'),
            'ksalesmarketing'  => $this->_chk('salesmarketing'),
            'kaos'             => $this->_chk('aos'),
            'kdokterbedah'     => $this->_chk('dokterbedah'),
            'kreseller'        => $this->_chk('reseller'),
            'kdokterpj'        => $this->_chk('dokterpj'),
            'ktampildidokter'  => $this->_chk('kolomdokter'),
            'ktampildiperawat' => $this->_chk('kolomperawat'),
            'ktampildiresep'   => $this->_chk('kolomresep'),
            'kdokterinsider'   => $this->_chk('dokterinsider'),

            // Tab Alamat & Identitas
            'k1alamat'         => $this->input->post('alamat'),
            'k1kota'           => $this->input->post('kota'),
            'k1kecamatan'      => $this->input->post('kecamatan'),
            'k1telp1'          => $this->input->post('nohp'),
            'k1email'          => $this->input->post('email'),
            'kjeniskelamin'    => $this->input->post('kelamin') ?: 0,
            'ktgllahir'        => $tgllahir,
            'knoktp'           => $this->input->post('noktp'),
            'kuser'            => $this->input->post('user') ?: null,
            'ktgljoin'         => $tgljoin,

            // Tab Payroll
            'kidemployee'      => $this->input->post('nik'),
            'knamaemployee'    => $this->input->post('namapanjang'),
            'kkodeinsider'     => $this->input->post('kodeinsider'),
            'kkelompokfu'      => $this->input->post('kelompokfu') ?: null,
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
