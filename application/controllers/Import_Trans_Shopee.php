<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Import_Trans_Shopee extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if (!$this->session->has_userdata('nama')) {
            redirect(base_url('exception'));
        }
        $this->load->model('M_datatables');
    }

    // Header Excel template Shopee -> nama kolom tabel zztemptransshopee (urut kolom Excel)
    private function _kolomMap()
    {
        return array(
            'No. Pesanan'                                                    => 'no_pesanan',
            'Status Pesanan'                                                 => 'status_pesanan',
            'Alasan Pembatalan'                                              => 'alasan_pembatalan',
            'Status Pembatalan/ Pengembalian'                               => 'status_pembatalan_pengembalian',
            'No. Resi'                                                       => 'no_resi',
            'Opsi Pengiriman'                                                => 'opsi_pengiriman',
            'Antar ke counter/ pick-up'                                      => 'antar_ke_counter',
            'Pesanan Harus Dikirimkan Sebelum (Menghindari keterlambatan)'   => 'pesanan_dikirim_sebelum',
            'Waktu Pengiriman Diatur'                                        => 'waktu_pengiriman_diatur',
            'Waktu Pesanan Dibuat'                                           => 'waktu_pesanan_dibuat',
            'Waktu Pembayaran Dilakukan'                                     => 'waktu_pembayaran',
            'Tipe Pesanan'                                                   => 'tipe_pesanan',
            'Metode Pembayaran'                                              => 'metode_pembayaran',
            'SKU Induk'                                                      => 'sku_induk',
            'Nama Produk'                                                    => 'nama_produk',
            'Nomor Referensi SKU'                                            => 'nomor_referensi_sku',
            'Nama Variasi'                                                   => 'nama_variasi',
            'Harga Awal'                                                     => 'harga_awal',
            'Harga Setelah Diskon'                                           => 'harga_setelah_diskon',
            'Jumlah'                                                         => 'jumlah',
            'Returned quantity'                                              => 'returned_quantity',
            'Subtotal Pesanan'                                               => 'subtotal_pesanan',
            'Total Diskon'                                                   => 'total_diskon',
            'Diskon Dari Penjual'                                            => 'diskon_dari_penjual',
            'Diskon Dari Shopee'                                             => 'diskon_dari_shopee',
            'Berat Produk'                                                   => 'berat_produk',
            'Jumlah Produk di Pesan'                                         => 'jumlah_produk_dipesan',
            'Total Berat'                                                    => 'total_berat',
            'Voucher Ditanggung Penjual'                                     => 'voucher_ditanggung_penjual',
            'Cashback Koin'                                                  => 'cashback_koin',
            'Voucher Ditanggung Shopee'                                      => 'voucher_ditanggung_shopee',
            'Paket Diskon'                                                   => 'paket_diskon',
            'Paket Diskon (Diskon dari Shopee)'                              => 'paket_diskon_dari_shopee',
            'Paket Diskon (Diskon dari Penjual)'                             => 'paket_diskon_dari_penjual',
            'Potongan Koin Shopee'                                           => 'potongan_koin_shopee',
            'Diskon Kartu Kredit'                                            => 'diskon_kartu_kredit',
            'Ongkos Kirim Dibayar oleh Pembeli'                              => 'ongkir_dibayar_pembeli',
            'Estimasi Potongan Biaya Pengiriman'                             => 'estimasi_potongan_biaya_pengiriman',
            'Ongkos Kirim Pengembalian Barang'                               => 'ongkir_pengembalian_barang',
            'Total Pembayaran'                                               => 'total_pembayaran',
            'Perkiraan Ongkos Kirim'                                         => 'perkiraan_ongkir',
            'Catatan dari Pembeli'                                           => 'catatan_dari_pembeli',
            'Catatan'                                                        => 'catatan',
            'Username (Pembeli)'                                             => 'username_pembeli',
            'Nama Penerima'                                                  => 'nama_penerima',
            'No. Telepon'                                                    => 'no_telepon',
            'Alamat Pengiriman'                                              => 'alamat_pengiriman',
            'Kota/Kabupaten'                                                 => 'kota_kabupaten',
            'Provinsi'                                                       => 'provinsi',
            'Waktu Pesanan Selesai'                                          => 'waktu_pesanan_selesai',
        );
    }

    private function _normalize($str)
    {
        return trim(preg_replace('/\s+/', ' ', (string) $str));
    }

    function upload()
    {
        header('Content-Type: application/json');

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
            $rows = $this->xlsx_reader->read($_FILES['file']['tmp_name'], 'Sheet1');
        } catch (Exception $e) {
            echo json_encode(array('pesan' => 'error', 'error' => $e->getMessage()));
            return;
        }

        $kolomMap = array();
        foreach ($this->_kolomMap() as $header => $field) {
            $kolomMap[$this->_normalize($header)] = $field;
        }

        // Cari baris header
        $headerIdx  = null;
        $colToField = array();
        foreach ($rows as $i => $row) {
            $normalized = array();
            foreach ($row as $idx => $val) {
                $normalized[$idx] = $this->_normalize($val);
            }
            if (in_array('No. Pesanan', $normalized, true) && in_array('Nama Produk', $normalized, true)) {
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
            echo json_encode(array('pesan' => 'error', 'error' => 'Baris header (No. Pesanan, Nama Produk) tidak ditemukan di sheet "Sheet1".'));
            return;
        }

        $noPesananIdx = array_search('no_pesanan', $colToField, true);
        if ($noPesananIdx === false) {
            echo json_encode(array('pesan' => 'error', 'error' => 'Kolom "No. Pesanan" tidak ditemukan di file.'));
            return;
        }

        $dataRows   = array();
        $noPesanan  = array();
        for ($i = $headerIdx + 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $np  = isset($row[$noPesananIdx]) ? trim((string) $row[$noPesananIdx]) : '';
            if ($np === '') {
                continue;
            }

            $data = array();
            foreach ($colToField as $idx => $field) {
                $val = isset($row[$idx]) ? trim((string) $row[$idx]) : null;
                $data[$field] = ($val === '') ? null : $val;
            }
            $data['sumber_file']   = $namaFile;
            $data['diimport_oleh'] = $this->session->id;

            $dataRows[] = $data;
            $noPesanan[$np] = true;
        }

        if (empty($dataRows)) {
            echo json_encode(array('pesan' => 'error', 'error' => 'Tidak ada baris data di file.'));
            return;
        }

        $this->db->trans_start();

        // Hapus data lama untuk No. Pesanan yang diimport (idempotent)
        $this->db->where_in('no_pesanan', array_keys($noPesanan));
        $this->db->delete('zztemptransshopee');

        foreach (array_chunk($dataRows, 200) as $chunk) {
            $this->db->insert_batch('zztemptransshopee', $chunk);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(array('pesan' => 'error', 'error' => 'Gagal menyimpan data ke database (rollback).'));
            return;
        }

        echo json_encode(array('pesan' => 'sukses', 'jumlah' => count($dataRows)));
    }

    function view_riwayat()
    {
        $query = "SELECT A.id 'id', A.no_pesanan 'no_pesanan', A.username_pembeli 'username_pembeli',
                         A.waktu_pesanan_dibuat 'waktu_pesanan_dibuat', A.status_pesanan 'status_pesanan',
                         A.nama_produk 'nama_produk', A.jumlah 'jumlah', A.total_pembayaran 'total_pembayaran',
                         COALESCE(B.unamalengkap,B.unama) 'diimport_oleh',
                         DATE_FORMAT(A.diimport_pada,'%d-%m-%Y %H:%i') 'diimport_pada'
                    FROM zztemptransshopee A
               LEFT JOIN auser B ON A.diimport_oleh=B.uid";
        $search = array('A.no_pesanan', 'A.username_pembeli', 'A.nama_produk');
        $where = null;
        $isWhere = "";
        $isOrder = 'A.id DESC';

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere, $isOrder);
    }

}
