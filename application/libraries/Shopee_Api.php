<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Klien untuk Shopee Open Platform API v2 (partner API).
 * Dokumentasi resmi: https://open.shopee.com/documents
 *
 * Pemakaian:
 *   $this->load->library('Shopee_Api');
 *   $list = $this->shopee_api->getItemList();
 *
 * Kredensial & token diambil/disimpan otomatis dari/ke tabel aashopeeapi
 * (baris AKTIF=1 pertama). Token akses (access_token, berlaku ~4 jam)
 * di-refresh otomatis pakai refresh_token (berlaku ~30 hari) sebelum
 * tiap panggilan API yang butuh otorisasi.
 */
class Shopee_Api {

    private $CI;
    private $setting;   // row dari aashopeeapi
    private $baseUrl;

    const HOST_LIVE    = 'https://partner.shopeemobile.com';
    const HOST_SANDBOX = 'https://partner.test-stable.shopeemobile.com';

    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->_muatSetting();
    }

    private function _muatSetting()
    {
        $row = $this->CI->db->where('aktif', 1)->order_by('id', 'DESC')->limit(1)->get('aashopeeapi')->row();
        $this->setting = $row;
        $this->baseUrl = ($row && $row->environment === 'sandbox') ? self::HOST_SANDBOX : self::HOST_LIVE;
    }

    function isTerhubung()
    {
        return $this->setting
            && !empty($this->setting->partner_id)
            && !empty($this->setting->partner_key)
            && !empty($this->setting->shop_id)
            && !empty($this->setting->access_token);
    }

    function getSetting()
    {
        return $this->setting;
    }

    // ---------------------------------------------------------------
    // Tanda tangan (sign) HMAC-SHA256 sesuai spek Shopee Open API v2
    // ---------------------------------------------------------------
    private function _sign($baseString)
    {
        return hash_hmac('sha256', $baseString, (string) $this->setting->partner_key);
    }

    // sign publik (belum ada access_token): partner_id + path + timestamp
    private function _signPublik($path, $timestamp)
    {
        return $this->_sign($this->setting->partner_id . $path . $timestamp);
    }

    // sign endpoint toko (butuh access_token + shop_id)
    private function _signToko($path, $timestamp)
    {
        return $this->_sign($this->setting->partner_id . $path . $timestamp . $this->setting->access_token . $this->setting->shop_id);
    }

    // ---------------------------------------------------------------
    // Otorisasi (OAuth)
    // ---------------------------------------------------------------

    // URL utk dibuka user (login & authorize toko Shopee-nya)
    function getAuthUrl()
    {
        $path      = '/api/v2/shop/auth_partner';
        $timestamp = time();
        $sign      = $this->_signPublik($path, $timestamp);

        $query = http_build_query(array(
            'partner_id' => $this->setting->partner_id,
            'timestamp'  => $timestamp,
            'sign'       => $sign,
            'redirect'   => $this->setting->redirect_uri,
        ));

        return $this->baseUrl . $path . '?' . $query;
    }

    // Tukar "code" hasil redirect callback Shopee -> access_token/refresh_token, simpan ke DB
    function tukarKodeToken($code, $shopId)
    {
        $path      = '/api/v2/auth/token/get';
        $timestamp = time();
        $sign      = $this->_signPublik($path, $timestamp);

        $url = $this->baseUrl . $path . '?' . http_build_query(array(
            'partner_id' => (int) $this->setting->partner_id,
            'timestamp'  => $timestamp,
            'sign'       => $sign,
        ));

        $body = json_encode(array(
            'code'       => $code,
            'shop_id'    => (int) $shopId,
            'partner_id' => (int) $this->setting->partner_id,
        ));

        $result = $this->_curlJson($url, 'POST', $body);

        if (empty($result['access_token'])) {
            throw new Exception('Gagal menukar code Shopee: ' . ($result['message'] ?? json_encode($result)));
        }

        $this->_simpanToken($shopId, $result['access_token'], $result['refresh_token'], $result['expire_in']);
        return $result;
    }

    // Refresh access_token pakai refresh_token yang tersimpan
    function refreshToken()
    {
        if (empty($this->setting->refresh_token)) {
            throw new Exception('Belum ada refresh_token tersimpan. Lakukan otorisasi ulang.');
        }

        $path      = '/api/v2/auth/access_token/get';
        $timestamp = time();
        $sign      = $this->_signPublik($path, $timestamp);

        $url = $this->baseUrl . $path . '?' . http_build_query(array(
            'partner_id' => (int) $this->setting->partner_id,
            'timestamp'  => $timestamp,
            'sign'       => $sign,
        ));

        $body = json_encode(array(
            'refresh_token' => $this->setting->refresh_token,
            'shop_id'       => (int) $this->setting->shop_id,
            'partner_id'    => (int) $this->setting->partner_id,
        ));

        $result = $this->_curlJson($url, 'POST', $body);

        if (empty($result['access_token'])) {
            throw new Exception('Gagal refresh token Shopee: ' . ($result['message'] ?? json_encode($result)));
        }

        $this->_simpanToken($this->setting->shop_id, $result['access_token'], $result['refresh_token'], $result['expire_in']);
        return $result;
    }

    // Refresh otomatis kalau access_token sudah/hampir kadaluarsa (margin 5 menit)
    private function _pastikanToken()
    {
        if (!$this->setting || empty($this->setting->access_token)) {
            throw new Exception('Shopee belum terhubung. Buka menu "Seting API Marketplace Shopee" dan lakukan otorisasi dulu.');
        }

        $expire = $this->setting->access_token_expire ? strtotime($this->setting->access_token_expire) : 0;
        if ($expire - time() < 300) {
            $this->refreshToken();
        }
    }

    private function _simpanToken($shopId, $accessToken, $refreshToken, $expireIn)
    {
        $data = array(
            'shop_id'              => $shopId,
            'access_token'         => $accessToken,
            'refresh_token'        => $refreshToken,
            'access_token_expire'  => date('Y-m-d H:i:s', time() + (int) $expireIn),
            'refresh_token_expire' => date('Y-m-d H:i:s', time() + (30 * 86400)),
        );
        $this->CI->db->where('id', $this->setting->id)->update('aashopeeapi', $data);
        $this->_muatSetting();
    }

    // ---------------------------------------------------------------
    // Panggilan API toko (butuh access_token)
    // ---------------------------------------------------------------

    private function _call($path, $method = 'GET', $params = array(), $body = null)
    {
        $this->_pastikanToken();

        $timestamp = time();
        $sign      = $this->_signToko($path, $timestamp);

        $common = array(
            'partner_id'  => (int) $this->setting->partner_id,
            'timestamp'   => $timestamp,
            'access_token'=> $this->setting->access_token,
            'shop_id'     => (int) $this->setting->shop_id,
            'sign'        => $sign,
        );

        $url = $this->baseUrl . $path . '?' . http_build_query(array_merge($common, $params));
        $jsonBody = ($body !== null) ? json_encode($body) : null;

        return $this->_curlJson($url, $method, $jsonBody);
    }

    private function _curlJson($url, $method = 'GET', $jsonBody = null)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody ?: '{}');
        }

        $response = curl_exec($ch);
        $errno    = curl_errno($ch);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($errno) {
            throw new Exception('Koneksi ke Shopee gagal: ' . $error);
        }

        $decoded = json_decode($response, true);
        if ($decoded === null) {
            throw new Exception('Response Shopee tidak valid: ' . substr((string) $response, 0, 300));
        }
        if (!empty($decoded['error'])) {
            throw new Exception('Shopee API error [' . $decoded['error'] . ']: ' . ($decoded['message'] ?? '-'));
        }

        return $decoded;
    }

    // ---------------------------------------------------------------
    // Produk
    // ---------------------------------------------------------------

    // product/get_item_list - daftar item_id milik toko
    function getItemList($offset = 0, $pageSize = 50, $itemStatus = 'NORMAL')
    {
        return $this->_call('/api/v2/product/get_item_list', 'GET', array(
            'offset'      => $offset,
            'page_size'   => $pageSize,
            'item_status' => $itemStatus,
        ));
    }

    // product/get_item_base_info - detail (nama, sku, harga, stok) utk maks 50 item_id sekaligus
    function getItemBaseInfo(array $itemIds)
    {
        return $this->_call('/api/v2/product/get_item_base_info', 'GET', array(
            'item_id_list'          => implode(',', $itemIds),
            'need_tax_info'         => 'false',
            'need_complaint_policy' => 'false',
        ));
    }

    // Tarik SEMUA produk toko (list + base_info, otomatis paging), kembalikan array item mentah Shopee
    function tarikSemuaProduk()
    {
        $semuaId = array();
        $offset  = 0;
        do {
            $res = $this->getItemList($offset, 100, 'NORMAL');
            $list = $res['response']['item'] ?? array();
            foreach ($list as $it) $semuaId[] = $it['item_id'];
            $hasNext = !empty($res['response']['has_next_page']);
            $offset  = $res['response']['next_offset'] ?? ($offset + 100);
        } while ($hasNext);

        $items = array();
        foreach (array_chunk($semuaId, 50) as $chunk) {
            $res = $this->getItemBaseInfo($chunk);
            foreach (($res['response']['item_list'] ?? array()) as $it) {
                $items[] = $it;
            }
        }
        return $items;
    }

    // ---------------------------------------------------------------
    // Order / Penjualan
    // ---------------------------------------------------------------

    // order/get_order_list - daftar order_sn dalam rentang waktu (maks 15 hari per panggilan)
    function getOrderList($timeFrom, $timeTo, $cursor = '', $pageSize = 50, $timeRangeField = 'create_time')
    {
        return $this->_call('/api/v2/order/get_order_list', 'GET', array(
            'time_range_field' => $timeRangeField,
            'time_from'        => $timeFrom,
            'time_to'          => $timeTo,
            'page_size'        => $pageSize,
            'cursor'           => $cursor,
        ));
    }

    // order/get_order_detail - detail lengkap (item, pembeli, ongkir) utk maks 50 order_sn sekaligus
    function getOrderDetail(array $orderSns)
    {
        return $this->_call('/api/v2/order/get_order_detail', 'GET', array(
            'order_sn_list'           => implode(',', $orderSns),
            'response_optional_fields'=> 'buyer_user_id,buyer_username,recipient_address,item_list,payment_method,total_amount,note,shipping_carrier,order_status',
        ));
    }

    // Tarik semua order dalam rentang tanggal (unix timestamp), otomatis paging + detail
    function tarikSemuaOrder($timeFrom, $timeTo)
    {
        $semuaSn = array();
        $cursor  = '';
        do {
            $res  = $this->getOrderList($timeFrom, $timeTo, $cursor);
            $list = $res['response']['order_list'] ?? array();
            foreach ($list as $o) $semuaSn[] = $o['order_sn'];
            $more   = !empty($res['response']['more']);
            $cursor = $res['response']['next_cursor'] ?? '';
        } while ($more && $cursor !== '');

        $orders = array();
        foreach (array_chunk($semuaSn, 50) as $chunk) {
            $res = $this->getOrderDetail($chunk);
            foreach (($res['response']['order_list'] ?? array()) as $o) {
                $orders[] = $o;
            }
        }
        return $orders;
    }

}
