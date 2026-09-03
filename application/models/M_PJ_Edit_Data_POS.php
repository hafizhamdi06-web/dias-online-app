<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_PJ_Edit_Data_POS extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    private function _toNumber($v)
    {
        if ($v === null) return 0;
        $v = str_replace(array(' '), '', (string) $v);
        // buang pemisah ribuan, sisakan titik desimal & minus
        if (substr_count($v, ',') === 1 && strpos($v, '.') === false) {
            $v = str_replace(',', '.', $v);
        } else {
            $v = str_replace(',', '', $v);
        }
        return is_numeric($v) ? (float) $v : 0;
    }

    function simpanBaris()
    {
        $sdid         = (int) $this->input->post('sdid');
        $diskonpersen = $this->_toNumber($this->input->post('diskonpersen'));
        $diskon       = $this->_toNumber($this->input->post('diskon'));

        if ($sdid <= 0) {
            return json_encode(array('pesan' => 'Baris tidak valid.'));
        }
        if ($diskonpersen < 0 || $diskon < 0) {
            return json_encode(array('pesan' => 'Nilai diskon tidak boleh minus.'));
        }

        $row = $this->db->query(
            "SELECT D.sdid, D.sdidsu, D.sdkeluar, D.sdharga,
                    H.sunotransaksi, COALESCE(H.sumerchantjumlah,0) 'merchantjumlah'
               FROM fstokd D
         INNER JOIN fstoku H ON D.sdidsu = H.suid
              WHERE D.sdid = ".$sdid." LIMIT 1"
        )->row();

        if (!$row) {
            return json_encode(array('pesan' => 'Data baris tidak ditemukan.'));
        }
        if ($row->merchantjumlah <= 0) {
            return json_encode(array('pesan' => 'Transaksi ini bukan pembayaran merchant.'));
        }

        $subtotalBaris = ($row->sdkeluar * $row->sdharga) - $diskon;

        $this->db->trans_begin();

        $this->db->where('sdid', $sdid);
        $this->db->update('fstokd', array(
            'sddiskonpersen' => $diskonpersen,
            'sddiskon'       => $diskon,
        ));

        // sumerchantjumlah = agregat sub total seluruh baris transaksi
        $agg = $this->db->query(
            "SELECT SUM(sdkeluar * sdharga - sddiskon) 'total'
               FROM fstokd WHERE sdidsu = ".(int) $row->sdidsu
        )->row();
        $merchantjumlah = $agg ? (float) $agg->total : 0;

        $this->db->where('suid', $row->sdidsu);
        $this->db->update('fstoku', array('sumerchantjumlah' => $merchantjumlah));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return json_encode(array('pesan' => 'rollback'));
        }

        $this->db->trans_commit();
        return json_encode(array(
            'pesan'          => 'sukses',
            'notransaksi'    => $row->sunotransaksi,
            'subtotal'       => $subtotalBaris,
            'merchantjumlah' => $merchantjumlah,
        ));
    }
}
