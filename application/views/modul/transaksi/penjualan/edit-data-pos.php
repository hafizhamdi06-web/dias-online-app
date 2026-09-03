<body id="<?= $id; ?>" class="layout-fixed overflow-hidden" data-panel-auto-height-mode="height">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/table-page.css');?>">
  <link rel="stylesheet" href="<? echo base_url('assets/plugins/datepicker/datepicker3.css'); ?>">

  <style>
    #tabeledit { table-layout: fixed; width: auto; }
    #tabeledit td, #tabeledit th { white-space: nowrap; vertical-align: middle; overflow: hidden; text-overflow: ellipsis; }
    #tabeledit input.inp-edit { width: 100%; text-align: right; }
    #tabeledit tr.row-berubah { background-color: #fff3cd; }
    #tabeledit tr.row-tersimpan { background-color: #d4edda; }
    .edp-wrap { height: calc(100vh - 240px); overflow: auto; }

    /* kolom bisa di-sort */
    #tabeledit th.th-sort { cursor: pointer; user-select: none; }
    #tabeledit th.th-sort .sort-ind { opacity: .3; margin-left: 2px; font-size: 10px; }
    #tabeledit th.sort-asc .sort-ind, #tabeledit th.sort-desc .sort-ind { opacity: 1; }

    /* kolom bisa diubah lebarnya */
    #tabeledit th { position: relative; }
    #tabeledit th .col-resizer {
      position: absolute; top: 0; right: 0; width: 6px; height: 100%;
      cursor: col-resize; user-select: none; z-index: 2;
    }
    #tabeledit th .col-resizer:hover { background: rgba(0,123,255,.35); }
    body.col-resizing, body.col-resizing * { cursor: col-resize !important; user-select: none !important; }
  </style>

  <div class="content-wrapper tab-wrap mx-0">
    <div class="content-header bg-white px-4 py-2 position-fixed w-100">
      <div class="row">
        <div class="col-sm-11">
          <span class="text-md text-olive">Penjualan</span>
          <h5><?= $page_caption; ?></h5>
        </div>
        <div id="btnsideright">
          <a class="nav-link text-lg" data-widget="control-sidebar" data-slide="true" href="#" role="button">
            <i class="fas fa-bars text-gray"></i>
          </a>
        </div>
      </div>
    </div>

    <div class="content px-3 mx-0" style="margin-top:70px;">
      <div class="container-fluid mt-1 px-0 mx-0">

        <div class="card card-outline card-primary">
          <div class="card-body py-2">
            <div class="row">
              <div class="col-md-2 col-6 mb-2">
                <label class="text-sm font-weight-normal mb-1">Dari Tanggal</label>
                <div class="input-group date">
                  <input id="tgldari" type="text" class="form-control form-control-sm datepicker" autocomplete="off">
                  <div id="dtgldari" class="input-group-append" role="button">
                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>
              </div>
              <div class="col-md-2 col-6 mb-2">
                <label class="text-sm font-weight-normal mb-1">Sampai Tanggal</label>
                <div class="input-group date">
                  <input id="tglsampai" type="text" class="form-control form-control-sm datepicker" autocomplete="off">
                  <div id="dtglsampai" class="input-group-append" role="button">
                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-8 mb-2">
                <label class="text-sm font-weight-normal mb-1">Cabang</label>
                <select id="cabang" class="form-control select2 form-control-sm" style="width:100%"></select>
              </div>
              <div class="col-md-3 col-12 mb-2">
                <label class="text-sm font-weight-normal mb-1">No Transaksi</label>
                <input id="fnotransaksi" type="text" class="form-control form-control-sm"
                       placeholder="cari sebagian / lengkap" autocomplete="off">
              </div>
              <div class="col-md-2 col-4 mb-2">
                <label class="text-sm font-weight-normal mb-1">&nbsp;</label>
                <button type="button" id="btampilkan" class="btn btn-primary btn-sm btn-block">Tampilkan</button>
              </div>
              <div class="col-md-3 col-12 mb-2">
                <label class="text-sm font-weight-normal mb-1">&nbsp;</label>
                <button type="button" id="bsimpansemua" class="btn btn-success btn-sm btn-block">
                  <i class="fas fa-save"></i> Simpan Semua Baris Berubah
                </button>
              </div>
            </div>
            <small class="text-muted d-block">
              *Hanya transaksi POS dengan pembayaran lewat merchant (sumerchantjumlah &gt; 0).
              Simpan menulis Harga (sdharga), Diskon Persen 1 (sddiskonpersen) &amp; Diskon Nilai (sddiskon) ke baris,
              lalu menghitung ulang total transaksi: sutotaltransaksi &amp; sumerchantjumlah = jumlah sub total
              seluruh baris, sutotaltada = jumlah sub total baris non-DP.
            </small>
          </div>
        </div>

        <div class="card card-outline card-secondary">
          <div class="card-body p-0">
            <div class="edp-wrap">
              <table id="tabeledit" class="table table-sm table-striped table-hover mb-0 nowrap">
                <thead class="bg-light">
                  <tr>
                    <th class="d-none"></th>
                    <th class="text-sm" style="width:24px"></th>
                    <th class="text-sm th-sort" data-sort="text" style="width:140px">No Transaksi <i class="fas fa-sort sort-ind"></i></th>
                    <th class="text-sm th-sort" data-sort="date" style="width:95px">Tanggal <i class="fas fa-sort sort-ind"></i></th>
                    <th class="text-sm th-sort" data-sort="text" style="width:190px">Pasien <i class="fas fa-sort sort-ind"></i></th>
                    <th class="text-sm th-sort" data-sort="text" style="width:110px">Kode Item <i class="fas fa-sort sort-ind"></i></th>
                    <th class="text-sm th-sort" data-sort="text" style="width:240px">Nama Item <i class="fas fa-sort sort-ind"></i></th>
                    <th class="text-sm text-right th-sort" data-sort="num" style="width:70px">Qty <i class="fas fa-sort sort-ind"></i></th>
                    <th class="text-sm text-right th-sort" data-sort="num" style="width:110px">Harga <i class="fas fa-sort sort-ind"></i></th>
                    <th class="text-sm text-right th-sort" data-sort="num" style="width:90px">Disk % 1 <i class="fas fa-sort sort-ind"></i></th>
                    <th class="text-sm text-right th-sort" data-sort="num" style="width:110px">Diskon Nilai <i class="fas fa-sort sort-ind"></i></th>
                    <th class="text-sm text-right th-sort" data-sort="num" style="width:120px">Sub Total <i class="fas fa-sort sort-ind"></i></th>
                    <th class="text-sm text-center" style="width:70px">Aksi</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="bg-white btn-group-vertical btn-top"></div>
  <aside id="control-sidebar-r" class="control-sidebar bg-transparent border-0"></aside>

  <input type="hidden" id="cabangdefault" value="<? echo @$_SESSION['cabang']; ?>">
  <input type="hidden" id="namacabangdefault" value="<? echo @$_SESSION['namagudang']; ?>">

<!-- JS Vendor -->
<script src="<? echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<? echo base_url('assets/dist/js/adminlte.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/select2/select2.full.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/toastr/toastr.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/input-mask/jquery.inputmask.bundle.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<!-- JS Custom -->
<script type="module" src="<? echo app_url('assets/dist/js/modul/transaksi/penjualan/edit-data-pos.js'); ?>"></script>
</body>
</html>
