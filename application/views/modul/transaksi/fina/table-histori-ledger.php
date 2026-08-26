<body id="<?= $id; ?>" class="layout-fixed overflow-hidden" data-panel-auto-height-mode="height">
  <!-- Custom CSS -->  
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/table-page.css');?>">

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

    <div class="table-utils d-none">
      <button id="bfilter" type="button" class="btn btn-light btn-sm" style="text-shadow: none;">
        <i class="fas fa-filter text-sm text-primary"></i> Filter Data
      </button>
    </div>

    <div class="content px-0 mx-0 ml-2" style="margin-top: 70px;">
      <div class="container-fluid mt-1 px-0 mx-0">      
        <table id="table" class="table table-sm table-striped table-hover w-100 nowrap d-none">
          <thead>
          <tr>
          <th class="d-none"></th>
          <th width="10"></th>
          <th class="text-sm" width="120">Nomor</th>
          <th class="text-sm" width="90">Tanggal</th>
          <th class="text-sm" width="60">Sumber</th>          
          <th class="text-sm">Kontak</th>  
          <th class="text-sm">Uraian</th> 
          <th class="text-sm text-right px-2" width="150">Debit</th>
          <th class="text-sm text-right px-2" width="150">Kredit</th>
          <th class="text-sm text-right px-2" width="150">Saldo</th>
          <th class="d-none"></th>
          <th class="d-none"></th>                              
          <th class="text-sm">Akun</th>           
          </tr>
          </thead>
        </table>
        <div id="fDataTable" class="fDataTable d-none">
          <div class="row mt-2 mx-1">
              <div class="col-sm-12">
                <label class="col-form-label text-sm font-weight-normal">Pilih Akun :</label>
                <div class="input-group" data-target-input="nearest">
                  <select id="coa" name="coa" class="form-control form-control-sm select2" data-trigger="manual" data-placement="top" style="width: 100%">
                  </select>                  
                </div>                
              </div>
          </div>
          <div class="row mt-2 mx-1 my-0 py-0">
              <div class="col-sm-12">
                <label class="col-form-label text-sm font-weight-normal">Sampai Akun :</label>
                <div class="input-group" data-target-input="nearest">
                  <select id="coasampai" name="coasampai" class="form-control form-control-sm select2" data-trigger="manual" data-placement="top" style="width: 100%">
                  </select>                  
                </div>                
              </div>
          </div>          
          <div class="row mt-2 mx-1">
              <div class="col-sm-12">
                <label class="col-form-label text-sm font-weight-normal">Dari Tanggal :</label>
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
                <label class="col-form-label text-sm font-weight-normal">Sampai Tanggal :</label>
                <div class="input-group date">
                  <input id="tglsampai" type="text" class="form-control form-control-sm datepicker">
                  <div id="dtglsampai" class="input-group-append" role="button">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>                                
              </div>
          </div>
          <div class="row ml-3 mt-4 pt-0">
            <button type="button" id="submitfilter" class="btn btn-primary btn-sm"><i class="fas fa-check px-0"></i> Tampilkan</button>
          </div>
          <div class="row mt-2 ml-3">
            <div id="btnExpor"></div>            
          </div>                              
        </div>        
      </div>
    </div>
  </div>

  <!-- Control Sidebar -->
  <div class="bg-white btn-group-vertical btn-top">
  </div>
  <div class="btn-group-vertical">
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
<script src="<? echo base_url('assets/dist/js/akunting.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/select2/select2.full.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-select/js/dataTables.select.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-select/js/select.bootstrap4.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-buttons/js/buttons.bootstrap4.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-buttons/js/buttons.html5.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-buttons/js/buttons.jsZip.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/input-mask/jquery.inputmask.bundle.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables/colResize.js'); ?>"></script>
<!-- JS Custom -->
<script type="module" src="<? echo app_url('assets/dist/js/modul/transaksi/fina/table-histori-ledger.js'); ?>"></script>
</body>
</html>