<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Shopee_Api extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if (!$this->session->has_userdata('nama')) {
            redirect(base_url('exception'));
        }
        $this->load->model('M_datatables');
    }

    // ============================================================
    // Setting (kredensial, otorisasi & token)
    // ============================================================

    function setting_get()
    {
        $row = $this->db->order_by('id', 'DESC')->limit(1)->get('aashopeeapi')->row();
        header('Content-Type: application/json');

        if (!$row) {
            echo json_encode(array('data' => null));
            return;
        }

        echo json_encode(array('data' => array(
            'id'                  => $row->id,
            'nama_toko'           => $row->nama_toko,
            'partner_id'          => $row->partner_id,
            'partner_key_mask'    => $row->partner_key ? str_repeat('*', max(0, strlen($row->partner_key) - 4)) . substr($row->partner_key, -4) : '',
            'shop_id'             => $row->shop_id,
            'redirect_uri'        => $row->redirect_uri,
            'environment'         => $row->environment,
            'cabang'              => $row->cabang,
            'aktif'               => $row->aktif,
            'terhubung'           => !empty($row->access_token) ? 1 : 0,
            'access_token_expire' => $row->access_token_expire,
        )));
    }

    function setting_save()
    {
        header('Content-Type: application/json');

        $data = array(
            'nama_toko'    => $this->input->post('nama_toko'),
            'partner_id'   => $this->input->post('partner_id'),
            'shop_id'      => $this->input->post('shop_id'),
            'redirect_uri' => $this->input->post('redirect_uri'),
            'environment'  => $this->input->post('environment') === 'sandbox' ? 'sandbox' : 'live',
            'cabang'       => (int) $this->input->post('cabang'),
            'aktif'        => 1,
            'diubah_oleh'  => $this->session->id,
        );

        // partner_key hanya ditimpa kalau diisi ulang (form menampilkan versi tersamar, bukan aslinya)
        $partnerKey = $this->input->post('partner_key');
        if ($partnerKey !== null && $partnerKey !== '') {
            $data['partner_key'] = $partnerKey;
        }

        $existing = $this->db->order_by('id', 'DESC')->limit(1)->get('aashopeeapi')->row();

        if ($existing) {
            $this->db->where('id', $existing->id)->update('aashopeeapi', $data);
        } else {
            $this->db->insert('aashopeeapi', $data);
        }

        $this->_userlog('Simpan seting API Shopee: ' . $data['nama_toko']);

        echo json_encode(array('pesan' => 'sukses'));
    }

    // URL utk dibuka user (login & authorize toko Shopee)
    function auth_url()
    {
        header('Content-Type: application/json');
        try {
            $this->load->library('Shopee_Api');
            if (!$this->shopee_api->getSetting()) {
                echo json_encode(array('pesan' => 'error', 'error' => 'Isi & simpan partner_id/partner_key/redirect_uri dulu.'));
                return;
            }
            echo json_encode(array('pesan' => 'sukses', 'url' => $this->shopee_api->getAuthUrl()));
        } catch (\Throwable $e) {
            echo json_encode(array('pesan' => 'error', 'error' => $e->getMessage()));
        }
    }

    // Shopee redirect ke sini setelah user otorisasi toko: ?code=...&shop_id=...
    function callback()
    {
        $code   = $this->input->get('code');
        $shopId = $this->input->get('shop_id');

        $pesan = 'Otorisasi berhasil. Silakan tutup tab ini dan kembali ke DIAS.';
        $ok    = true;

        try {
            if (empty($code) || empty($shopId)) {
                throw new Exception('Parameter code/shop_id tidak ada dari Shopee.');
            }
            $this->load->library('Shopee_Api');
            $this->shopee_api->tukarKodeToken($code, $shopId);
            $this->_userlog('Otorisasi Shopee berhasil, shop_id=' . $shopId);
        } catch (\Throwable $e) {
            $ok = false;
            $pesan = 'Otorisasi GAGAL: ' . $e->getMessage();
        }

        echo '<!doctype html><html><body style="font-family:sans-serif;padding:40px;text-align:center;">'
           . '<h3 style="color:' . ($ok ? '#28a745' : '#dc3545') . '">' . ($ok ? 'Berhasil' : 'Gagal') . '</h3>'
           . '<p>' . htmlspecialchars($pesan) . '</p>'
           . '<script>if(window.opener){window.opener.postMessage("shopee-auth-' . ($ok ? 'ok' : 'gagal') . '","*");}</script>'
           . '</body></html>';
    }

    // ============================================================
    // Cek Produk (product/get_item_list + get_item_base_info)
    // ============================================================

    function cek_produk()
    {
        header('Content-Type: application/json');
        try {
            $this->load->library('Shopee_Api');
            $items = $this->shopee_api->tarikSemuaProduk();

            $rows = array();
            foreach ($items as $it) {
                $sku   = (string) ($it['item_sku'] ?? '');
                $harga = 0;
                if (!empty($it['price_info'][0]['current_price'])) $harga = $it['price_info'][0]['current_price'];
                $stok = 0;
                if (isset($it['stock_info_v2']['summary_info']['total_available_stock'])) {
                    $stok = $it['stock_info_v2']['summary_info']['total_available_stock'];
                }

                $rows[] = array(
                    'item_id'          => $it['item_id'],
                    'item_sku'         => $sku,
                    'item_name'        => mb_substr((string) ($it['item_name'] ?? ''), 0, 500),
                    'category_id'      => $it['category_id'] ?? null,
                    'harga'            => $harga,
                    'stok'             => $stok,
                    'item_status'      => $it['item_status'] ?? '',
                    'cocok_kode_lokal' => $this->_cocokkanKodeLokal($sku),
                    'update_time'      => !empty($it['update_time']) ? date('Y-m-d H:i:s', $it['update_time']) : null,
                    'raw_json'         => json_encode($it),
                    'ditarik_oleh'     => $this->session->id,
                );
            }

            if (!empty($rows)) {
                foreach (array_chunk($rows, 100) as $chunk) {
                    foreach ($chunk as $r) {
                        // upsert per item_id
                        $exists = $this->db->where('item_id', $r['item_id'])->get('zzshopeeproduk')->row();
                        if ($exists) {
                            $this->db->where('id', $exists->id)->update('zzshopeeproduk', $r);
                        } else {
                            $this->db->insert('zzshopeeproduk', $r);
                        }
                    }
                }
            }

            $this->_userlog('Cek Produk Shopee: ' . count($rows) . ' produk');
            echo json_encode(array('pesan' => 'sukses', 'jumlah' => count($rows)));
        } catch (\Throwable $e) {
            echo json_encode(array('pesan' => 'error', 'error' => $e->getMessage()));
        }
    }

    private function _cocokkanKodeLokal($sku)
    {
        if ($sku === '') return null;
        $row = $this->db->select('IKODE')
            ->from('bitem2')
            ->join('bitem', 'bitem.IID = bitem2.I2IDITEM')
            ->where('bitem2.I2SKUSHOPEE', $sku)
            ->limit(1)->get()->row();
        if ($row) return $row->IKODE;

        $row = $this->db->select('IKODE')->from('bitem')->where('IKODE', $sku)->limit(1)->get()->row();
        return $row ? $row->IKODE : null;
    }

    function view_produk_list()
    {
        $query = "SELECT id 'id', item_id 'item_id', item_sku 'item_sku', item_name 'item_name',
                         harga 'harga', stok 'stok', item_status 'item_status',
                         COALESCE(cocok_kode_lokal,'-') 'cocok_kode_lokal',
                         DATE_FORMAT(update_time,'%d-%m-%Y %H:%i') 'update_time',
                         DATE_FORMAT(ditarik_pada,'%d-%m-%Y %H:%i') 'ditarik_pada'
                    FROM zzshopeeproduk";
        $search  = array('item_sku', 'item_name', 'cocok_kode_lokal');
        $isOrder = 'item_name ASC';

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query, $search, null, "", $isOrder);
    }

    // ============================================================
    // Tarik Penjualan (order/get_order_list + get_order_detail)
    // ============================================================

    function tarik_penjualan()
    {
        header('Content-Type: application/json');
        try {
            $tgldari   = $this->input->post('tgldari');
            $tglsampai = $this->input->post('tglsampai');
            if (empty($tgldari) || empty($tglsampai)) {
                echo json_encode(array('pesan' => 'error', 'error' => 'Tanggal dari/sampai wajib diisi.'));
                return;
            }

            $timeFrom = strtotime($tgldari . ' 00:00:00');
            $timeTo   = strtotime($tglsampai . ' 23:59:59');
            if (($timeTo - $timeFrom) > (15 * 86400)) {
                echo json_encode(array('pesan' => 'error', 'error' => 'Rentang tanggal maksimal 15 hari per tarik (batas API Shopee).'));
                return;
            }

            $this->load->library('Shopee_Api');
            $orders = $this->shopee_api->tarikSemuaOrder($timeFrom, $timeTo);

            $jumlahOrder = 0;
            $jumlahBaris = 0;

            foreach ($orders as $o) {
                $header = array(
                    'order_sn'         => $o['order_sn'],
                    'order_status'     => $o['order_status'] ?? '',
                    'create_time'      => !empty($o['create_time']) ? date('Y-m-d H:i:s', $o['create_time']) : null,
                    'update_time'      => !empty($o['update_time']) ? date('Y-m-d H:i:s', $o['update_time']) : null,
                    'pay_time'         => !empty($o['pay_time']) ? date('Y-m-d H:i:s', $o['pay_time']) : null,
                    'total_amount'     => $o['total_amount'] ?? 0,
                    'currency'         => $o['currency'] ?? '',
                    'buyer_username'   => $o['buyer_username'] ?? '',
                    'recipient_name'   => $o['recipient_address']['name'] ?? '',
                    'recipient_phone'  => $o['recipient_address']['phone'] ?? '',
                    'payment_method'   => $o['payment_method'] ?? '',
                    'shipping_carrier' => $o['shipping_carrier'] ?? '',
                    'tracking_number'  => $o['tracking_no'] ?? '',
                    'note'             => mb_substr((string) ($o['note'] ?? ''), 0, 500),
                    'raw_json'         => json_encode($o),
                    'ditarik_oleh'     => $this->session->id,
                );

                $existing = $this->db->where('order_sn', $header['order_sn'])->get('zzshopeeorder')->row();
                if ($existing) {
                    $this->db->where('id', $existing->id)->update('zzshopeeorder', $header);
                } else {
                    $this->db->insert('zzshopeeorder', $header);
                }
                $jumlahOrder++;

                $this->db->where('order_sn', $o['order_sn'])->delete('zzshopeeorderdetail');
                $baris = array();
                foreach (($o['item_list'] ?? array()) as $it) {
                    $baris[] = array(
                        'order_sn'     => $o['order_sn'],
                        'item_id'      => $it['item_id'] ?? null,
                        'item_sku'     => $it['item_sku'] ?? '',
                        'item_name'    => mb_substr((string) ($it['item_name'] ?? ''), 0, 500),
                        'model_id'     => $it['model_id'] ?? null,
                        'model_sku'    => $it['model_sku'] ?? '',
                        'model_name'   => mb_substr((string) ($it['model_name'] ?? ''), 0, 255),
                        'qty'          => $it['model_quantity_purchased'] ?? 0,
                        'harga_asli'   => $it['model_original_price'] ?? 0,
                        'harga_diskon' => $it['model_discounted_price'] ?? 0,
                    );
                }
                if (!empty($baris)) {
                    $this->db->insert_batch('zzshopeeorderdetail', $baris);
                    $jumlahBaris += count($baris);
                }
            }

            $this->_userlog('Tarik Penjualan Shopee: ' . $jumlahOrder . ' order, ' . $tgldari . ' s/d ' . $tglsampai);
            echo json_encode(array('pesan' => 'sukses', 'jumlah_order' => $jumlahOrder, 'jumlah_baris' => $jumlahBaris));
        } catch (\Throwable $e) {
            echo json_encode(array('pesan' => 'error', 'error' => $e->getMessage()));
        }
    }

    function view_order_list()
    {
        $query = "SELECT id 'id', order_sn 'order_sn', order_status 'order_status',
                         DATE_FORMAT(create_time,'%d-%m-%Y %H:%i') 'create_time',
                         total_amount 'total_amount', buyer_username 'buyer_username',
                         recipient_name 'recipient_name', payment_method 'payment_method',
                         (SELECT COUNT(*) FROM zzshopeeorderdetail WHERE order_sn = zzshopeeorder.order_sn) 'jumlah_item',
                         DATE_FORMAT(ditarik_pada,'%d-%m-%Y %H:%i') 'ditarik_pada'
                    FROM zzshopeeorder";
        $search  = array('order_sn', 'buyer_username', 'recipient_name');
        $isOrder = 'create_time DESC';

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query, $search, null, "", $isOrder);
    }

    private function _userlog($aktivitas)
    {
        $this->db->insert('aauserlog', array(
            'ULUSER'     => $this->session->id,
            'ULUSERNAME' => $this->session->nama,
            'ULCOMPUTER' => $this->input->ip_address(),
            'ULACTIVITY' => $aktivitas,
            'ULLEVEL'    => 2,
        ));
    }

}
