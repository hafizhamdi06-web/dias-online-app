<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_PJ_Editdepo extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    private function _autonumber($tgl, $cabang)
    {
        $kodecabang = @$_SESSION['kodecabang'];
        $nomor = 0;
        $nomor1 = $this->M_transaksi->prefixtrans(element('PJ_Editdepo', NID));
        $nomor2 = tgl_notrans($tgl);

        $notrans_length = strlen($nomor1) + 4;

        $sql = "SELECT MAX(RIGHT(sunotransaksi,4)) as 'maks'
                  FROM fstoku
                 WHERE MID(sunotransaksi,4,".$notrans_length.")='".$nomor1.$nomor2."' and sucabang='".$cabang."' ";

        $query = $this->db->query($sql);
        foreach ($query->result() as $res) {
            $nomor = number_format($res->maks) + 1;
        }

        switch(strlen($nomor)){
        case 1:
          $nomor = $nomor1.$nomor2."000".$nomor;
          break;
        case 2:
          $nomor = $nomor1.$nomor2."00".$nomor;
          break;
        case 3:
          $nomor = $nomor1.$nomor2."0".$nomor;
          break;
        case 4:
          $nomor = $nomor1.$nomor2.$nomor;
          break;
        }
        $nomor = $kodecabang."-".$nomor;
        return $nomor;
    }

    private function _simpanDetail($suid, $cabang, $sdidtindakan)
    {
        $detil = json_decode($_POST['detil']);
        $r = 1;
        foreach($detil as $item){
            $data_detil = array(
                'sdidsu' => $suid,
                'sditem' => $item->iid,
                'sdurutan' => $r,
                'sdkeluar' => $item->qty,
                'sdkeluard' => $item->qty,
                'sdsatuan' => $item->idsatuan,
                'sdsatuand' => $item->idsatuan,
                'sdgudang' => $cabang,
                'sdidualkes' => $sdidtindakan,
                'sdqtydasar' => $item->qtystandar,
            );
            $this->db->insert('fstokd', $data_detil);
            $r++;
        }

        // tandai baris tindakan asal sudah diinput penyusun alkesnya
        $this->db->where('sdid', $sdidtindakan);
        $this->db->update('fstokd', array('sdidalkesnya' => $suid));
    }

    private function _cekApprovalOverdue()
    {
        $idsupos = $_POST['idsupos'];

        $row = $this->db->query("SELECT sutanggal FROM fstoku WHERE suid='".$idsupos."'")->row();
        if (!$row) return true;

        $selisihHari = (strtotime(date('Y-m-d')) - strtotime($row->sutanggal)) / 86400;
        if ($selisihHari < 1) return true;

        $roleName = 'Approve Edit Depo Overdue';
        $selfRole = $this->db->query(
            "SELECT 1 FROM aauserrole B INNER JOIN aarole C ON B.AURIDROLE=C.ARID
              WHERE B.AURIDUSER='".$this->session->id."' AND B.AURSTATUS=1
                AND C.ARNAMAROLE='".$this->db->escape_str($roleName)."' LIMIT 1"
        )->row();
        if ($selfRole) return true;

        $approved = $this->db->query(
            "SELECT APID FROM aapersetujuan
              WHERE APIDUSERMINTA='".$this->session->id."'
                AND APJENIS='Edit Depo Overdue'
                AND APREFERENSI='".$this->db->escape_str($_POST['noip'])."'
                AND APSTATUS=1
                AND APTGLEXPIRED > '".date('Y-m-d H:i:s')."'
              ORDER BY APID DESC LIMIT 1"
        )->row();

        return $approved ? true : false;
    }

    function tambahData()
    {
        if (!$this->_cekApprovalOverdue()) {
            return json_encode(array('pesan'=>'butuh_persetujuan'));
        }

        $tgl = tgl_database($_POST['tanggaldepo']);
        $cabang = $_POST['idcabang'];
        $sdidtindakan = $_POST['sdidtindakan'];
        $nomor = $this->_autonumber($_POST['tanggaldepo'], $cabang);

        $this->db->trans_begin();

        $data_header = array(
            'susumber' => 'AL',
            'sunotransaksi' => $nomor,
            'sutanggal' => $tgl,
            'sukontak' => $_POST['idkontak'],
            'sunoref' => $_POST['noip'],
            'suuraian' => 'Alkes No IP '.$_POST['noip'].' Tindakan '.$_POST['namatindakan'],
            'sucreateu' => $this->session->id,
            'sumodifu' => $this->session->id,
            'sumodifd' => date('Y-m-d H:i:s'),
            'sucabang' => $cabang,
            'suidualkes' => $_POST['idsupos'],
        );
        $this->db->insert('fstoku', $data_header);
        $suid = $this->db->insert_id();

        $this->_simpanDetail($suid, $cabang, $sdidtindakan);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return json_encode(array('pesan'=>'rollback'));
        } else {
            $this->db->trans_commit();
            return json_encode(array('pesan'=>'sukses','id'=>$suid,'nomor'=>$nomor));
        }
    }

    function ubahData()
    {
        if (!$this->_cekApprovalOverdue()) {
            return json_encode(array('pesan'=>'butuh_persetujuan'));
        }

        $id = $_POST['id'];
        $tgl = tgl_database($_POST['tanggaldepo']);
        $cabang = $_POST['idcabang'];
        $sdidtindakan = $_POST['sdidtindakan'];

        $this->db->trans_begin();

        $data_header = array(
            'sutanggal' => $tgl,
            'sukontak' => $_POST['idkontak'],
            'sunoref' => $_POST['noip'],
            'suuraian' => 'Alkes No IP '.$_POST['noip'].' Tindakan '.$_POST['namatindakan'],
            'sumodifu' => $this->session->id,
            'sumodifd' => date('Y-m-d H:i:s'),
            'sucabang' => $cabang,
            'suidualkes' => $_POST['idsupos'],
        );
        $this->db->where('suid', $id);
        $this->db->update('fstoku', $data_header);

        $this->db->where('sdidsu', $id);
        $this->db->delete('fstokd');

        $this->_simpanDetail($id, $cabang, $sdidtindakan);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return json_encode(array('pesan'=>'rollback'));
        } else {
            $this->db->trans_commit();
            return json_encode(array('pesan'=>'sukses','id'=>$id,'nomor'=>$id));
        }
    }

    function hapusData()
    {
        $id = $_POST['id'];

        $this->db->trans_begin();
        $this->db->where('bid', $id);
        $this->db->delete('bbank');

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();
            return "sukses";
        }
    }
}
