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

    function tambahData()
    {
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
