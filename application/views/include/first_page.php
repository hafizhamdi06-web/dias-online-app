<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<body id="first-page" class="layout-fixed border-0 bg-white" data-panel-auto-height-mode="height" style="overflow-x: hidden;">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/grafik-penjualan.css');?>">
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/first_page.css');?>">

  <div class="content-wrapper bg-white">
    <?
      if($dasbor_msg!==''){
      }
    ?>
    <div class="px-3 pt-3">
      <div id="greeting-banner" class="callout callout-info bg-white mb-3">
        <h5 class="mb-1"><i class="fas fa-hand-sparkles text-info"></i> Selamat datang, <span id="greeting-nama">-</span>, di DIAS ONLINE.</h5>
        <p class="mb-0 text-sm">Anda aktif sebagai user di cabang <b id="greeting-cabang">-</b>. Berikut rekapan aktivitas Anda:</p>
      </div>

      <div id="grup-fstoku">
        <h6 class="text-muted mb-2">Transaksi Stok <small>(Bulan Ini: <span id="rekap-namabulanini">-</span> &middot; Bulan Lalu: <span id="rekap-namabulanlalu">-</span>)</small></h6>
        <div class="row" id="rekap-fstoku">
          <div class="col-12 text-muted text-sm">Memuat...</div>
        </div>
      </div>

      <div id="grup-permintaan">
        <h6 class="text-muted mb-2 mt-2">Permintaan Barang</h6>
        <div class="row" id="rekap-permintaan">
          <div class="col-12 text-muted text-sm">Memuat...</div>
        </div>
      </div>

      <div id="grup-salesorder">
        <h6 class="text-muted mb-2 mt-2">Sales Order</h6>
        <div class="row" id="rekap-salesorder">
          <div class="col-12 text-muted text-sm">Memuat...</div>
        </div>
      </div>

      <div id="grup-invoice">
        <h6 class="text-muted mb-2 mt-2">Invoice Penjualan</h6>
        <div class="row" id="rekap-invoice">
          <div class="col-12 text-muted text-sm">Memuat...</div>
        </div>
      </div>

      <div id="grup-kaskecil">
        <h6 class="text-muted mb-2 mt-2">Transaksi Kas Kecil</h6>
        <div class="row" id="rekap-kaskecil">
          <div class="col-12 text-muted text-sm">Memuat...</div>
        </div>
      </div>

      <div id="grup-piutang">
        <h6 class="text-muted mb-2 mt-2">Transaksi Piutang</h6>
        <div class="row" id="rekap-piutang">
          <div class="col-12 text-muted text-sm">Memuat...</div>
        </div>
      </div>
    </div>
  </div>
  <script defer src="<? echo app_url('assets/dist/js/modul/first_page.js'); ?>"></script>