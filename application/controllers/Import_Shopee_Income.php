<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Import_Shopee_Income extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if (!$this->session->has_userdata('nama')) {
            redirect(base_url('exception'));
        }
        $this->load->model('M_datatables');
    }

    private function _kolomMap()
    {
        return array(
            'No. Pesanan' => 'no_pesanan',
            'No. Pengajuan' => 'no_pengajuan',
            'Username (Pembeli)' => 'username_pembeli',
            'Waktu Pesanan Dibuat' => 'tgl_pesanan',
            'Metode pembayaran pembeli' => 'metode_pembayaran',
            'Tanggal Dana Dilepaskan' => 'tgl_dana_dilepaskan',
            'Harga Asli Produk' => 'harga_asli_produk',
            'Total Diskon Produk' => 'total_diskon_produk',
            'Jumlah Pengembalian Dana ke Pembeli' => 'pengembalian_dana_pembeli',
            'Diskon Produk dari Shopee' => 'diskon_produk_shopee',
            'Voucher disponsor oleh Penjual' => 'voucher_penjual',
            'Voucher co-fund disponsor oleh Penjual' => 'voucher_cofund_penjual',
            'Cashback Koin disponsori Penjual' => 'cashback_koin_penjual',
            'Cashback Koin Co-fund disponsori Penjual' => 'cashback_koin_cofund_penjual',
            'Ongkir Dibayar Pembeli' => 'ongkir_dibayar_pembeli',
            'Diskon Ongkir Ditanggung Jasa Kirim' => 'diskon_ongkir_jasa_kirim',
            'Gratis Ongkir dari Shopee' => 'gratis_ongkir_shopee',
            'Ongkir yang Diteruskan oleh Shopee ke Jasa Kirim' => 'ongkir_diteruskan_shopee',
            'Ongkos Kirim Pengembalian Barang' => 'ongkir_pengembalian_barang',
            'Kembali ke Biaya Pengiriman Pengirim' => 'kembali_biaya_pengiriman_pengirim',
            'Pengembalian Biaya Kirim' => 'pengembalian_biaya_kirim',
            'Biaya Komisi AMS' => 'biaya_komisi_ams',
            'Biaya Administrasi (termasuk PPN 11%)' => 'biaya_administrasi',
            'Biaya Layanan' => 'biaya_layanan',
            'Biaya Proses Pesanan' => 'biaya_proses_pesanan',
            'Premi' => 'premi',
            'Biaya Program Hemat Biaya Kirim' => 'biaya_program_hemat_ongkir',
            'Biaya Transaksi' => 'biaya_transaksi',
            'Biaya Kampanye' => 'biaya_kampanye',
            'Bea Masuk, PPN & PPh' => 'bea_masuk_ppn_pph',
            'Biaya Isi Saldo Otomatis (dari Penghasilan)' => 'biaya_isi_saldo_otomatis',
            'Total Penghasilan' => 'total_penghasilan',
            'Kode Voucher' => 'kode_voucher',
            'Kompensasi' => 'kompensasi',
            'Promo Gratis Ongkir dari Penjual' => 'promo_gratis_ongkir_penjual',
            'Jasa Kirim' => 'jasa_kirim',
            'Nama Kurir' => 'nama_kurir',
            'Pengembalian Dana ke Pembeli' => 'pengembalian_dana_retur',
            'Pro-rata Koin yang Ditukarkan untuk Pengembalian Barang' => 'prorata_koin_retur',
            'Pro-rata Voucher Shopee untuk Pengembalian Barang' => 'prorata_voucher_shopee_retur',
            'Pro-rated Bank Payment Channel Promotion for return refund Items' => 'prorated_bank_promo_retur',
            'Pro-rated Shopee Payment Channel Promotion for return refund Items' => 'prorated_shopee_promo_retur',
        );
    }

    private function _kolomTeks()
    {
        return array('no_pesanan', 'no_pengajuan', 'username_pembeli', 'metode_pembayaran', 'kode_voucher', 'jasa_kirim', 'nama_kurir');
    }

    private function _kolomTanggal()
    {
        return array('tgl_pesanan', 'tgl_dana_dilepaskan');
    }

    private function _normalize($str)
    {
        return trim(preg_replace('/\s+/', ' ', (string) $str));
    }

    function upload()
    {
        if (empty($_FILES['file']['tmp_name'])) {
            echo json_encode(array('pesan' => 'error', 'error' => 'File tidak ditemukan.'));
            return;
        }

        $namaFile = $_FILES['file']['name'];
        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
        if ($ext !== 'xlsx') {
            echo json_encode(array('pesan' => 'error', 'error' => 'File harus berformat .xlsx.'));
            return;
        }

        $this->load->library('Xlsx_reader');

        try {
            $rows = $this->xlsx_reader->read($_FILES['file']['tmp_name'], 'Income');
        } catch (Exception $e) {
            echo json_encode(array('pesan' => 'error', 'error' => $e->getMessage()));
            return;
        }

        $kolomMap = array();
        foreach ($this->_kolomMap() as $header => $field) {
            $kolomMap[$this->_normalize($header)] = $field;
        }

        $headerIdx = null;
        $colToField = array();
        foreach ($rows as $i => $row) {
            $normalized = array();
            foreach ($row as $idx => $val) {
                $normalized[$idx] = $this->_normalize($val);
            }
            if (in_array('No. Pesanan', $normalized, true) && in_array('Total Penghasilan', $normalized, true)) {
                $headerIdx = $i;
                foreach ($normalized as $idx => $text) {
                    if (isset($kolomMap[$text])) {
                        $colToField[$idx] = $kolomMap[$text];
                    }
                }
                break;
            }
        }

        if ($headerIdx === null) {
            echo json_encode(array('pesan' => 'error', 'error' => 'Baris header (No. Pesanan, Total Penghasilan) tidak ditemukan di sheet "Income".'));
            return;
        }

        $noPesananIdx = array_search('no_pesanan', $colToField, true);
        if ($noPesananIdx === false || !in_array('total_penghasilan', $colToField, true)) {
            echo json_encode(array('pesan' => 'error', 'error' => 'Kolom wajib (No. Pesanan / Total Penghasilan) tidak lengkap di file.'));
            return;
        }

        $kolomTeks = $this->_kolomTeks();
        $kolomTanggal = $this->_kolomTanggal();

        $this->db->trans_start();

        $jumlahBaris = 0;
        for ($i = $headerIdx + 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $noPesanan = isset($row[$noPesananIdx]) ? trim($row[$noPesananIdx]) : '';
            if ($noPesanan === '') {
                continue;
            }

            $data = array();
            foreach ($colToField as $idx => $field) {
                $val = isset($row[$idx]) ? $row[$idx] : null;

                if (in_array($field, $kolomTanggal, true)) {
                    $data[$field] = ($val !== null && $val !== '') ? $val : null;
                } elseif (in_array($field, $kolomTeks, true)) {
                    $data[$field] = $val;
                } else {
                    $data[$field] = ($val === null || $val === '') ? 0 : (float) $val;
                }
            }
            $data['sumber_file'] = $namaFile;
            $data['diimport_oleh'] = $this->session->id;

            $this->db->replace('shopee_income_import', $data);
            $jumlahBaris++;
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(array('pesan' => 'error', 'error' => 'Gagal menyimpan data ke database (rollback).'));
            return;
        }

        echo json_encode(array('pesan' => 'sukses', 'jumlah' => $jumlahBaris));
    }

    function view_riwayat()
    {
        $query = "SELECT A.id 'id', A.no_pesanan 'no_pesanan', A.username_pembeli 'username_pembeli',
                          DATE_FORMAT(A.tgl_pesanan,'%d-%m-%Y') 'tgl_pesanan',
                          DATE_FORMAT(A.tgl_dana_dilepaskan,'%d-%m-%Y') 'tgl_dana_dilepaskan',
                          A.metode_pembayaran 'metode_pembayaran',
                          A.total_penghasilan 'total_penghasilan',
                          COALESCE(B.unamalengkap,B.unama) 'diimport_oleh',
                          DATE_FORMAT(A.diimport_pada,'%d-%m-%Y %H:%i') 'diimport_pada'
                     FROM shopee_income_import A
                LEFT JOIN auser B ON A.diimport_oleh=B.uid";
        $search = array('A.no_pesanan', 'A.username_pembeli');
        $where = null;
        $isWhere = "";
        $isOrder = 'A.id DESC';

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere, $isOrder);
    }

}
