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
      <div class="row pl-2">
      <span class="text-md text-olive">Fina</span>
      <ul class="navbar-nav d-none">
        <li class="nav-item dropdown d-sm-inline-block">
          <a href="#" class="nav-link my-0 py-0 mx-2" tabindex="-1" data-toggle="dropdown">
            <i class="fas fa-caret-down px-2 text-olive text-lg"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-sm dropdown-menu-left"> 
            <a id="bDataSaldoAwal" href="#" class="dropdown-item text-sm">
              <span class="ml-1">Saldo Awal</span>
            </a>
            <a id="bDataPembelian" href="#" class="dropdown-item text-sm">
              <span class="ml-1">Pembelian Aktiva</span>
            </a>                                    
            <a id="bDataPenyusutan" href="#" class="dropdown-item text-sm">
              <span class="ml-1">Penyusutan Aktiva</span>
            </a>            
          </div>        
        </li>
      </ul>
      </div>
      <div class="row pl-2">               
        <h5><?= $page_caption;?></h5> 
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
        <table id="aktiva-table" class="table table-sm table-striped table-hover w-100 bg-light nowrap d-none">
          <thead>
          <tr>
          <th class="d-none"></th>
          <th></th>
          <th class="text-sm">Kode</th>
          <th class="text-sm">Nama</th>
          <th class="text-sm">Kelompok</th>  
          <th class="text-sm text-right">Nilai Perolehan</th> 
          <th class="text-sm text-right">Umur</th> 
          <th class="text-sm text-right">Akumulasi Beban</th>
          <th class="text-sm text-right">Nilai Buku</th>                                                      
          </tr>
          </thead>
        </table>
        <div id="fDataTable" class="fDataTable d-none">
          <div class="row mt-2 mx-1">
              <div class="col-sm-12">
                <label class="label-filter font-weight-normal">Kelompok :</label>
                <div class="input-group" data-target-input="nearest">
                  <select id="kelompok" name="kelompok" class="form-control form-control-sm select2"></select>
                </div>                
              </div>
          </div>          
          <div class="row mt-0 mx-1">
              <div class="col-sm-12">
                <label class="col-form-label text-sm font-weight-normal">Kode :</label>
                <div class="input-group" data-target-input="nearest">
                  <input type="text" id="kode" name="kode" class="form-control form-control-sm" autocomplete="off">
                </div>                
              </div>
          </div>
          <div class="row mt-0 mx-1">
              <div class="col-sm-12">
                <label class="col-form-label text-sm font-weight-normal">Nama :</label>
                <div class="input-group" data-target-input="nearest">
                  <input type="text" id="nama" name="nama" class="form-control form-control-sm" autocomplete="off">
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

  <div class="bg-white btn-group-vertical btn-top">
  </div>
  <div class="btn-group-vertical">
      <button id="badd" class="btn btn-app font-weight-normal" data-toggle="dropdown" style="text-shadow:none;">
        <i class="fas fa-plus"></i> <span>Tambah</span>
        <div class="dropdown-menu dropdown-menu-sm"> 
          <a id="baddaktiva" href="#" class="dropdown-item text-sm">
            <span class="ml-1">Tambah Aktiva Tetap</span>
          </a>
          <a id="bSaldoAwal" href="#" class="dropdown-item text-sm">
            <span class="ml-1">Saldo Awal Aktiva Tetap</span>
          </a>
          <a id="bPembelian" href="#" class="dropdown-item text-sm">
            <span class="ml-1">Pembelian Aktiva Tetap</span>
          </a>                                    
          <a id="bPenyusutan" href="#" class="dropdown-item text-sm">
            <span class="ml-1">Penyusutan Aktiva Tetap</span>
          </a>            
        </div>                
      </button>
      <a id="bedit" class="btn btn-app" >
        <i class="fas fa-edit"></i> <span>Edit</span>
      </a>
      <a id="bdelete" class="btn btn-app" >
        <i class="fas fa-trash"></i> <span>Hapus</span>
      </a>    
      <a id="bprint" class="btn btn-app" >
        <i class="fas fa-print"></i> <span>Cetak</span>
      </a>        
      <a id="brefresh" class="btn btn-app">
        <i class="fas fa-sync"></i> <span>Refresh</span>
      </a>            
  </div>    
  </form>
  <!-- /.control-sidebar -->

<!-- JS Vendor -->
<script src="<? echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<? echo base_url('assets/dist/js/adminlte.js'); ?>"></script>
<script src="<? echo base_url('assets/dist/js/akunting.js'); ?>"></script>
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
<script type="module" src="<? echo app_url('assets/dist/js/modul/transaksi/fina/aset-tetap.js'); ?>"></script>
</body>
</html>  