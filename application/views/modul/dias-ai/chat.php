<body id="<? echo $id; ?>" class="layout-fixed overflow-hidden" data-panel-auto-height-mode="height">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/table-page.css');?>">

  <!-- Loading Page -->
  <div class="loader-wrap d-none">
    <div class="loader">
      <div class="box-1 box"></div>
      <div class="box-2 box"></div>
      <div class="box-3 box"></div>
      <div class="box-4 box"></div>
      <div class="box-5 box"></div>
    </div>
  </div>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper mx-0">
    <!-- Content Header (Page header) -->
    <div class="content-header bg-white px-4 py-2 position-fixed w-100">
      <div class="row">
      <div class="col-sm-11">
      <span class="text-md text-olive">DIAS AI</span>
      <h5><?= $page_caption;?></h5>
      </div>
      <div id="btnsideright">
        <a class="nav-link text-lg" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-bars text-gray"></i>
        </a>
      </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content px-0 mx-0" style="margin-top: 70px;">
      <div class="container-fluid mt-1 px-3" style="max-width: 800px;">

        <small class="text-muted d-block mb-2">
          Minta data laporan pakai bahasa biasa, mis. <i>"data penjualan bulan ini dalam bentuk excel"</i>.
          DIAS AI cuma memilihkan dari laporan yang sudah ada &amp; yang Anda punya akses -- tidak menyusun query sendiri.
        </small>

        <div id="chat-box" class="card card-outline card-primary mb-2" style="height: calc(100vh - 220px); overflow-y: auto;">
          <div class="card-body" id="chat-isi"></div>
        </div>

        <div class="input-group">
          <input type="text" id="chat-input" class="form-control" placeholder="Tulis permintaan Anda..." autocomplete="off">
          <div class="input-group-append">
            <button type="button" id="chat-kirim" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim</button>
          </div>
        </div>

      </div>
    </div>
    <!-- /.Main content -->
  </div>

  <!-- Control Sidebar -->
  <div class="bg-white btn-group-vertical btn-top"></div>
  <aside id="control-sidebar-r" class="control-sidebar bg-transparent border-0"></aside>

  <!-- Form tersembunyi utk memicu download laporan (dibuka tab baru) -->
  <form id="form-download-laporan" method="post" target="_blank" action="<? echo base_url('Laporan/index'); ?>" class="d-none">
    <input type="hidden" name="id" id="fd-id">
    <input type="hidden" name="mode" value="3">
    <input type="hidden" name="tgldari" id="fd-tgldari">
    <input type="hidden" name="tglsampai" id="fd-tglsampai">
    <input type="hidden" name="tgl" id="fd-tgl">
    <input type="hidden" name="namapt" id="fd-namapt">
    <input type="hidden" name="gudang" id="fd-gudang">
    <input type="hidden" name="saldo" id="fd-saldo" value="0">
  </form>

<!-- JS Vendor -->
<script src="<? echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<? echo base_url('assets/dist/js/adminlte.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/toastr/toastr.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<!-- JS Custom -->
<script src="<? echo app_url('assets/dist/js/modul/dias-ai/chat.js'); ?>"></script>
</body>
</html>
