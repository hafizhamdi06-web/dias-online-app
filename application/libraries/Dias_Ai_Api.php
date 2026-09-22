<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Klien sederhana utk Anthropic Messages API (Claude), dipakai fitur
 * "DIAS AI" -- chat pemandu ke laporan yg SUDAH ADA (sistem aareport),
 * bukan chat umum bebas. AI cuma diminta memilih dari katalog laporan
 * yg dikirim di system prompt & mengisi parameter filternya (tanggal/
 * PT/cabang) -- tdk pernah diminta menyusun query SQL sendiri.
 * Dokumentasi resmi: https://docs.anthropic.com/en/api/messages
 *
 * Kredensial diambil dari tabel aaanthropicapi (baris AKTIF=1 pertama),
 * pola sama dgn Shopee_Api (lihat application/libraries/Shopee_Api.php).
 */
class Dias_Ai_Api {

    private $CI;
    private $setting; // row dari aaanthropicapi

    const HOST = 'https://api.anthropic.com';
    const API_VERSION = '2023-06-01';

    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->_muatSetting();
    }

    private function _muatSetting()
    {
        $this->setting = $this->CI->db->where('aktif', 1)->order_by('id', 'DESC')->limit(1)->get('aaanthropicapi')->row();
    }

    function isTerhubung()
    {
        return $this->setting && !empty($this->setting->api_key);
    }

    // Kirim satu system prompt + satu pesan user, kembalikan teks balasan mentah
    // (diharapkan berisi JSON sesuai skema yg diminta di system prompt).
    function kirim($systemPrompt, $pesanUser)
    {
        if (!$this->isTerhubung()) {
            throw new Exception('API key Anthropic belum diatur. Isi tabel aaanthropicapi (kolom api_key, aktif=1).');
        }

        $body = json_encode(array(
            'model'      => $this->setting->model,
            'max_tokens' => 1024,
            'system'     => $systemPrompt,
            'messages'   => array(
                array('role' => 'user', 'content' => (string) $pesanUser),
            ),
        ));

        $ch = curl_init(self::HOST . '/v1/messages');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'content-type: application/json',
            'x-api-key: ' . $this->setting->api_key,
            'anthropic-version: ' . self::API_VERSION,
        ));

        $response = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($errno) {
            throw new Exception('Koneksi ke Anthropic gagal: ' . $error);
        }

        $decoded = json_decode($response, true);
        if ($decoded === null) {
            throw new Exception('Response Anthropic tidak valid: ' . substr((string) $response, 0, 300));
        }
        if (!empty($decoded['error'])) {
            $pesan = isset($decoded['error']['message']) ? $decoded['error']['message'] : json_encode($decoded['error']);
            throw new Exception('Anthropic API error: ' . $pesan);
        }

        $teks = '';
        if (!empty($decoded['content']) && is_array($decoded['content'])) {
            foreach ($decoded['content'] as $blok) {
                if (isset($blok['type']) && $blok['type'] === 'text') {
                    $teks .= $blok['text'];
                }
            }
        }
        return $teks;
    }

}
