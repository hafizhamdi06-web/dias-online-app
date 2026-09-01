<body id="<?= $id; ?>" class="layout-fixed bg-transparent overflow-hidden" data-panel-auto-height-mode="height">
  <!-- Custom CSS -->  
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/transaksi-page.css');?>">  

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
            <a id="bTable" href="#" class="dropdown-item text-sm"><i class="fas fa-folder-open text-gray"></i>
            <span class="ml-1">Data <?= $page_caption;?></span></a>
            <a id="bViewJurnal" href="#" class="dropdown-item text-sm"><i class="fas fa-search text-gray"></i>
            <span class="ml-1">Lihat Jurnal</span></a>                                    
          </div>        
        </li>
      </ul>         
      </div>
      <div class="row">
      <div class="col-sm-11">
      <h5><?= $page_caption;?></h5> 
      </div>
      <div id="btnsideright">
        <div class="form-check d-none">
          <input type="checkbox" class="form-check-input" id="chkpending" tabindex="-1">
          <label class="form-check-label text-sm" for="chkpending" role="button">Pending</label>
        </div>         
      </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="margin-top: 70px">
      <form id="form-<?= $id; ?>" class="form-horizontal">
      <input type="hidden" id="id" name="id" value="">
      <input type="hidden" id="notrans" name="notrans" value="">      
      <input type="hidden" id="status" name="status">
      <input type="hidden" class="noclear" id="multidivisi" name="multidivisi" value="<?= $multidivisi ?>">
      <input type="hidden" class="noclear" id="multiproyek" name="multiproyek" value="<?= $multiproyek ?>">
      <input type="hidden" class="noclear" id="multisatuan" name="multisatuan" value="<?= $multisatuan ?>">
      <input type="hidden" class="noclear" id="multikurs" name="multikurs" value="<?= $multikurs ?>">                
      <div class="container-fluid container-absolute-fina">
          <!-- Isi -->
          <div class="row mt-2">
            <div class="col-sm-6">
            </div>            
            <div class="col-sm-3">
              <div class="form-group">
                <label class="text-sm px-2 font-weight-normal">Tanggal *</label>
                  <div class="input-group date">
                    <input type="text" id="tgl" name="tgl" class="form-control form-control-sm datepicker" autocomplete="off">
                    <div id="dTgl" class="input-group-append" role="button">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                  </div>                
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="text-sm px-2 font-weight-normal">No. Transaksi</label>
                <input type="text" id="nomor" name="nomor" class="form-control form-control-sm" placeholder="[Auto]" autocomplete="off">
              </div>
            </div>
          </div>
      </div>
    </section>   

    <section class="content">
      <div id="dLeftFirst" class="container-fluid left">
        <div class="row">
          <label for="" class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">Sudah Bayar Ke *</label>
          <div class="col-sm-2">
                <div class="input-group">
                  <input type="hidden" id="idkontak" name="idkontak">                    
                  <input type="text" class="form-control form-control-sm" id="kontak" name="kontak" autocomplete="off" data-trigger="manual" data-placement="auto">
                  <div id="carikontak" class="input-group-append" role="button">
                      <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                  </div>
                </div>                
          </div>
          <div id="namakontak" class="col-sm-4 col-form-label-sm"></div>
        </div>  
        <div class="row">
          <label for="" class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">Uraian *</label>
          <div class="col-sm-10">
            <textarea class="form-control form-control-sm" rows="1" id="uraian" name="uraian" autocomplete="off" data-trigger="manual" data-placement="auto"></textarea>
          </div>
        </div>
        <div class="row">
          <label for="" class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">COA Kas [K] *</label>
          <div class="col-sm-3">
              <select id="coakredit" name="coakredit" class="form-control form-control-sm select2" data-trigger="manual" data-placement="top" style="width: 100%" disabled>
                <option value="5" selected>Kas Kecil</option>
              </select>
          </div>
          <div class="col-sm-3"></div>
          <label class="col-sm-1 col-form-label text-sm px-3 font-weight-normal">Uang</label>
          <div class="col-sm-1">
            <input type="hidden" id="iduangkredit" name="iduangkredit">
            <input id="uangkredit" type="text" class="form-control form-control-sm total" readonly="" tabindex="-1" value="Rp">
          </div>
          <label class="col-sm-1 col-form-label text-sm px-3 font-weight-normal">Kurs</label>
          <div class="col-sm-1">
            <input id="kurskredit" type="text" class="form-control form-control-sm numeric total" readonly="" tabindex="-1" value="1,00">
          </div>                                    
        </div>                                                                                                         
      </div>
    </section>


    <section class="content">
      <div class="container-fluid left mt-3">
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
                            <th class="text-sm text-label text-center border-0" style="width: 300px">COA</th>
                            <th class="text-sm text-label text-center border-0" style="width: 170px">Jumlah [D]</th>
                            <th class="text-sm text-label text-center border-0" style="width: 65px">Kurs</th>
                            <th class="text-sm text-label text-center border-0 d-none" style="width: 160px">Valas</th>    
                            <? if($multidivisi==1){ ?>
                            <th class="text-sm text-label text-center border-0" style="width: 110px">Divisi</th>
                            <? } ?>                   
                            <? if($multiproyek==1){ ?>                                                           
                            <th class="text-sm text-label text-center border-0" style="width: 110px">Proyek</th>
                            <? } ?>                                                                 
                            <th class="text-sm text-label text-center border-0" style="width: 180px">Catatan</th>
                            <th class="text-sm text-label text-center border-0" style="width: 50px"></th>                                            
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        </tfoot>
                      </table>
                  <button type="button" id="baddrow" class="btn btn-primary btn-step1 text-sm mb-2"><i class="fa fa-plus px-2"></i>Tambah Data</button>
                  <span id="loader-detil" class="ml-2 text-sm d-none"><i class="fas fa-spinner fa-spin mx-2"></i>loading coa data...</span>
                </div>
               </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-8"></div>                    
            <div class="col-sm-2 py-0">
              <div class="form-group">
                <label class="text-sm px-1 font-weight-normal">Rp.</label>
                <input id="totalrp" type="text" class="total form-control form-control-sm numeric border-0" value="0" disabled>
              </div>
            </div>    
            <div class="col-sm-2 py-0">
              <div class="form-group">
                <label class="text-sm px-1 font-weight-normal">Valas</label>
                <input id="totalvalas" type="text" class="total form-control form-control-sm numeric border-0" value="0" disabled>
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
    <a id="bsearch" class="btn btn-app btn-step2 disabled" >
      <span class="badge bg-success"></span>
      <i class="fas fa-search"></i> <span>Cari</span>
    </a>
    <a id="bprint" class="btn btn-app btn-step2 disabled" >
      <span class="badge bg-purple"></span>
      <i class="fas fa-print"></i> <span>Cetak</span>
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
<script type="module" src="<? echo app_url('assets/dist/js/modul/transaksi/fina/kas-keluar.js'); ?>"></script>
</body>
</html>