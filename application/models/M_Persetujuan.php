<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Persetujuan extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ajukan()
    {
        $jenis = $_POST['jenis'];
        $referensi = $_POST['referensi'];
        $keterangan = $_POST['keterangan'];
        $iduserapprover = $_POST['iduserapprover'];
        $iduserminta = $this->session->id;

        $this->db->where('APIDUSERMINTA', $iduserminta);
        $this->db->where('APJENIS', $jenis);
        $this->db->where('APREFERENSI', $referensi);
        $this->db->where('APSTATUS', 0);
        $existing = $this->db->get('aapersetujuan')->row();

        if ($existing) {
            return json_encode(array('pesan' => 'sukses', 'id' => $existing->APID));
        }

        $this->db->trans_begin();

        $data = array(
            'APJENIS' => $jenis,
            'APREFERENSI' => $referensi,
            'APKETERANGAN' => $keterangan,
            'APIDUSERMINTA' => $iduserminta,
            'APIDUSERSETUJU' => $iduserapprover,
            'APSTATUS' => 0,
            'APTGLMINTA' => date('Y-m-d H:i:s'),
        );
        $this->db->insert('aapersetujuan', $data);
        $id = $this->db->insert_id();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return json_encode(array('pesan' => 'rollback'));
        } else {
            $this->db->trans_commit();
            return json_encode(array('pesan' => 'sukses', 'id' => $id));
        }
    }

    function cekstatus()
    {
        $jenis = $_POST['jenis'];
        $referensi = $_POST['referensi'];
        $iduserminta = $this->session->id;

        // Jika user yang meminta sendiri punya hak approve untuk jenis ini,
        // langsung dianggap disetujui (tidak perlu minta ke orang lain).
        $roleName = 'Approve '.$jenis;
        $selfRole = $this->db->query(
            "SELECT 1 FROM aauserrole B
               INNER JOIN aarole C ON B.AURIDROLE = C.ARID
              WHERE B.AURIDUSER = '".$iduserminta."'
                AND B.AURSTATUS = 1
                AND C.ARNAMAROLE = '".$this->db->escape_str($roleName)."'
              LIMIT 1"
        )->row();
        if ($selfRole) {
            return json_encode(array('status' => 'disetujui', 'self' => 1));
        }

        $query = "SELECT A.APSTATUS 'status', A.APCATATAN 'catatan', A.APTGLEXPIRED 'expired',
                          COALESCE(B.unamalengkap, B.unama) 'approver'
                     FROM aapersetujuan A
                LEFT JOIN auser B ON A.APIDUSERSETUJU = B.uid
                    WHERE A.APIDUSERMINTA = '".$iduserminta."'
                      AND A.APJENIS = '".$this->db->escape_str($jenis)."'
                      AND A.APREFERENSI = '".$this->db->escape_str($referensi)."'
                 ORDER BY A.APID DESC
                    LIMIT 1";
        $row = $this->db->query($query)->row();

        if (!$row) {
            return json_encode(array('status' => 'belum_ada'));
        }

        if ($row->status == 1) {
            if ($row->expired !== null && strtotime($row->expired) > time()) {
                return json_encode(array('status' => 'disetujui'));
            }
            return json_encode(array('status' => 'kadaluarsa'));
        }

        if ($row->status == 2) {
            return json_encode(array('status' => 'ditolak', 'catatan' => $row->catatan));
        }

        return json_encode(array('status' => 'pending', 'approver' => $row->approver));
    }

    function setuju()
    {
        $id = $_POST['id'];

        $this->db->trans_begin();

        $data = array(
            'APSTATUS' => 1,
            'APTGLRESPON' => date('Y-m-d H:i:s'),
            'APTGLEXPIRED' => date('Y-m-d H:i:s', strtotime('+10 minutes')),
        );
        $this->db->where('APID', $id);
        $this->db->where('APIDUSERSETUJU', $this->session->id);
        $this->db->update('aapersetujuan', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return 'rollback';
        } else {
            $this->db->trans_commit();
            return 'sukses';
        }
    }

    function tolak()
    {
        $id = $_POST['id'];
        $catatan = @$_POST['catatan'];

        $this->db->trans_begin();

        $data = array(
            'APSTATUS' => 2,
            'APTGLRESPON' => date('Y-m-d H:i:s'),
            'APCATATAN' => $catatan,
        );
        $this->db->where('APID', $id);
        $this->db->where('APIDUSERSETUJU', $this->session->id);
        $this->db->update('aapersetujuan', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return 'rollback';
        } else {
            $this->db->trans_commit();
            return 'sukses';
        }
    }

    function listpending()
    {
        $query = "SELECT A.APID 'id', A.APJENIS 'jenis', A.APKETERANGAN 'keterangan',
                          COALESCE(B.unamalengkap, B.unama) 'pemohon',
                          DATE_FORMAT(A.APTGLMINTA,'%d-%m-%Y %H:%i') 'tanggal'
                     FROM aapersetujuan A
                LEFT JOIN auser B ON A.APIDUSERMINTA = B.uid
                    WHERE A.APIDUSERSETUJU = '".$this->session->id."' AND A.APSTATUS = 0
                 ORDER BY A.APID DESC";
        return $this->M_transaksi->get_data_query($query);
    }

    function getverifikasidata()
    {
        $id = $_POST['id'];

        $query = "SELECT A.APID 'id', A.APJENIS 'jenis', A.APKETERANGAN 'keterangan',
                          COALESCE(B.unamalengkap, B.unama) 'pemohon',
                          DATE_FORMAT(A.APTGLMINTA,'%d-%m-%Y %H:%i') 'tanggal'
                     FROM aapersetujuan A
                LEFT JOIN auser B ON A.APIDUSERMINTA = B.uid
                    WHERE A.APID = '".$id."' AND A.APIDUSERSETUJU = '".$this->session->id."'";
        return $this->M_transaksi->get_data_query($query);
    }
}
