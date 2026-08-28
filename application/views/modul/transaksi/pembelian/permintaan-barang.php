<body id="<?= $id; ?>" class="layout-fixed bg-transparent overflow-hidden" data-panel-auto-height-mode="height">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/transaksi-page.css');?>">
  <style>
    #tdetil.pb-hide-stokakhir .col-stokakhir,
    #tdetil.pb-hide-stokakhir .col-refreshstok {
      display: none;
    }
  </style>

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

  <div class="content-wrapper tab-wrap mx-0">
    <div class="content-header bg-white px-4 py-2 position-fixed w-100">
      <div class="row pl-2">
      <span class="text-md text-olive">Pembelian</span>
      <ul class="navbar-nav">
        <li class="nav-item dropdown d-sm-inline-block">
          <a href="#" class="nav-link my-0 py-0 mx-2" tabindex="-1" data-toggle="dropdown">
            <i class="fas fa-caret-down px-2 text-olive text-lg"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-sm dropdown-menu-left">
            <a id="bTable" href="#" class="dropdown-item text-sm"><i class="fas fa-folder-open text-gray"></i>
            <span class="ml-1">Data <?= $page_caption;?></span></a>
          </div>
        </li>
      </ul>
      </div>
      <div class="row">
      <div class="col-sm-11">
      <h5><?= $page_caption;?></h5>
      </div>
      <div id="btnsideright">
      </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <form id="form-<?= $id; ?>" class="form-horizontal">
    <input type="hidden" id="id" name="id" value="">
    <input type="hidden" id="status" name="status">
    <input type="hidden" class="noclear" id="cabang" name="cabang" value="<? echo @$_SESSION['cabang']; ?>">
    <input type="hidden" class="noclear" id="multidivisi" name="multidivisi" value="<?= $multidivisi ?>">
    <input type="hidden" class="noclear" id="multiproyek" name="multiproyek" value="<?= $multiproyek ?>">
    <input type="hidden" class="noclear" id="multisatuan" name="multisatuan" value="<?= $multisatuan ?>">
    <input type="hidden" class="noclear" id="multikurs" name="multikurs" value="<?= $multikurs ?>">
    <input type="hidden" class="noclear" id="decimalqty" name="decimalqty" value="<?= $decimalqty ?>">
    <input type="hidden" class="noclear" id="bisaedit" name="bisaedit" value="<?= $bisaedit ?>">
    <input type="hidden" class="noclear" id="bisahapus" name="bisahapus" value="<?= $bisahapus ?>">
    <input type="hidden" class="noclear" id="bisaprint" name="bisaprint" value="<?= $bisaprint ?>">
    <input type="hidden" class="noclear" id="idkaryawandefault" name="idkaryawandefault" value="<? echo @$_SESSION['idkaryawan']; ?>">
    <input type="hidden" class="noclear" id="karyawandefault" name="karyawandefault" value="<? echo @$_SESSION['namakaryawan']; ?>">
    <section class="content" style="margin-top: 70px">
      <div class="container-fluid pt-4">
          <div class="form-group row my-0">
            <label class="col-2 col-form-label text-sm px-3 font-weight-normal">Nama Karyawan *</label>
            <div class="col-2">
                  <div class="input-group" data-target-input="nearest">
                    <input type="hidden" id="idkaryawan" name="idkaryawan">
                    <input type="text" id="karyawan" name="karyawan" class="form-control form-control-sm" autocomplete="off" data-trigger="manual" data-placement="auto">
                    <div id="carikaryawan" class="input-group-append" role="button">
                        <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                    </div>
                  </div>
            </div>
            <div class="col-2"></div>
            <label class="col-1 col-form-label text-sm px-3 font-weight-normal">Tanggal *</label>
            <div class="col-2">
                  <div class="input-group date">
                    <input type="text" id="tgl" name="tgl" class="form-control form-control-sm datepicker" autocomplete="off">
                    <div id="dTgl" class="input-group-append" role="button">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                  </div>
            </div>
            <label class="col-1 col-form-label text-sm px-3 font-weight-normal">No. PO</label>
            <div class="col-2">
              <input type="text" id="nomor" name="nomor" class="form-control form-control-sm" placeholder="[Auto]" autocomplete="off">
            </div>
          </div>
          <div class="form-group row my-0">
            <label class="col-2 col-form-label text-sm px-3 font-weight-normal">Depo/Farmasi</label>
            <div class="col-2">
              <input type="text" id="gudangsumbernama" class="form-control form-control-sm border-0" disabled>
            </div>
            <div class="col-2"></div>
            <label class="col-1 col-form-label text-sm px-3 font-weight-normal">Status</label>
            <div class="col-2">
              <input type="hidden" id="status" name="status" value="0">
              <input type="text" id="statusnama" class="form-control form-control-sm border-0" value="Belum Verifikasi" disabled>
            </div>
          </div>
          <div class="form-group row my-0">
            <label class="col-2 col-form-label text-sm px-3 font-weight-normal">Tujuan *</label>
            <div class="col-2">
                  <div class="input-group" data-target-input="nearest">
                    <input type="hidden" id="tujuan" name="tujuan">
                    <input type="hidden" id="tujuankode">
                    <input type="text" id="tujuannama" class="form-control form-control-sm" autocomplete="off">
                    <div id="caritujuan" class="input-group-append" role="button">
                        <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                    </div>
                  </div>
            </div>
            <div class="col-2"></div>
            <label class="col-1 col-form-label text-sm px-3 font-weight-normal">Catatan Verifikasi</label>
            <div class="col-2">
              <input type="text" id="catatanverifikasi" name="catatanverifikasi" class="form-control form-control-sm border-0" autocomplete="off" disabled>
            </div>
          </div>
          <div class="form-group row my-0">
            <div class="col-5"></div>
            <div class="col-2">
              <button type="button" id="bverifikasibawah" class="btn btn-primary btn-sm btn-block btn-step2 disabled d-none"><i class="fas fa-check-circle"></i> Verifikasi</button>
            </div>
          </div>
          <div class="form-group row my-0">
            <label class="col-2 col-form-label text-sm px-3 font-weight-normal">Tipe Permintaan *</label>
            <div class="col-2">
                  <select id="jenis" name="jenis" class="form-control select2 text-sm" style="width:100%">
                    <option value="1">Permintaan Barang</option>
                    <option value="2">Permintaan Pembelian</option>
                  </select>
            </div>
          </div>
          <div class="form-group row my-0">
            <label class="col-2 col-form-label text-sm px-3 font-weight-normal">PO Ke</label>
            <div class="col-2">
                  <div class="input-group" data-target-input="nearest">
                    <input type="hidden" id="gudang" name="gudang">
                    <input type="text" id="gudangnama" class="form-control form-control-sm" autocomplete="off">
                    <div id="carigudang" class="input-group-append" role="button">
                        <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                    </div>
                  </div>
            </div>
          </div>
          <div class="form-group row my-0">
            <label class="col-2 col-form-label text-sm px-3 font-weight-normal">Keterangan</label>
            <div class="col-10">
              <input type="text" id="uraian" name="uraian" class="form-control form-control-sm" autocomplete="off">
            </div>
          </div>
      </div>
    </section>

    <section class="content mt-4 pt-1">
      <div class="container-fluid">
          <div class="card card-primary card-outline card-outline-tabs mt-2" style="box-shadow: none">
            <div class="card-header card-header-sm p-0 border-bottom-0">
              <ul class="nav nav-tabs bg-light" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item no-border mx-1">
                  <a class="nav-link text-sm active" id="btn-tab-menu" data-toggle="pill" href="#tab-menu" role="tab" aria-controls="tab-menu" aria-selected="true" tabindex="-1" title="Data Transaksi"><i class="fas fa-list text-gray text-md"></i></a>
                </li>
              </ul>
            </div>
            <div class="card-body card-outline-tabs-body">
              <div class="tab-content">
                <div class="row">
                <div class="table-responsive" tabindex="-1">
                      <table id="tdetil" class="table table-hover table-sm table-transaksi">
                        <thead class="bg-light">
                          <tr>
                            <th class="text-sm text-label text-center border-0" style="width: 130px">Kode</th>
                            <th class="text-sm text-label text-center border-0" style="width: 250px">Nama</th>
                            <th class="text-sm text-label text-center border-0" style="width: 90px">Qty</th>
                            <th class="text-sm text-label text-center border-0" style="width: 90px">Satuan</th>
                            <th class="text-sm text-label text-center border-0" style="width: 180px">Catatan</th>
                            <th class="text-sm text-label text-center border-0" style="width: 100px">Real Stok</th>
                            <th class="text-sm text-label text-center border-0 col-stokakhir" style="width: 100px">Stok Akhir</th>
                            <th class="text-sm text-label text-center border-0 col-refreshstok" style="width: 100px">Refresh Stok</th>
                            <th class="text-sm text-label text-center border-0" style="width: 40px"></th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        </tfoot>
                      </table>
                    <button type="button" id="baddrow" class="btn btn-primary btn-step1 text-sm mb-2"><i class="fa fa-plus px-2"></i>Tambah Data</button>
                    <span id="loader-detil" class="ml-2 text-sm d-none"><i class="fas fa-spinner fa-spin mx-2"></i>loading item data...</span>
                </div>
                </div>
              </div>
            </div>
          </div>
      </div>
    </section>

    <section class="content pt-0">
      <div class="container-fluid">
          <div class="row px-0 py-0">
            <div class="col-sm-10"></div>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="text-sm px-1 font-weight-normal">Total Qty</label>
                <input id="tqty" type="text" class="total form-control form-control-sm numeric border-0" value="0" disabled>
              </div>
            </div>
          </div>
      </div>
    </section>
