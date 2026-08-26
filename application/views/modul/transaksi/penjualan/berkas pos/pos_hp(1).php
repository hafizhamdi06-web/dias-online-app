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

    <form id="form-<?= $id; ?>" class="form-horizontal">
    <input type="hidden" id="id" name="id" value="">
    <input type="hidden" id="status" name="status">
    <input type="hidden" class="noclear" id="multidivisi" name="multidivisi" value="<?= $multidivisi ?>">
    <input type="hidden" class="noclear" id="multiproyek" name="multiproyek" value="<?= $multiproyek ?>">
    <input type="hidden" class="noclear" id="multisatuan" name="multisatuan" value="<?= $multisatuan ?>">
    <input type="hidden" class="noclear" id="multikurs" name="multikurs" value="<?= $multikurs ?>">        
    <input type="hidden" class="noclear" id="decimalqty" name="decimalqty" value="<?= $decimalqty ?>">                      
    

    
    
    
    <section class="content" style="margin-top:70px"> 

      <div class="container-fluid pt-4"> 
  
          
         <div class="form-group row my-0">
            <label class="col-4 col-form-label text-sm px-3 font-weight-normal">No Transaksi</label>
            <div class="col-8">
                <input type="text" id="nomor" name="nomor" class="form-control form-control-sm" placeholder="New Trans"   disabled> 
              
            </div>          
          </div>
          
          
            
         <div class="form-group row my-0">
            <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Tanggal *</label>
            <div class="col-8">
                  <div class="input-group date">
                    <input type="text" id="tgl" name="tgl" class="form-control form-control-sm datepicker" autocomplete="off">
                    <div id="dTgl" class="input-group-append">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                  </div>   
            </div>          
          </div>
          
          
          
          
          <div class="form-group row my-0">
            <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Pelanggan *</label>
            <div class="col-8">
                  <div class="input-group" data-target-input="nearest">
                    <input type="hidden" id="idkontak" name="idkontak">                    
                    <input type="text" id="kontak" name="kontak" class="form-control form-control-sm" disabled>
                    <div id="carikontak" class="input-group-append" role="button">
                        <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                    </div>
                  </div>                
            </div>
            <div id="namakontak" class="col-sm-2 text-sm overflow-hidden text-nowrap pt-1"></div>            
          </div>
          
          <div class="form-group row my-0">
            <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Karyawan *</label> 
             <div class="col-8">
                  <div class="input-group" data-target-input="nearest">
                    <input type="hidden" id="idsalesman" name="idsalesman">                    
                    <input type="text" id="salesman" name="salesman" class="form-control form-control-sm" disabled>
                    <div id="carisalesman" class="input-group-append" role="button">
                        <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                    </div>
                  </div>  
            </div>  
          </div>   

          
             <div class="card" > 
              <div class="card-body">   
                  
                 <div class="form-group row my-0">
                 <label class="col-3 col-form-label text-sm px-1 font-weight-normal">Produk</label> 
                    <div class="col-9">
                     <div class="input-group" data-target-input="nearest">
                        <input type="hidden" id="idbarang" name="idbarang">                    
                        <input type="text" id="barang" name="barang" class="form-control form-control-sm" disabled>
                        <div id="caribarang" class="input-group-append" role="button">
                            <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                        </div>
                      </div>  
                    </div>   
                  </div> 
                  
                     <div class="form-group row my-0">
                        <label class="col-3 col-form-label text-sm px-1 font-weight-normal">Qty</label>
                        <div class="col-3">
                            <input id="qty" type="text" class="total form-control form-control-sm numeric " value="0">
                        </div>  
                        <label class="col-2 col-form-label text-sm px-1 font-weight-normal">Harga</label>
                        <div class="col-4">
                            <input id="harga" type="text" class="total form-control form-control-sm numeric " value="0">
                        </div>   
                   </div> 
                   
                    <div class="form-group row my-0">
                    
                    <label class="col-3 col-form-label text-sm px-1 font-weight-normal">Disc 1</label>
                    <div class="col-3">
                        <input id="dis1" type="text" class="total form-control form-control-sm numeric " value="0">
                    </div>                
                  </div> 
                   
                    <div class="form-group row my-0"> 
                    <label class="col-3 col-form-label text-sm px-1 font-weight-normal">Disc 2</label>
                    <div class="col-3">
                        <input id="dis2" type="text" class="total form-control form-control-sm numeric " value="0">
                    </div>   
                    <label class="col-2 col-form-label text-sm px-1 font-weight-normal">Discount</label>
                    <div class="col-4">
                        <input id="diskon" type="text" class="total form-control form-control-sm numeric " value="0">
                    </div>             
                  </div>  
              
                 <div class="form-group row my-0">
                    <label class="col-3 col-form-label text-sm px-1 font-weight-normal">Sub Total</label>
                    <div class="col-9">
                        <input id="subtotal" type="text" class="total form-control form-control-sm numeric " value="0">
                    </div>          
                  </div>
                
                 
                  
                   <div class="form-group row my-0">
                 <label class="col-3 col-form-label text-sm px-1 font-weight-normal">Dokter</label> 
                    <div class="col-9">
                     <div class="input-group" data-target-input="nearest">
                        <input type="hidden" id="iddokter" name="iddokter">                    
                        <input type="text" id="dokter" name="dokter" class="form-control form-control-sm" disabled>
                        <div id="caridokter" class="input-group-append" role="button">
                            <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                        </div>
                      </div>  
                    </div>   
                  </div> 
                  
                   <div class="form-group row my-0">
                 <label class="col-3 col-form-label text-sm px-1 font-weight-normal">Operator</label> 
                    <div class="col-9">
                     <div class="input-group" data-target-input="nearest">
                        <input type="hidden" id="idoperator" name="idoperator">                    
                        <input type="text" id="operator" name="operator" class="form-control form-control-sm" disabled>
                        <div id="carioperator" class="input-group-append" role="button">
                            <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                        </div>
                      </div>  
                    </div>   
                  </div> 
                  
                  
                   <div class="form-group row my-0">
                        <label class="col-3 col-form-label text-sm px-1 font-weight-normal">No Ref</label>
                        <div class="col-3">
                            <input id="noref" type="text" class="form-control form-control-sm"  autocomplete="off">
                        </div> 
                        
                        <label class="col-3 col-form-label text-sm px-1 font-weight-normal">No IC</label>
                        <div class="col-3">
                            <input id="noic" type="text" class="form-control form-control-sm"  autocomplete="off">
                        </div>   
                   </div> 
                  
                   <div class="form-group row my-0">
                        <label class="col-3 col-form-label text-sm px-1 font-weight-normal">No Paket</label>
                        <div class="col-3">
                            <input id="nopaket" type="text" class="form-control form-control-sm"  autocomplete="off">
                        </div> 
                        
                        <label class="col-3 col-form-label text-sm px-1 font-weight-normal">Kedatangan ke</label>
                        <div class="col-3">
                            <input id="kedatanganke" type="text" class="form-control form-control-sm"  autocomplete="off">
                        </div>   
                   </div> 
                  
                   <div class="form-group row my-0">
                        <label class="col-3 col-form-label text-sm px-1 font-weight-normal">Nama Paket</label>
                        <div class="col-9">
                        <input type="hidden" id="idpaket" name="idpaket">  
                            <input id="paket" type="text" class="form-control form-control-sm"  autocomplete="off">
                        </div>  
                   </div> 
                  
                   <div class="form-group row my-0">
                        <label class="col-3 col-form-label text-sm px-1 font-weight-normal">Nama Promo</label>
                        <div class="col-9">
                        <input type="hidden" id="idpromo" name="idpromo">  
                            <input id="promo" type="text" class="form-control form-control-sm"  autocomplete="off">
                        </div>  
                   </div> 
                   
                   
                  
                   <div class="form-group row my-0">
                 <label class="col-3 col-form-label text-sm px-1 font-weight-normal">Referal</label> 
                    <div class="col-9">
                     <div class="input-group" data-target-input="nearest">
                        <input type="hidden" id="idreferal" name="idreferal">                    
                        <input type="text" id="referal" name="referal" class="form-control form-control-sm" disabled>
                        <div id="carireferal" class="input-group-append" role="button">
                            <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                        </div>
                      </div>  
                    </div>   
                  </div> 
                  
                  
                  
                   <div class="form-group row my-0">
                 <label class="col-3 col-form-label text-sm px-1 font-weight-normal">AOS PRO</label> 
                    <div class="col-9">
                     <div class="input-group" data-target-input="nearest">
                        <input type="hidden" id="idaospro" name="idaospro">                    
                        <input type="text" id="aospro" name="aospro" class="form-control form-control-sm" disabled>
                        <div id="cariaospro" class="input-group-append" role="button">
                            <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                        </div>
                      </div>  
                    </div>   
                  </div> 
                  
                  
                  
                   <div class="form-group row my-0">
                 <label class="col-3 col-form-label text-sm px-1 font-weight-normal">Recom PRO</label> 
                    <div class="col-9">
                     <div class="input-group" data-target-input="nearest">
                        <input type="hidden" id="idrecompro" name="idrecompro">                    
                        <input type="text" id="recompro" name="recompro" class="form-control form-control-sm" disabled>
                        <div id="carirecompro" class="input-group-append" role="button">
                            <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                        </div>
                      </div>  
                    </div>   
                  </div>  
              </div>   
            </div>



        </div>
        
     
    <section class="content mt-4">
      <div class="container-fluid">
          
          
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
                <div class="table-responsive pt-0" tabindex="-1">
                  <table id="tdetil" class="table table-hover table-sm table-transaksi"> 
                    <tbody>
                    </tbody>
                    <tfoot>
                    </tfoot>
                  </table>
                <button type="button" id="baddrow" class="btn btn-primary btn-step1 text-sm mb-2"><i class="fa fa-plus px-2"></i>Tambah Data</button>
                <span id="loader-detil" class="ml-2 text-sm d-none"><i class="fas fa-spinner fa-spin mx-2"></i>loading item data...</span>
                </div>
                </div>               
              </div>
            </div>
          </div>
 
          
          
          <div class="row px-1 ">
            <div id="col-clear" class="col-sm-8"></div>                    
                                                                                                   
            <div class="col-sm-2 col-overlap-2">
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
<script type="module" src="<? echo app_url('assets/dist/js/modul/transaksi/penjualan/pos_hp.js'); ?>"></script>
</body>
</html>