<body id="<?= $id; ?>" class="layout-fixed overflow-hidden" data-panel-auto-height-mode="height">
  <!-- Custom CSS -->  
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/table-page.css');?>">
  <style>
    table.dataTable{
      padding-top: 145px;
    }
    table.dataTable thead{
      position:sticky;
      top:215px;
    }  
    .top{
      height: 145px;
      border-bottom: none;
    }

    @media (max-width:767px){    
    .top{
      position: relative;
      top: 0px;
    }  
    table.dataTable thead{
      top:70px;
    }    
    table.dataTable{
      padding-top: 190px;
    }
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

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper tab-wrap mx-0">
    <!-- Content Header (Page header) -->
    <div class="content-header bg-white px-4 py-2 position-fixed w-100">
      <div class="row pl-2">
      <span class="text-md text-olive">Fina</span>
      <ul class="navbar-nav">
        <li class="nav-item dropdown d-sm-inline-block">
          <a href="#" class="nav-link my-0 py-0 mx-2" tabindex="-1" data-toggle="dropdown">
            <i class="fas fa-caret-down px-2 text-olive text-lg"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-sm dropdown-menu-left"> 
            <a id="bBack" href="#" class="dropdown-item text-sm">
              <span class="ml-1">Kembali ke list</span>
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

    <!-- Main content -->
    <div class="content px-0 mx-0 ml-2" style="margin-top: 70px;">
      <div class="container-fluid mt-1 px-0 mx-0">      
        <div class="top bg-light w-100">
          <div class="container-fluid mt-1 px-1 mx-1">                
            <div class="row pt-3 mx-1">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal px-2">Kontak *</label>
            <div class="col-sm-2">
                  <div class="input-group">
                    <input type="hidden" id="idkontak" name="idkontak">                    
                    <input type="text" class="form-control form-control-sm" id="kontak" name="kontak" autocomplete="off" data-trigger="manual" data-placement="auto">
                    <div id="carikontak" class="input-group-append" role="button">
                        <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                    </div>
                  </div>                
            </div>
            <div id="namakontak" class="col-sm-3 col-form-label-sm"></div>            
            <label class="col-sm-2 col-form-label text-sm font-weight-normal px-2">No Transaksi *</label>
            <div class="col-sm-2">
                  <div class="input-group">
                    <input type="hidden" id="id" name="id">                    
                    <input type="text" class="form-control form-control-sm" id="nomor" name="nomor" autocomplete="off" data-trigger="manual" data-placement="auto" placeholder="[Auto]">
                  </div>                
            </div>            
            </div>
            <div class="row mx-1">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal px-2">Tanggal *</label>
            <div class="col-sm-2">
                  <div class="input-group date">
                    <input type="text" id="tgl" name="tgl" class="form-control form-control-sm datepicker" autocomplete="off">
                    <div id="dTgl" class="input-group-append" role="button">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                  </div>                
            </div>
            </div>
            <div class="row mx-1">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal px-2">Untuk Periode *</label>
            <div class="col-sm-2 mb-1">
                <select class="select2 form-control form-control-sm" style="width: 100%" id="ibulan" name="ibulan" data-trigger="manual" data-placement="auto">
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
            <div class="col-sm-2">            
                <select class="select2 form-control form-control-sm" style="width: 100%" id="itahun" name="itahun" data-trigger="manual" data-placement="auto">
                </select>                             
            </div>              
            </div>
          </div>
        </div>
        <table id="aktiva-table" class="table table-sm table-striped table-hover w-100 bg-light nowrap d-none">
          <thead>
          <tr>
          <th class="d-none"></th>
          <th><input type='checkbox' id='all-check' class="mt-1"></th>
          <th class="text-sm">Kode</th>
          <th class="text-sm">Nama</th>
          <th class="text-sm">Kelompok</th>  
          <th class="text-sm">Tgl Pakai</th>            
          <th class="text-sm text-right">Nilai Perolehan</th> 
          <th class="text-sm text-right">Umur</th> 
          <th class="text-sm text-right">Akumulasi Beban</th>
          <th class="text-sm text-right">Nilai Buku</th>                                                      
          </tr>
          </thead>
        </table>                                                 
      </div>
    </div>
    <!-- /.Main content -->
  </div>

  <!-- Control Sidebar -->
<div class="bg-white btn-group-vertical btn-top">
</div>
  <div class="btn-group-vertical">
      <a id="bsave" class="btn btn-app">
        <span class="badge bg-success"></span>
        <i class="fas fa-save"></i> <span>Simpan</span>
      </a>
      <a id="bcancel" class="btn btn-app">
        <span class="badge bg-purple"></span>
        <i class="fas fa-times"></i> <span>Batal</span>
      </a>        
  </div>    
  <aside id="control-sidebar-r" class="control-sidebar bg-transparent border-0" style="width: 75px">
    <!-- Control sidebar content goes here -->
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
<script type="module" src="<? echo app_url('assets/dist/js/modul/transaksi/fina/aset-penyusutan.js'); ?>"></script>
</body>
</html>  