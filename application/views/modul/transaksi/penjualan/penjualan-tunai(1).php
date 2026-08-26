
<body id="<?= $id; ?>" class="layout-fixed bg-transparent overflow-hidden" data-panel-auto-height-mode="height">
  <!-- Custom CSS -->  
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/transaksi-page.css');?>">  

  <style scoped>
    .container-absolute{
      margin-top: -105px;
    }
    .container-absolute input,
    .container-absolute input:focus{
      background-color: transparent;
      font-weight: bold;
      font-size: 2.1rem;
      border:0px;
      box-shadow: none;
    } 
    @media (max-width:767px){
      .container-absolute{
        margin-top: -210px;
      }
    }       
  </style>

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
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="chkBarcode" tabindex="-1">
          <label class="form-check-label text-sm" for="chkBarcode" role="button">Barcode [F12]</label>
        </div>         
      </div>
      </div>
    </div>

    <form id="form-<?= $id; ?>" class="form-horizontal">
    <input type="hidden" id="id" name="id" value="">     
    <input type="hidden" id="notrans" name="notrans" value="">                                      
    <input type="hidden" id="status" name="status">                                     
    <input type="hidden" id="icetakpos" name="icetakpos" class="default">
    <input type="hidden" id="ipajakpos" name="ipajakpos" class="default">
    <input type="hidden" id="ikontakpos" name="ikontakpos" class="default">
    <input type="hidden" id="ikontakposkode" name="ikontakposkode" class="default">
    <input type="hidden" id="ikontakposnama" name="ikontakposnama" class="default">            
    <section class="content" style="margin-top: 70px">
      <div class="container-fluid pt-4">
          <div class="form-group row my-0">
            <label for="" class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">No Transaksi *</label>
            <div class="col-sm-2">
              <input type="text" id="nomor" name="nomor" class="form-control form-control-sm" placeholder="[Auto]" autocomplete="off">            
            </div>
          </div> 
          <div class="form-group row my-0">
            <label for="" class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">Tanggal *</label>
            <div class="col-sm-2">
              <div class="input-group date">
                <input type="text" id="tgl" name="tgl" class="form-control form-control-sm datepicker" autocomplete="off">
                <div id="dTgl" class="input-group-append" role="button">
                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                </div>
              </div>                
            </div>
          </div>
          <div class="form-group row my-0">
            <label for="" class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">Pelanggan [F1] *</label>
            <div class="col-sm-2">
                  <div class="input-group" data-target-input="nearest">
                    <input type="hidden" id="idkontak" name="idkontak">                    
                    <input type="text" id="kontak" name="kontak" class="form-control form-control-sm" autocomplete="off" data-trigger="manual" data-placement="auto">
                    <div id="carikontak" class="input-group-append" role="button">
                        <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                    </div>
                  </div>                
            </div>
            <div id="namakontak" class="col-sm-3 text-sm overflow-hidden text-nowrap pt-1"></div>                        
          </div>           
      </div>
      <div class="container-fluid container-absolute">
        <div class="row">
          <div class="col-sm-4"></div>
          <div class="col-sm-8">
            <h4><input id="ttrans" type="text" class="total form-control numeric text-right" tabindex="-1" value="0" readonly></h4>
          </div>
        </div>
      </div>                                                                         
    </section>

    <section class="content pt-1">
      <div class="container-fluid">
        <div class="row px-2"> 
          <div class="card card-primary card-outline card-outline-tabs mt-2" style="box-shadow: none">
            <div class="card-header card-header-sm p-0 border-bottom-0">
              <ul class="nav nav-tabs bg-light" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item no-border mx-1">
                  <a class="nav-link text-sm active" id="btn-tab-menu" data-toggle="pill" href="#tab-menu" role="tab" aria-controls="tab-menu" aria-selected="true" tabindex="-1" title="Data Transaksi"><i class="fas fa-list text-gray text-md"></i></a>
                </li>
                <li class="nav-item no-border col-sm-12">
                  <div id="dbarcode" class="row d-none px-2 pt-4 bg-white">
                    <div class="col-sm-4">
                        <input id="kodeitem" type="search" class="form-control form-control-sm" placeholder="Kode Item / UPC [F3]" autocomplete="off">
                    </div>
                  </div>                  
                </li>
              </ul>
            </div>
            <div class="card-body card-outline-tabs-body">
              <div class="tab-content">
                <div class="row py-0 my-0">
                <div class="table-responsive mt-0" tabindex="-1">
                      <table id="tdetil" class="table table-hover table-sm table-transaksi">
                        <thead class="bg-light">
                          <tr>
                            <th class="text-sm text-label text-center border-0" style="width: 250px">Nama</th>
                            <th class="text-sm text-label text-center border-0" style="width: 80px">Qty</th>
                            <th class="text-sm text-label text-center border-0" style="width: 90px">Satuan</th>
                            <th class="text-sm text-label text-center border-0" style="width: 170px">Harga</th>
                            <th class="text-sm text-label text-center border-0" style="width: 170px">Diskon</th>
                            <th class="text-sm text-label text-center border-0" style="width: 60px">Disc(%)</th>
                            <th class="text-sm text-label text-center border-0" style="width: 170px">Jumlah</th>
                            <th class="text-sm text-label text-center border-0" style="width: 110px">Gudang</th>                            
                            <th class="text-sm text-label text-center border-0" style="width: 40px"></th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="9" class="py-1 my-0">
                              <span id="loader-detil" class="text-sm d-none"><i class="fas fa-spinner fa-spin"></i>Loading item data...</span>
                            </td>
                          </tr>
                        </tfoot>
                      </table>
                </div>
                </div>
              </div>
            </div>
            </div>
          </div>
          <div class="row mt-0 px-2">
            <div class="col-sm-3 d-none">
              <div class="form-group">
                <label class="text-sm px-2 font-weight-normal">Pajak *</label>
                  <select id="pajak" name="pajak" class="form-control form-control-sm select2" style="width:100%">
                    <option value="0">Tanpa Pajak</option>
                    <option value="1">Belum Termasuk Pajak</option>
                    <option value="2">Termasuk Pajak</option>
                  </select>     
              </div>
            </div>
            <div class="col-sm-4"></div>
            <div class="col-sm-2 d-none">
              <div class="form-group">
                <label class="text-sm px-2 font-weight-normal">Total Qty</label>
                <input id="tqty" type="text" class="total form-control form-control-sm numeric border-0" value="0" disabled>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="text-sm px-2 font-weight-normal">Sub Total</label>
                <input id="tsubtotal" type="text" class="total form-control form-control-sm numeric border-0" value="0" disabled>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="text-sm px-2 font-weight-normal">Pajak</label>
                <input type="hidden" id="nilaipajak" value="10" class="nilaipajak">                                
                <input id="tpajak" type="text" class="total form-control form-control-sm numeric border-0" value="0" disabled>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <input type="hidden" id="bayarcash" value="0">
                <input type="hidden" id="bayardebit" value="0"> 
                <input type="hidden" id="bayardebitbank">
                <input type="hidden" id="bayardebitbankn">                
                <input type="hidden" id="bayardebitno">                                                                               
                <input type="hidden" id="bayarcredit" value="0">                                
                <input type="hidden" id="bayarcreditbank">
                <input type="hidden" id="bayarcreditbankn">                
                <input type="hidden" id="bayarcreditno"> 
                <input type="hidden" id="tbayartrigger" value="0">                                                                        
                <label class="text-sm px-2 font-weight-normal">Pembayaran [F8]</label>
                <div class="input-group" data-target-input="nearest">                
                  <input id="tbayar" type="text" class="total form-control form-control-sm numeric border-0" value="0" data-trigger="manual" data-placement="auto" disabled>
                  <div id="bpembayaran" class="input-group-append" role="button" style="border-left:1px solid #ddd;">
                      <div class="input-group-text border-0"><i class="fa fa-ellipsis-h"></i></div>
                  </div>                                  
                </div>
              </div>
            </div>            
            <div class="col-sm-2">
              <div class="form-group">
                <label class="text-sm px-2 font-weight-normal">Sisa</label>
                <input id="tsisa" type="text" class="total form-control form-control-sm numeric border-0" value="0" data-trigger="manual" data-placement="auto" disabled>
              </div>
            </div>                                                                                                
          </div>          
          <div class="row mt-0 px-2 d-none">
            <div class="col-sm-8"></div>            
            <div class="col-sm-2 d-none">
              <div class="form-group">
                <label class="text-sm px-2 font-weight-normal">Total Transaksi</label>
                <input id="ttrans" type="text" class="total form-control form-control-sm numeric border-0" value="0" disabled>
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
<script type="module" src="<? echo app_url('assets/dist/js/modul/transaksi/penjualan/penjualan-tunai.js'); ?>"></script>
</body>
</html>