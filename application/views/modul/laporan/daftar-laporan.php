<body id="<?= $id; ?>" class="layout-fixed" data-panel-auto-height-mode="height" style="overflow-x: hidden;">
  <!-- Custom CSS -->  
  <link rel="stylesheet" href="<?= base_url('assets/dist/css/modul/settings-page.css');?>">

  <style scoped>
    .tab-content{
      margin-top: 65px;
    }
    .card-header {
      position: fixed;
      top:40px;
    }
    .nav-item a{
      line-height: 1.5rem;
    } 
    .content-header{
      height: 40px;
    }         
    @media (max-width:767px){
      .tab-content{
        padding:10px;
      }
      .nav{
          flex-wrap: inherit; 
          overflow: auto;
      } 
      .nav-item a{
        white-space: nowrap;
        line-height: 2rem;
      }  
      .small-box .inner h5,
      .small-box .inner p{
        text-align: left;
      }  
    }    
  </style>

  <div class="content-wrapper mx-0 bg-light">
    <div class="content-header bg-white px-4 py-2 w-100 border-0">
      <div class="row">
      <div class="col-sm-11">
      <h5><?= $page_caption;?></h5> 
      </div>
      <div id="btnsideright">
      </div>
      </div>
    </div>

    <section class="content mx-0 px-0 mt-0">
      <div class="loader-wrap d-none">
      </div>      
      <div class="card card-outline card-outline-tabs" style="box-shadow: none">
        <div class="card-header p-0 w-100" style="z-index:9995;">
          <ul id="listparent" class="nav nav-tabs border-0 bg-white" role="tablist">
          </ul>
        </div>
        <div class="card-body card-outline-tabs-body border-0 bg-light">
          <div class="tab-content">
            <div id="tabcontent" class="row">                                   
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- JS Vendor -->
  <script src="<? echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
  <script src="<? echo base_url('assets/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
  <script src="<? echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
  <script src="<? echo base_url('assets/dist/js/adminlte.js'); ?>"></script>
  <script src="<? echo base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
  <!-- JS Custom -->
  <script type="module" src="<? echo app_url('assets/dist/js/modul/laporan/daftar-laporan4.js'); ?>"></script>
  
</body>
</html>