<body id="<?= $id; ?>" class="layout-fixed" data-panel-auto-height-mode="height" style="overflow-x: hidden;">
  <!-- Custom CSS -->  
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/settings-page.css');?>">

  <style scoped>
    .tab-content{
      margin-top: 100px;
    }
    .nav-item a{
      line-height: 1.2rem;
    }

    @media (max-width:767px){
      .nav{
          flex-wrap: inherit; 
          overflow: auto;
      } 
      .nav-item a{
        white-space: nowrap;
        line-height: 1.8rem;
      }
      .top{
        top: 110px;
      }
    }    
  </style>

  <div class="content-wrapper tab-wrap mx-0 bg-white">
    <div class="content-header bg-white px-4 py-2 w-100">
      <div class="row">
      <div class="col-sm-11">
      <span class="text-md text-olive">Administrator</span>
      <h5><?= $page_caption;?></h5> 
      </div>
      <div id="btnsideright">
      </div>
      </div>
    </div>

    <section class="content mx-0 px-0 mt-0">
      <div id="tabsettings" class="card card-white card-outline card-outline-tabs" style="box-shadow: none">
        <div class="card-header card-header-sm p-0 border-0 bg-white w-100" style="position: fixed;top: 70px;z-index:999;">
          <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link text-sm active py-1 my-0" id="btn-tab-menu" data-toggle="pill" href="#tab-menu" role="tab" aria-controls="tab-menu" aria-selected="true">Info Perusahaan</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-sm py-1 my-0" id="btn-tab-jurnal" data-toggle="pill" href="#tab-jurnal" role="tab" aria-controls="tab-jurnal" aria-selected="false">Setup Jurnal</a>
            </li>            
            <?
              if($this->session->kode == 0) {
            ?>
            <li class="nav-item">
              <a class="nav-link text-sm py-1 my-0" id="btn-tab-post" data-toggle="pill" href="#tab-post" role="tab" aria-controls="tab-post" aria-selected="false">UN/Posting Transaksi</a>
            </li>            
            <li class="nav-item">
              <a class="nav-link text-sm py-1 my-0" id="btn-tab-nomor" data-toggle="pill" href="#tab-nomor" role="tab" aria-controls="tab-nomor" aria-selected="false">Penomoran Transaksi</a>
            </li>            
            <?
              }
            ?>
            <li class="nav-item">
              <a class="nav-link text-sm py-1 my-0" id="btn-tab-hapus" data-toggle="pill" href="#tab-hapus" role="tab" aria-controls="tab-hapus" aria-selected="false">Hapus Transaksi</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-sm py-1 my-0" id="btn-tab-utility" data-toggle="pill" href="#tab-utility" role="tab" aria-controls="tab-utility" aria-selected="false">Utility</a>
            </li>            
          </ul>
        </div>
        <div class="card-body card-outline-tabs-body border-0 px-1">
          <div class="tab-content">
            <?php include ("settings-tab-info.php"); ?>
            <?php include ("settings-tab-jurnal.php"); ?>
            <?php if($this->session->kode == 0) include ("settings-tab-posting.php"); ?>
            <?php if($this->session->kode == 0) include ("settings-tab-nomor.php"); ?>     
            <?php include ("settings-tab-hapus.php"); ?>                   
            <?php include ("settings-tab-utility.php"); ?>                               
          </div>
        </div>
      </div>
    </section>
  </div>

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
    <a id="bprint" class="btn btn-app btn-step2 disabled" >
      <span class="badge bg-purple"></span>
      <i class="fas fa-print"></i> <span>Cetak</span>
    </a>        
    <a id="bsave" class="btn btn-app btn-step1">
      <span class="badge bg-success"></span>
      <i class="fas fa-save"></i> <span>Simpan</span>
    </a>
    <a id="brefresh" class="btn btn-app disabled">
      <i class="fas fa-sync"></i> <span>Refresh</span>
    </a>                
  </div>    
  </form>

<!-- JS Vendor -->
<script src="<? echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<? echo base_url('assets/dist/js/adminlte.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/select2/select2.full.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/input-mask/jquery.inputmask.bundle.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-select/js/dataTables.select.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables-select/js/select.bootstrap4.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/input-mask/jquery.inputmask.bundle.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datatables/colResize.js'); ?>"></script>
<!-- JS Custom -->
<script src="<? echo app_url('assets/dist/js/modul/administrator/settings-tab-info.js'); ?>"></script>
<script src="<? echo app_url('assets/dist/js/modul/administrator/settings-tab-jurnal.js'); ?>"></script>
<script src="<? echo app_url('assets/dist/js/modul/administrator/settings-tab-posting.js'); ?>"></script>
<script src="<? echo app_url('assets/dist/js/modul/administrator/settings-tab-nomor.js'); ?>"></script>
<script src="<? echo app_url('assets/dist/js/modul/administrator/settings-tab-hapus.js'); ?>"></script>
<script src="<? echo app_url('assets/dist/js/modul/administrator/settings-tab-utility.js'); ?>"></script>
<script src="<? echo app_url('assets/dist/js/modul/administrator/settings.js'); ?>"></script>
</body>
</html>