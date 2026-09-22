<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Katalog laporan yg BOLEH dipilih DIAS AI utk user yg sedang login --
 * dipakai supaya AI cuma memilih dari daftar laporan yg sudah ada &
 * user ybs benar2 punya akses (dicek via aausermenu), bukan bebas.
 */
class M_Dias_Ai extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    // Laporan (aareport) yg user ybs berhak akses, lengkap dgn flag filternya
    // spy DIAS AI tahu field mana yg perlu diisi (tanggal/PT/cabang).
    function getKatalogLaporan()
    {
        $uid = (int) $this->session->id;

        $query = "SELECT C.ARID 'arid', C.ARNAME 'nama', C.ARLINK 'link',
                         IFNULL(C.ARDATE1F,0) 'ardate1f', IFNULL(C.ARDATE2F,0) 'ardate2f',
                         IFNULL(C.ARGUDANGF,0) 'argudangf', IFNULL(C.ARPTF,0) 'arptf',
                         IFNULL(C.ARSALDOF,0) 'arsaldof'
                    FROM aamenu A
              INNER JOIN aausermenu B ON A.MID = B.AUIDMENU AND B.AUAPPROVE = 1 AND B.AUIDUSER = " . $uid . "
              INNER JOIN aareport C ON A.MREPORT = C.ARID
                   WHERE A.MTYPE = 1 AND A.MACTIVE = 1 AND C.ARACTIVE = 1
                ORDER BY C.ARNAME";

        return $this->db->query($query)->result();
    }

    // Cek ulang server-side: apakah $arid ada di katalog yg boleh diakses user ybs
    // (dipakai utk validasi keluaran AI sblm menampilkan link download-nya).
    function bolehAksesLaporan($arid)
    {
        foreach ($this->getKatalogLaporan() as $r) {
            if ((int) $r->arid === (int) $arid) {
                return $r;
            }
        }
        return null;
    }

    function getKatalogPt()
    {
        return $this->db->query("SELECT npid 'id', npnama 'nama' FROM bnamapt ORDER BY npnama")->result();
    }

    function getKatalogGudang()
    {
        return $this->db->query("SELECT gid 'id', gnama 'nama', gpt 'pt' FROM bgudang ORDER BY gnama")->result();
    }

}
