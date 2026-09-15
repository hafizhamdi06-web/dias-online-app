<body id="<? echo $id; ?>" class="layout-fixed overflow-hidden" data-panel-auto-height-mode="height">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/table-page.css');?>">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper tab-wrap mx-0">
    <!-- Content Header (Page header) -->
    <div class="content-header bg-white px-4 py-2 position-fixed w-100">
      <div class="row">
      <div class="col-sm-11">
      <span class="text-md text-olive">Setup Program</span>
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
          <div class="card-header py-2">
            <h6 class="card-title mb-0">Kredensial Shopee Open Platform
              <span id="badge-status" class="badge badge-secondary ml-2">Memuat...</span>
            </h6>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4 col-12 mb-2">
                <label class="text-sm font-weight-normal mb-1">Nama Toko</label>
                <input type="text" id="nama_toko" class="form-control form-control-sm" autocomplete="off">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="text-sm font-weight-normal mb-1">Environment</label>
                <select id="environment" class="form-control form-control-sm">
                  <option value="live">Live (Production)</option>
                  <option value="sandbox">Sandbox (Test)</option>
                </select>
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="text-sm font-weight-normal mb-1">Cabang Marketplace</label>
                <select id="cabang" class="form-control select2 form-control-sm" style="width:100%"></select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 col-12 mb-2">
                <label class="text-sm font-weight-normal mb-1">Partner ID</label>
                <input type="text" id="partner_id" class="form-control form-control-sm" autocomplete="off">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="text-sm font-weight-normal mb-1">Partner Key</label>
                <input type="password" id="partner_key" class="form-control form-control-sm" autocomplete="off" placeholder="(kosongkan kalau tidak diganti)">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="text-sm font-weight-normal mb-1">Shop ID</label>
                <input type="text" id="shop_id" class="form-control form-control-sm" autocomplete="off" placeholder="terisi otomatis setelah otorisasi">
              </div>
            </div>
            <div class="row">
              <div class="col-12 mb-2">
                <label class="text-sm font-weight-normal mb-1">Redirect URI (daftarkan persis ini di Shopee Open Platform)</label>
                <input type="text" id="redirect_uri" class="form-control form-control-sm" autocomplete="off">
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-md-3 col-6 mb-2">
                <button type="button" id="bsimpan" class="btn btn-primary btn-sm btn-block"><i class="fas fa-save"></i> Simpan</button>
              </div>
              <div class="col-md-3 col-6 mb-2">
                <button type="button" id="bhubungkan" class="btn btn-success btn-sm btn-block"><i class="fas fa-plug"></i> Hubungkan / Otorisasi ke Shopee</button>
              </div>
            </div>
            <small class="text-muted d-block mt-2">
              Partner ID &amp; Partner Key didapat dari toko App di <b>open.shopee.com</b>.
              Setelah Simpan, klik <b>Hubungkan / Otorisasi</b> — jendela baru akan meminta login &amp; izin toko Shopee,
              lalu otomatis mengisi Shop ID dan token akses (access_token akan diperpanjang otomatis via refresh_token).
            </small>
          </div>
        </div>

      </div>
    </div>
    <!-- /.Main content -->
  </div>

  <div class="bg-white btn-group-vertical btn-top"></div>
  <aside id="control-sidebar-r" class="control-sidebar bg-transparent border-0"></aside>

<!-- JS Vendor -->
<script src="<? echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<? echo base_url('assets/dist/js/adminlte.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/select2/select2.full.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/toastr/toastr.min.js'); ?>"></script>
<!-- JS Custom -->
<script src="<? echo app_url('assets/dist/js/modul/administrator/shopee-api-setting.js'); ?>"></script>
</body>
</html>
