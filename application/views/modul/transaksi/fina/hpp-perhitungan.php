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
  <div class="content-wrapper tab-wrap mx-0">
    <!-- Content Header (Page header) -->
    <div class="content-header bg-white px-4 py-2 position-fixed w-100">
      <div class="row">
      <div class="col-sm-11">
      <span class="text-md text-olive">Fina</span>
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
              <div class="col-md-2 col-6 mb-2">
                <label class="text-sm font-weight-normal mb-1">Bulan</label>
                <select id="bulan" class="form-control form-control-sm">
                  <option value="1">Januari</option>
                  <option value="2">Februari</option>
                  <option value="3">Maret</option>
                  <option value="4">April</option>
                  <option value="5">Mei</option>
                  <option value="6">Juni</option>
                  <option value="7">Juli</option>
                  <option value="8">Agustus</option>
                  <option value="9">September</option>
                  <option value="10">Oktober</option>
                  <option value="11">November</option>
                  <option value="12">Desember</option>
                </select>
              </div>
              <div class="col-md-2 col-6 mb-2">
                <label class="text-sm font-weight-normal mb-1">Tahun</label>
                <input id="tahun" type="text" class="form-control form-control-sm" autocomplete="off">
              </div>
              <div class="col-md-3 col-12 mb-2">
                <label class="text-sm font-weight-normal mb-1">PT</label>
                <select id="pt" class="form-control select2 form-control-sm" style="width:100%"></select>
              </div>
              <div class="col-md-3 col-12 mb-2">
                <label class="text-sm font-weight-normal mb-1">Cabang</label>
                <select id="cabang" class="form-control select2 form-control-sm" style="width:100%" disabled></select>
              </div>
              <div class="col-md-2 col-12 mb-2">
                <label class="text-sm font-weight-normal mb-1">&nbsp;</label>
                <button type="button" id="bhitung" class="btn btn-primary btn-sm btn-block"><i class="fas fa-calculator"></i> Hitung</button>
              </div>
            </div>
            <small class="text-muted">
              Metode <b>FIFO</b> (First In First Out) per Cabang: lapisan stok masuk (pembelian, mutasi masuk,
              penyesuaian, dsb) dipakai lebih dulu berdasarkan urutan tanggal masuknya. HPP dihitung dari baris
              penjualan (POS, Faktur Penjualan/Surat Jalan, Retur Penjualan) yang jatuh pada bulan yang dipilih.
              Kalau harga satuan suatu baris stok masuk kosong/0, dipakai HPP master item (ICOGS) sebagai cadangan.
            </small>
          </div>
        </div>

        <table id="hpp-table" class="table table-sm table-striped table-hover w-100 nowrap d-none">
          <thead>
          <tr>
          <th class="text-sm">Kode Item</th>
          <th class="text-sm">Nama Item</th>
          <th class="text-sm text-right">Qty Terjual</th>
          <th class="text-sm text-right">Total HPP (FIFO)</th>
          <th class="text-sm text-right">HPP Rata-rata</th>
          <th class="text-sm text-center">Sumber</th>
          </tr>
          </thead>
          <tfoot>
          <tr class="bg-light font-weight-bold">
          <th class="text-sm" colspan="2">Total</th>
          <th class="text-sm text-right" id="foot-qty">0</th>
          <th class="text-sm text-right" id="foot-hpp">0</th>
          <th class="text-sm text-right"></th>
          <th class="text-sm"></th>
          </tr>
          </tfoot>
        </table>

        <!-- Modal Rincian Sumber HPP -->
        <div class="modal fade" id="modal-detail-hpp" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header py-2">
                <h6 class="modal-title" id="detail-hpp-judul">Rincian Sumber HPP</h6>
                <button type="button" class="close" id="b-tutup-x-detail-hpp"><span>&times;</span></button>
              </div>
              <div class="modal-body p-0">
                <table class="table table-sm table-striped mb-0">
                  <thead>
                  <tr class="bg-light">
                    <th></th>
                    <th class="text-sm">No Transaksi</th>
                    <th class="text-sm">Tanggal</th>
                    <th class="text-sm">Sumber</th>
                    <th class="text-sm text-right">Qty</th>
                    <th class="text-sm text-right">HPP Satuan</th>
                    <th class="text-sm text-right">HPP Baris</th>
                  </tr>
                  </thead>
                  <tbody id="detail-hpp-tbody"></tbody>
                </table>
              </div>
              <div class="modal-footer py-2">
                <button type="button" id="b-tutup-detail-hpp" class="btn btn-primary btn-sm">OK</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Main content -->
  </div>

  <!-- Control Sidebar -->
  <div class="bg-white btn-group-vertical btn-top"></div>
  <aside id="control-sidebar-r" class="control-sidebar bg-transparent border-0"></aside>

<!-- JS Vendor -->
<script src="<? echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<? echo base_url('assets/dist/js/adminlte.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/select2/select2.full.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/toastr/toastr.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<!-- JS Custom -->
<script src="<? echo app_url('assets/dist/js/modul/transaksi/fina/hpp-perhitungan.js'); ?>"></script>
</body>
</html>