</div>

<!-- Control Sidebar -->
  <div class="bg-white btn-group-vertical btn-top">
  </div>
  <div class="btn-group-vertical">
    <a id="badd" class="btn btn-app btn-step2 disabled">
      <span class="badge bg-success"></span>
      <i class="fas fa-plus"></i> <span>Tambah</span>
    </a>
    <a id="bedit" class="btn btn-app btn-step2 disabled" >
      <span class="badge bg-purple"></span>
      <i class="fas fa-edit"></i> <span>Edit</span>
    </a>
    <a id="bdelete" class="btn btn-app btn-step2 disabled" >
      <span class="badge bg-teal"></span>
      <i class="fas fa-trash"></i> <span>Hapus</span>
    </a>
    <a id="bsearch" class="btn btn-app btn-step2 disabled">
      <span class="badge bg-warning"></span>
      <i class="fas fa-search"></i> <span>Cari</span>
    </a>
    <a id="bprint" class="btn btn-app btn-step2 disabled" >
      <span class="badge bg-purple"></span>
      <i class="fas fa-print"></i> <span>Cetak</span>
    </a>
    <a id="bverifikasi" class="btn btn-app btn-step2 disabled d-none" >
      <span class="badge bg-info"></span>
      <i class="fas fa-check-circle"></i> <span>Verifikasi</span>
    </a>
    <a id="bsave" class="btn btn-app btn-step1">
      <span class="badge bg-success"></span>
      <i class="fas fa-save"></i> <span>Simpan</span>
    </a>
    <a id="bcancel" class="btn btn-app btn-step1">
      <span class="badge bg-purple"></span>
      <i class="fas fa-times"></i> <span>Batal</span>
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
<script src="<? echo base_url('assets/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/input-mask/jquery.inputmask.bundle.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/input_hidden.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<!-- JS Custom -->
<script type="module" src="<? echo app_url('assets/dist/js/modul/transaksi/pembelian/permintaan-barang.js'); ?>"></script>
</body>
</html>
