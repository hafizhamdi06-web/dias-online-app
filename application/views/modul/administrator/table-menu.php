<body id="<? echo $id; ?>" class="layout-fixed overflow-hidden" data-panel-auto-height-mode="height">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/table-page.css');?>">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-rowgroup/css/rowGroup.bootstrap4.min.css');?>">

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
      <span class="text-md text-olive">Administrator</span>
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
        <table id="menu-table" class="table table-sm table-striped table-hover w-100 nowrap d-none">
          <thead>
          <tr>
          <th class="d-none"></th>
          <th></th>          
          <th class="text-sm">Nama Menu</th>
          <th class="text-sm">Urutan</th>          
          <th class="text-sm">Tipe</th>
          <th class="text-sm">Icon</th>
          <th class="text-sm">Status</th>                                        
          </tr>
          </thead>
        </table>
        <div id="fDataTable" class="fDataTable d-none">
          <div class="row mt-2 mx-1">
              <div class="col-sm-12">
                <label class="label-filter font-weight-normal">Tipe :</label>
                <div class="input-group" data-target-input="nearest">
                  <select id="tipe" name="tipe" class="form-control form-control-sm select2">
                      <option class="text-sm" value="" selected>&nbsp;</option>                    
                      <option class="text-sm" value="1">Laporan</option>
                      <option class="text-sm" value="2">Transaksi</option>                                                  
                      <option class="text-sm" value="3">Master Data</option>
                      <option class="text-sm" value="4">Administrator</option>                    
                  </select>
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
          <div class="row ml-3 mt-4 pt-0">
            <button type="button" id="submitfilter" class="btn btn-primary btn-sm">Tampilkan</button>
          </div>                              
        </div>                                
      </div>
    </div>
  </div>
  
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
<script src="<? echo base_url('assets/plugins/datatables/colResize.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-rowgroup/js/dataTables.rowGroup.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-rowgroup/js/rowGroup.bootstrap4.min.js'); ?>"></script>
<!-- JS Custom -->
<script type="module" src="<? echo app_url('assets/dist/js/modul/administrator/table-menu.js'); ?>"></script>
</body>
</html>