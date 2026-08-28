<body id="<?= $id; ?>" class="layout-fixed overflow-hidden" data-panel-auto-height-mode="height">
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
      <span class="text-md text-olive">Pembelian</span>
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

    <div class="table-utils d-none">
      <button id="bfilter" type="button" class="btn btn-light btn-sm" style="text-shadow: none;">
        <i class="fas fa-filter text-sm text-primary"></i> Filter Data
      </button>
    </div>

    <!-- Main content -->
    <div class="content px-0 mx-0 ml-2" style="margin-top: 70px;">
      <div class="container-fluid mt-1 px-0 mx-0">
        <table id="table" class="table table-sm table-striped table-hover w-100 nowrap d-none">
          <thead>
          <tr>
          <th class="d-none"></th>
          <th></th>
          <th class="text-sm">No Transaksi</th>
          <th class="text-sm">Tanggal</th>
          <th class="text-sm">Nama Karyawan</th>
          <th class="text-sm">Keterangan</th>
          <th class="text-sm">Tujuan</th>
          <th class="text-sm text-center">Verifikasi</th>
          <th class="text-sm">Status</th>
          <th class="text-sm">User Verifikasi</th>
          <th class="text-sm">Jenis</th>
          <th class="text-sm">Cabang/Supplier Tujuan</th>
          <th class="text-sm">Catatan Verifikasi</th>
          </tr>
          </thead>
        </table>
        <div id="fDataTable" class="fDataTable d-none">
          <div class="row mt-2 mx-1">
              <div class="col-sm-12">
                <label class="col-form-label text-sm font-weight-normal">Karyawan :</label>
                <div class="input-group" data-target-input="nearest">
                  <input type="hidden" name="idkontak" id="idkontak">
                  <input type="text" id="kontak" name="kontak" class="form-control form-control-sm" autocomplete="off">
                  <div id="bfilterkontak" class="input-group-append" role="button">
                      <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                  </div>
                </div>
              </div>
          </div>
          <div class="row mt-2 mx-1">
              <div class="col-sm-12">
                <label class="col-form-label text-sm font-weight-normal">Tanggal Transaksi :</label>
                <div class="input-group date">
                  <input id="tgldari" type="text" class="form-control form-control-sm datepicker">
                  <div id="dtgldari" class="input-group-append" role="button">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>
              </div>
          </div>
          <div class="row mt-0 pt-0 mx-1">
              <div class="col-sm-12">
                <label class="col-form-label text-sm font-weight-normal">s/d :</label>
                <div class="input-group date">
                  <input id="tglsampai" type="text" class="form-control form-control-sm datepicker">
                  <div id="dtglsampai" class="input-group-append" role="button">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>
              </div>
          </div>
          <div class="row mt-2 mx-1">
              <div class="col-sm-12">
                <label class="col-form-label text-sm font-weight-normal">Cabang :</label>
                <div class="input-group" data-target-input="nearest">
                  <select id="cabang" name="cabang" class="form-control select2 form-control-sm" style="width:100%" data-trigger="manual" data-placement="auto"></select>
                </div>
              </div>
          </div>
          <div class="row mt-2 mx-1">
              <div class="col-sm-12">
                <label class="col-form-label text-sm font-weight-normal">Jenis :</label>
                <div class="input-group" data-target-input="nearest">
                  <input type="hidden" id="tujuan" name="tujuan">
                  <input type="text" id="tujuannama" name="tujuannama" class="form-control form-control-sm" autocomplete="off">
                  <div id="carijenis" class="input-group-append" role="button">
                      <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                  </div>
                </div>
              </div>
          </div>
          <div class="row mt-2 mx-1">
              <div class="col-sm-12">
                <label class="col-form-label text-sm font-weight-normal">Status :</label>
                <div class="input-group" data-target-input="nearest">
                  <select id="status" name="status" class="form-control select2 form-control-sm" style="width:100%" data-trigger="manual" data-placement="auto">
                    <option value="">Semua</option>
                    <option value="0">Belum Verifikasi</option>
                    <option value="1">Pending</option>
                    <option value="2">Verifikasi Finance</option>
                    <option value="3">Perintah Kirim</option>
                    <option value="4">Sedang DiKirim</option>
                    <option value="5">Progress Diterima Cabang</option>
                    <option value="6">Selesai Diterima Cabang</option>
                    <option value="7">Konfirmasi Bag Pembelian</option>
                  </select>
                </div>
              </div>
          </div>
          <div class="row ml-3 mt-4 pt-0">
            <button type="button" id="submitfilter" class="btn btn-primary btn-sm">Tampilkan</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Main content -->
  </div>

  <!-- Control Sidebar -->
  <div class="bg-white btn-group-vertical btn-top">
  </div>
  <div class="btn-group-vertical">
      <a id="bedit" class="btn btn-app">
        <i class="fas fa-edit"></i> <span>Edit</span>
      </a>
      <a id="brefresh" class="btn btn-app">
        <i class="fas fa-sync"></i> <span>Refresh</span>
      </a>
  </div>
  <aside id="control-sidebar-r" class="control-sidebar bg-transparent border-0">
  </aside>
  </form>
  <!-- /.control-sidebar -->

<!-- JS Vendor -->
<script src="<? echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<? echo base_url('assets/dist/js/adminlte.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/select2/select2.full.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-select/js/dataTables.select.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-select/js/select.bootstrap4.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/input-mask/jquery.inputmask.bundle.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables/colResize.js'); ?>"></script>
<!-- JS Custom -->
<script type="module" src="<? echo app_url('assets/dist/js/modul/transaksi/pembelian/table-verifikasi-permintaan-barang.js'); ?>"></script>
</body>
</html>
