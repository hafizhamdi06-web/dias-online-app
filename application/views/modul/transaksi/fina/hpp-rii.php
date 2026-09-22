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

        <div class="card card-outline card-warning">
          <div class="card-body">
            <div class="row align-items-end">
              <div class="col-md-3 col-6 mb-2">
                <label class="text-sm font-weight-normal mb-1">Proses dari Tanggal <span class="text-muted">(opsional)</span></label>
                <div class="input-group input-group-sm date">
                  <input id="tglawal" type="text" class="form-control form-control-sm datepicker" placeholder="Kosongkan = dari awal data" autocomplete="off">
                  <div id="dtglawal" class="input-group-append" role="button">
                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-6 mb-2">
                <label class="text-sm font-weight-normal mb-1">Proses s/d Tanggal</label>
                <div class="input-group input-group-sm date">
                  <input id="tglakhir" type="text" class="form-control form-control-sm datepicker" autocomplete="off">
                  <div id="dtglakhir" class="input-group-append" role="button">
                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-12 mb-2">
                <button type="button" id="bproses" class="btn btn-warning btn-sm"><i class="fas fa-sync"></i> Proses Ulang</button>
              </div>
              <div class="col-md-3 col-12 mb-2 text-right">
                <small class="text-muted d-block" id="info-proses">Belum pernah diproses.</small>
                <small id="status-proses"></small>
              </div>
            </div>
            <small class="text-muted">
              Khusus <b>PT. RII</b>. Metode <b>FIFO</b> per lapisan, tapi diagregat <b>per PT</b> (semua gudang di
              PT ini dianggap satu kolam stok yg sama) -- transaksi <b>TMB</b> (terima mutasi) dan <b>KMB</b>
              (kirim mutasi) tidak diproses sama sekali karena cuma perpindahan gudang di dalam PT yg sama.
              Hasil proses disimpan permanen; klik "Proses Ulang" kalau ada transaksi baru yg perlu ikut dihitung.
              Kalau <b>"Proses dari Tanggal"</b> diisi, antrian tiap item dimulai KOSONG persis di tanggal itu --
              stok yg "dibawa" dari sblm tanggal itu tidak ikut kehitung, jadi transaksi keluar tak lama setelah
              tanggal awal bisa tercatat HPP Rp 0 kalau belum ada transaksi masuk baru.
            </small>
          </div>
        </div>

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
              <div class="col-md-2 col-12 mb-2">
                <label class="text-sm font-weight-normal mb-1">&nbsp;</label>
                <button type="button" id="blihat" class="btn btn-primary btn-sm btn-block"><i class="fas fa-search"></i> Lihat Laporan</button>
              </div>
            </div>
          </div>
        </div>

        <table id="hpp-rii-table" class="table table-sm table-striped table-hover w-100 nowrap d-none">
          <thead>
          <tr>
          <th class="text-sm">Kode Item</th>
          <th class="text-sm">Nama Item</th>
          <th class="text-sm text-right">Qty Keluar</th>
          <th class="text-sm text-right">Total HPP</th>
          <th class="text-sm text-right">HPP Rata-rata</th>
          <th class="text-sm text-center">Rincian</th>
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

        <!-- Modal Rincian Transaksi -->
        <div class="modal fade" id="modal-detail-hpp-rii" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header py-2">
                <h6 class="modal-title" id="detail-hpp-rii-judul">Rincian Transaksi</h6>
                <button type="button" class="close" id="b-tutup-x-detail-hpp-rii"><span>&times;</span></button>
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
                  <tbody id="detail-hpp-rii-tbody"></tbody>
                </table>
              </div>
              <div class="modal-footer py-2">
                <button type="button" id="b-tutup-detail-hpp-rii" class="btn btn-primary btn-sm">OK</button>
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
<script src="<? echo base_url('assets/plugins/input-mask/jquery.inputmask.bundle.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/toastr/toastr.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<!-- JS Custom -->
<script src="<? echo app_url('assets/dist/js/modul/transaksi/fina/hpp-rii.js'); ?>"></script>
</body>
</html>
