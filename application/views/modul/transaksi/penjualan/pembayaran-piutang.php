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

  <div class="content-wrapper tab-wrap mx-0">
    <div class="content-header bg-white px-4 py-2 position-fixed w-100">
      <div class="row pl-2">
      <span class="text-md text-olive">Penjualan</span>                
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
      </div>
      </div>
    </div>

    <section class="content" style="margin-top: 70px">
      <form id="form-<?= $id; ?>" class="form-horizontal">
      <input type="hidden" id="id" name="id" value="">
      <input type="hidden" id="notrans" name="notrans" value="">      
      <input type="hidden" id="status" name="status">      
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
          <label for="" class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">Sudah Terima Dari *</label>
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
        <div class="row pt-1">
          <label for="" class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">COA Kas/Bank [D] *</label>
          <div class="col-sm-3">
              <select id="coadebet" name="coadebet" class="form-control form-control-sm select2" data-trigger="manual" data-placement="top" style="width: 100%">
              </select>     
          </div>
          <div class="col-sm-2">
            <div class="input-group">
              <input id="totalrp" type="text" class="form-control form-control-sm numeric" value="0" data-trigger="manual" data-placement="auto">
              <div id="calcrp" class="input-group-append" role="button" title="Hitung Nilai Otomatis">
                  <div class="input-group-text"><i class="fa fa-calculator"></i></div>
              </div>            
            </div>
          </div>
          <div class="col-sm-1"></div>
          <label class="col-sm-1 col-form-label text-sm px-3 font-weight-normal">Uang</label>
          <div class="col-sm-1">
            <input type="hidden" id="iduangdebet" name="iduangdebet">
            <input id="uangdebet" type="text" class="form-control form-control-sm" readonly="" tabindex="-1" value="Rp">
          </div>
          <label class="col-sm-1 col-form-label text-sm px-3 font-weight-normal">Kurs</label>
          <div class="col-sm-1">
            <input id="kursdebet" type="text" class="form-control form-control-sm numeric" readonly="" tabindex="-1" value="1,00">
          </div>                                    
        </div>
        <div class="row pt-0">
          <label for="" class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">Tipe Pembayaran *</label>
          <div class="col-sm-3">
            <select id="tipebayar" name="tipebayar" class="form-control select2" style="width: 100%" data-trigger="manual" data-placement="top">
              <option class="text-sm" value="0">Tunai</option>                            
              <option class="text-sm" value="1">Giro</option>                                            
              <option class="text-sm" value="2">Transfer</option>                                            
            </select>       
          </div>
          <div class="giro col-sm-2 d-none">
            <input id="nocekgiro" type="text" class="form-control form-control-sm" placeholder="No. Cek/Giro">
          </div>
          <div class="giro col-sm-1 d-none"></div>
          <label class="giro col-sm-1 col-form-label text-sm px-3 font-weight-normal d-none">Tgl Giro</label>          
          <div class="giro col-sm-2 d-none">
            <div class="input-group date">
              <input type="text" id="tglcekgiro" name="tglcekgiro" class="form-control form-control-sm datepicker" autocomplete="off">
              <div id="dTglCek" class="input-group-append" role="button">
                  <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
              </div>
            </div>                
          </div>
          <div class="transfer col-sm-3 d-none"></div>          
          <label class="transfer col-sm-1 col-form-label text-sm px-3 font-weight-normal d-none">Bank</label>                    
          <div class="transfer col-sm-3 d-none">
            <select id="bank" name="bank" class="form-control select2" style="width: 100%" data-trigger="manual" data-placement="top">
            </select>       
          </div>          
        </div>
        <div id="lebihbayar" class="row pt-0 d-none">
          <label for="" class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">COA Selisih</label>
          <div class="col-sm-3">
              <select id="coaselisih" name="coaselisih" class="form-control form-control-sm select2" data-trigger="manual" data-placement="top" style="width: 100%">
              </select>     
          </div>
        </div>                                                                                                                                 
      </div>
    </section>    

    <section class="content">
      <div class="container-fluid left mt-2 pt-2">      
          <div class="card card-primary card-outline card-outline-tabs" style="box-shadow: none">
            <div class="card-header card-header-sm p-0 border-0">
              <ul class="nav nav-tabs bg-light" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item no-border mx-1">
                  <a class="nav-link text-sm active" id="btn-tab-faktur" data-toggle="pill" href="#tab-faktur" role="tab" aria-controls="tab-faktur" aria-selected="true" tabindex="-1" title="Data Tagihan"><i class="fas fa-list text-gray text-md"></i></a>
                </li>
                <li class="nav-item no-border">
                  <a class="nav-link text-sm" id="btn-tab-retur" data-toggle="pill" href="#tab-retur" role="tab" aria-controls="tab-retur" aria-selected="false" tabindex="-1" title="Data Retur">RTR</a>
                </li>
              </ul>
            </div>
            <div class="card-body card-outline-tabs-body">
              <div class="tab-content">
                <div class="tab-pane fade active show text-sm" id="tab-faktur" role="tabpanel" aria-labelledby="btn-tab-faktur">
                  <div class="row px-0 mt-3">                       
                  <div class="table-responsive">
                        <table id="tfaktur" class="table table-hover table-sm table-transaksi">
                          <thead class="bg-light">
                            <tr>
                              <th class="text-sm text-label text-center border-0" style="width: 150px">Nomor Inv</th>
                              <th class="text-sm text-label text-center border-0" style="width: 100px">Tanggal</th>
                              <th class="text-sm text-label text-center border-0" style="width: 140px">Total Tagihan</th>
                              <th class="text-sm text-label text-center border-0" style="width: 140px">Terbayar</th>
                              <th class="text-sm text-label text-center border-0" style="width: 140px">Jumlah Bayar</th>                              
                              <th class="text-sm text-label text-center border-0 d-none" style="width: 140px">Kas/Bank</th>
                              <th class="text-sm text-label text-center border-0" style="width: 140px">Diskon</th>
                              <th class="text-sm text-label text-center border-0" style="width: 140px">Retur</th>                              
                              <th class="text-sm text-label text-center border-0 d-none" style="width: 140px">PPH 23</th>
                              <th class="text-sm text-label text-center border-0 d-none" style="width: 140px">No. Bukti Potong</th>
                              <th class="text-sm text-label text-center border-0" style="width: 40px"></th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                          <tfoot>
                          </tfoot>
                        </table>
                  </div>
                </div> 
                <div class="row mt-2 pt-0">
                  <input type="hidden" id="idfaktur" name="idfaktur" value="">                    
                  <button type="button" id="bcarifaktur" class="btn btn-primary btn-step1 text-sm" data-trigger="manual" data-placement="auto">Ambil Data Tagihan</button>                  
                </div>                                 
                <div style='clear:both'></div>
                </div>
                <div class="tab-pane fade text-sm" id="tab-retur" role="tabpanel" aria-labelledby="btn-tab-retur">
                  <div class="row px-0 mt-3">                       
                  <div class="table-responsive">
                        <table id="tretur" class="table table-hover table-sm table-transaksi">
                          <thead class="bg-light">
                            <tr>
                              <th class="text-sm text-label text-center border-0" style="width: 150px">No. Retur</th>
                              <th class="text-sm text-label text-center border-0" style="width: 100px">Tanggal</th>
                              <th class="text-sm text-label text-center border-0" style="width: 140px">Total Transaksi</th>
                              <th class="text-sm text-label text-center border-0" style="width: 140px">Terbayar</th>
                              <th class="text-sm text-label text-center border-0" style="width: 140px">Jumlah Potong</th>
                              <th class="text-sm text-label text-center border-0" style="width: 40px"></th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                          <tfoot>
                          </tfoot>
                        </table>
                  </div>
                </div>
                <div class="row mt-2 pt-0">
                  <input type="hidden" id="idretur" name="idretur" value="">                                        
                  <button type="button" id="bcariretur" class="btn btn-primary btn-step1 text-sm" data-trigger="manual" data-placement="auto">Ambil Data Retur</button>                   
                </div>                                  
                <div style='clear:both'></div>
                </div>
                </div>
              </div>
            </div>
            <div class="row px-2">
              <div class="col-sm-2">
                  <input type="hidden" name="ttagihan" id="ttagihan" value="0">
                  <input type="hidden" name="tterbayar" id="tterbayar" value="0">                                  
              </div>                    
              <div class="col-sm-2 py-0">
                <div class="form-group">
                  <label class="text-sm px-1 font-weight-normal">Total Piutang Dibayar</label>
                  <input id="totalpembayaran" type="text" class="total form-control form-control-sm numeric border-0" value="0" data-trigger="manual" data-placement="auto" disabled>
                </div>                                
              </div>    
              <div class="col-sm-2 py-0">
                <div class="form-group">
                  <label class="text-sm px-1 font-weight-normal">Total Diskon</label>
                  <input id="totaldiskon" type="text" class="total form-control form-control-sm numeric border-0" value="0" data-trigger="manual" data-placement="auto" disabled>
                </div>
              </div>              
              <div class="col-sm-2 py-0">
                <div class="form-group">
                  <label class="text-sm px-1 font-weight-normal">Total Retur</label>
                  <input id="totalretur" type="text" class="total form-control form-control-sm numeric border-0" value="0" data-trigger="manual" data-placement="auto" disabled>
                </div>
              </div>
              <div class="col-sm-2 py-0">
                <div class="form-group">                  
                  <input type="hidden" id="idpph">
                  <input type="hidden" id="namapph">
                  <input type="hidden" id="nobupot">
                  <input type="hidden" id="statussetor" value="0">
                  <input type="hidden" id="triggerpph" value="0">                                                      
                  <label class="text-sm px-1 font-weight-normal">Total Pajak</label>
                  <div class="input-group" data-target-input="nearest">                
                    <input id="totalpajak" type="text" class="total form-control form-control-sm numeric border-0" value="0" data-trigger="manual" data-placement="auto" disabled>
                    <div id="bpajak" class="input-group-append" role="button" title="Isi Nilai Potongan Pajak" style="border-left:1px solid #ddd;">
                        <div class="input-group-text border-0"><i class="fa fa-ellipsis-h"></i></div>
                    </div>                                  
                  </div>
                </div>
              </div>
              <div class="col-sm-2 py-0">
                <div class="form-group">
                  <label class="text-sm px-1 font-weight-normal">Selisih Bayar</label>
                  <input id="totalselisih" type="text" class="total form-control form-control-sm numeric border-0" value="0" data-trigger="manual" data-placement="auto" disabled>
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
<script src="<? echo base_url('assets/plugins/toastr/toastr.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/input_hidden.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<!-- JS Custom -->
<script type="module" src="<? echo app_url('assets/dist/js/modul/transaksi/penjualan/pembayaran-piutang.js'); ?>"></script>
</body>
</html>