<body id="<? echo $id; ?>" class="layout-fixed overflow-hidden" data-panel-auto-height-mode="height">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/table-page.css');?>">
  <link rel="stylesheet" href="<? echo base_url('assets/plugins/datepicker/datepicker3.css'); ?>">

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
  <div class="content-wrapper tab-wrap mx-0">
    <!-- Content Header (Page header) -->
    <div class="content-header bg-white px-4 py-2 position-fixed w-100">
      <div class="row">
      <div class="col-sm-11">
      <span class="text-md text-olive">Penjualan</span>
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
    <div class="content px-0 mx-0 ml-2" style="margin-top: 70px;">
      <div class="container-fluid mt-1 px-0 mx-0">

        <div class="card card-outline card-primary">
          <div class="card-body">
            <div class="row">
              <div class="col-md-3 col-6 mb-2">
                <label class="text-sm font-weight-normal mb-1">Dari Tanggal</label>
                <div class="input-group date">
                  <input id="tgldari" type="text" class="form-control form-control-sm datepicker" autocomplete="off">
                  <div id="dtgldari" class="input-group-append" role="button">
                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-6 mb-2">
                <label class="text-sm font-weight-normal mb-1">Sampai Tanggal</label>
                <div class="input-group date">
                  <input id="tglsampai" type="text" class="form-control form-control-sm datepicker" autocomplete="off">
                  <div id="dtglsampai" class="input-group-append" role="button">
                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-12 mb-2">
                <label class="text-sm font-weight-normal mb-1">&nbsp;</label>
                <button type="button" id="btarik" class="btn btn-primary btn-sm btn-block"><i class="fas fa-sync"></i> Tarik Penjualan dari Shopee</button>
              </div>
            </div>
            <small class="text-muted">
              Menarik order lewat API (order/get_order_list + get_order_detail) ke tabel staging
              <code>zzshopeeorder</code> / <code>zzshopeeorderdetail</code>. Rentang tanggal maksimal 15 hari sekali tarik
              (batas Shopee). Order yang sama akan diperbarui (ditimpa), bukan diduplikasi.
              Pemrosesan ke transaksi (fstoku) menyusul. Toko harus sudah terhubung lewat menu
              <b>Seting API Marketplace Shopee</b>.
            </small>
          </div>
        </div>

        <table id="order-table" class="table table-sm table-striped table-hover w-100 nowrap d-none">
          <thead>
          <tr>
          <th class="d-none"></th>
          <th></th>
          <th class="text-sm">Order SN</th>
          <th class="text-sm">Status</th>
          <th class="text-sm">Waktu Dibuat</th>
          <th class="text-sm text-right">Total</th>
          <th class="text-sm">Pembeli</th>
          <th class="text-sm">Penerima</th>
          <th class="text-sm">Metode Bayar</th>
          <th class="text-sm text-right">Jml Item</th>
          <th class="text-sm">Ditarik Pada</th>
          </tr>
          </thead>
        </table>
      </div>
    </div>
    <!-- /.Main content -->
  </div>

  <!-- Control Sidebar -->
  <div class="bg-white btn-group-vertical btn-top"></div>
  <div class="btn-group-vertical">
      <a id="brefresh" class="btn btn-app">
        <i class="fas fa-sync"></i> <span>Refresh</span>
      </a>
  </div>
  <aside id="control-sidebar-r" class="control-sidebar bg-transparent border-0"></aside>

<!-- JS Vendor -->
<script src="<? echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<? echo base_url('assets/dist/js/adminlte.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/select2/select2.full.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/toastr/toastr.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-select/js/dataTables.select.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-select/js/select.bootstrap4.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables/colResize.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
<!-- JS Custom -->
<script src="<? echo app_url('assets/dist/js/modul/transaksi/penjualan/shopee-api-penjualan.js'); ?>"></script>
</body>
</html>
