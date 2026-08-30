<body id="<?= $id; ?>" class="layout-fixed overflow-hidden" data-panel-auto-height-mode="height">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/table-page.css');?>">
  <link rel="stylesheet" href="<? echo base_url('assets/plugins/datepicker/datepicker3.css'); ?>">
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/grafik-penjualan.css');?>">

  <div class="content-wrapper tab-wrap mx-0">
    <div class="content-header bg-white px-4 py-2 position-fixed w-100">
      <div class="row">
      <div class="col-sm-11">
      <span class="text-md text-olive">Laporan</span>
      <h5><?= $page_caption;?></h5>
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

        <div class="card card-outline card-primary gp-filter-card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-2 col-6 mb-2">
                <label class="text-sm font-weight-normal mb-1">Dari Tanggal</label>
                <div class="input-group date">
                  <input id="tgldari" type="text" class="form-control form-control-sm datepicker">
                  <div id="dtgldari" class="input-group-append" role="button">
                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>
              </div>
              <div class="col-md-2 col-6 mb-2">
                <label class="text-sm font-weight-normal mb-1">Sampai Tanggal</label>
                <div class="input-group date">
                  <input id="tglsampai" type="text" class="form-control form-control-sm datepicker">
                  <div id="dtglsampai" class="input-group-append" role="button">
                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-8 mb-2">
                <label class="text-sm font-weight-normal mb-1">Cabang</label>
                <select id="cabang" class="form-control select2 form-control-sm" style="width:100%"></select>
              </div>
              <div class="col-md-2 col-4 mb-2">
                <label class="text-sm font-weight-normal mb-1">&nbsp;</label>
                <button type="button" id="btampilkan" class="btn btn-primary btn-sm btn-block">Tampilkan</button>
              </div>
              <div class="col-md-2 col-4 mb-2">
                <label class="text-sm font-weight-normal mb-1">&nbsp;</label>
                <button type="button" id="bexportpdf" class="btn btn-danger btn-sm btn-block gp-btn-pdf">
                  <i class="fas fa-file-pdf"></i> PDF
                </button>
              </div>
            </div>
          </div>
        </div>

        <div id="print-summary" class="gp-print-only"></div>

        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="gp-ringkasan-box gp-ringkasan-success">
              <span class="gp-ringkasan-icon"><i class="fas fa-money-bill-wave"></i></span>
              <div class="gp-ringkasan-text">
                <span class="gp-ringkasan-label">Omzet Hari Ini</span>
                <span class="gp-ringkasan-number" id="ib-omzet-hari-ini">0</span>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="gp-ringkasan-box gp-ringkasan-primary">
              <span class="gp-ringkasan-icon"><i class="fas fa-wallet"></i></span>
              <div class="gp-ringkasan-text">
                <span class="gp-ringkasan-label">Omzet Bulan Ini</span>
                <span class="gp-ringkasan-number" id="ib-omzet-bulan-ini">0</span>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="gp-ringkasan-box gp-ringkasan-warning">
              <span class="gp-ringkasan-icon"><i class="fas fa-user-check"></i></span>
              <div class="gp-ringkasan-text">
                <span class="gp-ringkasan-label">Pasien Hari Ini</span>
                <span class="gp-ringkasan-number" id="ib-pasien-hari-ini">0</span>
                <small class="gp-ringkasan-note">*1 pasien dihitung 1x per hari</small>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="gp-ringkasan-box gp-ringkasan-info">
              <span class="gp-ringkasan-icon"><i class="fas fa-users"></i></span>
              <div class="gp-ringkasan-text">
                <span class="gp-ringkasan-label">Pasien Bulan Ini</span>
                <span class="gp-ringkasan-number" id="ib-pasien-bulan-ini">0</span>
                <small class="gp-ringkasan-note">*1 pasien dihitung 1x per hari</small>
              </div>
            </div>
          </div>
        </div>

        <div class="card card-outline card-success">
          <div class="card-header">
            <h3 class="card-title text-sm">Omzet per Bulan</h3>
          </div>
          <div class="card-body">
            <canvas id="chart-omzet" height="90"></canvas>
          </div>
        </div>

        <div class="card card-outline card-info d-none">
          <div class="card-header">
            <h3 class="card-title text-sm">Jumlah Transaksi per Bulan</h3>
          </div>
          <div class="card-body">
            <canvas id="chart-transaksi" height="90"></canvas>
          </div>
        </div>

        <div class="card card-outline card-warning">
          <div class="card-header">
            <h3 class="card-title text-sm">Jumlah Pasien per Bulan</h3>
            <small class="text-muted d-block">*1 pasien dihitung 1x per hari, meski transaksi berkali-kali</small>
          </div>
          <div class="card-body">
            <canvas id="chart-pasien" height="90"></canvas>
          </div>
        </div>

      </div>
    </div>
  </div>

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
<script src="<? echo base_url('assets/plugins/chart.js/Chart.min.js'); ?>"></script>
<!-- JS Custom -->
<script type="module" src="<? echo app_url('assets/dist/js/modul/laporan/grafik-penjualan.js'); ?>"></script>
</body>
</html>
