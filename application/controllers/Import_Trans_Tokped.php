<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Import_Trans_Tokped extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if (!$this->session->has_userdata('nama')) {
            redirect(base_url('exception'));
        }
        $this->load->model('M_datatables');
    }

    // Header Excel export Tokopedia (OrderSKUList) -> nama kolom tabel zztemptranstokped (urut kolom Excel)
    private function _kolomMap()
    {
        return array(
            'Order ID'                       => 'order_id',
            'Order Status'                   => 'order_status',
            'Order Substatus'                => 'order_substatus',
            'Cancelation/Return Type'        => 'cancelation_return_type',
            'Normal or Pre-order'            => 'normal_or_preorder',
            'SKU ID'                         => 'sku_id',
            'Seller SKU'                     => 'seller_sku',
            'Product Name'                   => 'product_name',
            'Variation'                      => 'variation',
            'Quantity'                       => 'quantity',
            'Sku Quantity of return'         => 'sku_quantity_of_return',
            'SKU Unit Original Price'        => 'sku_unit_original_price',
            'SKU Subtotal Before Discount'   => 'sku_subtotal_before_discount',
            'SKU Platform Discount'          => 'sku_platform_discount',
            'SKU Seller Discount'            => 'sku_seller_discount',
            'SKU Subtotal After Discount'    => 'sku_subtotal_after_discount',
            'Shipping Fee After Discount'    => 'shipping_fee_after_discount',
            'Original Shipping Fee'          => 'original_shipping_fee',
            'Shipping Fee Seller Discount'   => 'shipping_fee_seller_discount',
            'Shipping Fee Platform Discount' => 'shipping_fee_platform_discount',
            'Distance Shipping Fee'          => 'distance_shipping_fee',
            'Distance Fee'                   => 'distance_fee',
            'Order Refund Amount'            => 'order_refund_amount',
            'Payment platform discount'      => 'payment_platform_discount',
            'Buyer Service Fee'              => 'buyer_service_fee',
            'Handling Fee'                   => 'handling_fee',
            'Shipping Insurance'             => 'shipping_insurance',
            'Item Insurance'                 => 'item_insurance',
            'Order Amount'                   => 'order_amount',
            'Created Time'                   => 'created_time',
            'Paid Time'                      => 'paid_time',
            'RTS Time'                       => 'rts_time',
            'Shipped Time'                   => 'shipped_time',
            'Delivered Time'                 => 'delivered_time',
            'Cancelled Time'                 => 'cancelled_time',
            'Cancel By'                      => 'cancel_by',
            'Cancel Reason'                  => 'cancel_reason',
            'Fulfillment Type'              => 'fulfillment_type',
            'Warehouse Name'                 => 'warehouse_name',
            'Tracking ID'                    => 'tracking_id',
            'Delivery Option'                => 'delivery_option',
            'Shipping Provider Name'         => 'shipping_provider_name',
            'Buyer Message'                  => 'buyer_message',
            'Buyer Username'                 => 'buyer_username',
            'Recipient'                      => 'recipient',
            'Phone #'                        => 'phone',
            'Zipcode'                        => 'zipcode',
            'Country'                        => 'country',
            'Province'                       => 'province',
            'Regency and City'              => 'regency_and_city',
            'Districts'                      => 'districts',
            'Villages'                       => 'villages',
            'Detail Address'                 => 'detail_address',
            'Additional address information' => 'additional_address_information',
            'Payment Method'                 => 'payment_method',
            'Weight(kg)'                     => 'weight_kg',
            'Product Category'               => 'product_category',
            'Package ID'                     => 'package_id',
            'Purchase Channel'               => 'purchase_channel',
            'Seller Note'                    => 'seller_note',
            'Checked Status'                 => 'checked_status',
            'Checked Marked by'              => 'checked_marked_by',
            'Tokopedia Invoice Number'       => 'tokopedia_invoice_number',
            'Order Channel'                  => 'order_channel',
            'Creator Handle'                 => 'creator_handle',
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
            $rows = $this->xlsx_reader->read($_FILES['file']['tmp_name'], 'OrderSKUList');
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
            if (in_array('Order ID', $normalized, true) && in_array('Product Name', $normalized, true)) {
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
            echo json_encode(array('pesan' => 'error', 'error' => 'Baris header (Order ID, Product Name) tidak ditemukan di sheet "OrderSKUList".'));
            return;
        }

        $orderIdIdx = array_search('order_id', $colToField, true);
        if ($orderIdIdx === false) {
            echo json_encode(array('pesan' => 'error', 'error' => 'Kolom "Order ID" tidak ditemukan di file.'));
            return;
        }

        $dataRows = array();
        $orderIds = array();
        for ($i = $headerIdx + 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $oid = isset($row[$orderIdIdx]) ? trim((string) $row[$orderIdIdx]) : '';
            // Lewati baris keterangan kolom & baris kosong: Order ID Tokopedia selalu angka
            if ($oid === '' || !ctype_digit($oid)) {
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
            $orderIds[$oid] = true;
        }

        if (empty($dataRows)) {
            echo json_encode(array('pesan' => 'error', 'error' => 'Tidak ada baris data di file.'));
            return;
        }

        $this->db->trans_start();

        // Hapus data lama untuk Order ID yang diimport (idempotent)
        $this->db->where_in('order_id', array_keys($orderIds));
        $this->db->delete('zztemptranstokped');

        foreach (array_chunk($dataRows, 200) as $chunk) {
            $this->db->insert_batch('zztemptranstokped', $chunk);
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
        $query = "SELECT A.id 'id', A.order_id 'order_id', A.buyer_username 'buyer_username',
                         A.created_time 'created_time', A.order_status 'order_status',
                         A.product_name 'product_name', A.quantity 'quantity', A.order_amount 'order_amount',
                         COALESCE(B.unamalengkap,B.unama) 'diimport_oleh',
                         DATE_FORMAT(A.diimport_pada,'%d-%m-%Y %H:%i') 'diimport_pada'
                    FROM zztemptranstokped A
               LEFT JOIN auser B ON A.diimport_oleh=B.uid";
        $search = array('A.order_id', 'A.buyer_username', 'A.product_name');
        $where = null;
        $isWhere = "";
        $isOrder = 'A.id DESC';

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere, $isOrder);
    }

}
