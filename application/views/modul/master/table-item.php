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
      <span class="text-md text-success">Master Data</span>
      <h5><?= $page_caption;?></h5> 
      </div>
      <div id="btnsideright"> 
      </div>
      </div>
    </div>
    <!-- /.content-header -->

    <div class="table-utils d-none">
      <button id="bexport" type="button" class="btn btn-light btn-sm d-none" style="text-shadow: none;">
        <i class="fas fa-download text-sm text-primary"></i> Unduh
      </button>
      <button id="bfilter" type="button" class="btn btn-light btn-sm" style="text-shadow: none;">
        <i class="fas fa-filter text-sm text-primary"></i> Filter Data
      </button>
    </div>

    <!-- Main content -->
    <div id="tabel-content" class="content px-0 mx-0 ml-2" style="margin-top: 70px;">
      <div class="container-fluid mt-1 px-0 mx-0">      
        <table id="item-table" class="table table-sm table-striped table-hover w-100 bg-light nowrap d-none">
          <thead>
          <tr>
          <th class="d-none"></th>            
          <th></th>            
          <th class="text-sm">Kode</th>
          <th class="text-sm">Nama</th>
          <th class="text-sm">Qty</th>
          <th class="text-sm">Satuan</th>          
          <th class="text-sm">Jenis</th>
          <th class="text-sm">Harga Beli</th>
          <th class="text-sm">Harga Jual</th>  
          <th class="text-sm">Akun Pendapatan</th>
          <th class="text-sm">Nama Akun</th>                                                              
          </tr>
          </thead>
        </table>
        <div id="fDataTable" class="fDataTable d-none">
          <div class="row mt-2 mx-1">
              <div class="col-sm-12">
                <label class="label-filter font-weight-normal">Jenis Item :</label>
                <div class="input-group" data-target-input="nearest">
                  <select id="jenis" name="jenis" class="form-control form-control-sm select2">
                    <option value="0">Persediaan</option>
                    <option value="1">Jasa</option>
                  </select>
                </div>                
              </div>
          </div>
          <div class="row mt-0 mx-1">
              <div class="col-sm-12">
                <label class="label-filter font-weight-normal">Kode :</label>
                <div class="input-group" data-target-input="nearest">
                  <input type="text" id="kode" name="kode" class="form-control form-control-sm" autocomplete="off">
                </div>                
              </div>
          </div>
          <div class="row mt-0 mx-1">
              <div class="col-sm-12">
                <label class="label-filter font-weight-normal">Nama :</label>
                <div class="input-group" data-target-input="nearest">
                  <input type="text" id="nama" name="nama" class="form-control form-control-sm" autocomplete="off">
                </div>                
              </div>
          </div>
          <div class="btn-group pt-4 pl-3">
            <button type="button" id="submitfilter" class="btn btn-primary btn-sm"><i class="fas fa-check"></i> Tampilkan</button>
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
      <a id="badd" class="btn btn-app">
        <i class="fas fa-plus"></i> <span>Tambah</span>
      </a>
      <a id="bedit" class="btn btn-app">
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
<script type="module" src="<? echo app_url('assets/dist/js/modul/master/table-item.js'); ?>"></script>
</body>
</html>