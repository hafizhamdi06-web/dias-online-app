<body id="<?= $id; ?>" class="pm-body">
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/persetujuan-mobile.css');?>">

  <div id="loader" class="pm-loader d-none">
    <i class="fas fa-circle-notch fa-spin"></i>
  </div>

  <div class="pm-header">
    <h5 class="pm-title">Permintaan Persetujuan</h5>
    <button type="button" id="brefresh" class="pm-refresh-btn">
      <i class="fas fa-sync"></i>
    </button>
  </div>

  <div id="pm-list" class="pm-list"></div>

  <div id="pm-empty" class="pm-empty d-none">
    <i class="fas fa-check-circle"></i>
    <p>Tidak ada permintaan yang perlu direspon.</p>
  </div>

<!-- JS Vendor (minimal, halaman ini dibuka langsung dari HP tanpa lewat dashboard) -->
<script src="<? echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/toastr/toastr.min.js'); ?>"></script>
<!-- JS Custom -->
<script type="module" src="<? echo app_url('assets/dist/js/modul/transaksi/penjualan/table-persetujuan.js'); ?>"></script>
</body>
</html>
