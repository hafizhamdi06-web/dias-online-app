<form method="post">
<input type="hidden" id="id" name="id">
<div class="modal-body">
<div class="row mx-1">
<label class="col-sm-2 col-form-label text-sm font-weight-normal">Kode *</label>                    
<div class="col-sm-4">
  <input type="search" class="form-control form-control-sm" id="nomor" name="nomor" autocomplete="off" data-trigger="manual" data-placement="auto">
</div>
<label class="col-sm-2 col-form-label text-sm font-weight-normal">Barcode</label>                    
<div class="col-sm-4">
  <input type="search" class="form-control form-control-sm" id="barcode" name="barcode" autocomplete="off" data-trigger="manual" data-placement="auto">
</div>
</div>                              
<div class="row mx-1">
<label class="col-sm-2 col-form-label text-sm font-weight-normal">Nama *</label>                    
<div class="col-sm-10">
  <input type="search" class="form-control form-control-sm" id="nama" name="nama" autocomplete="off" data-trigger="manual" data-placement="auto">
</div>
</div>                
<div class="row mt-3 mx-1">
<div class="col-sm-12">
  <div id="tabItem" class="card card-primary card-outline card-outline-tabs" style="box-shadow: none">
    <div class="card-header p-0">
      <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
        <li class="nav-item">
          <a class="nav-link text-sm active" id="btn-tab-menu" data-toggle="pill" href="#tab-menu" role="tab" aria-controls="tab-menu" aria-selected="true" tabindex="-1">Detail</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-sm" id="btn-tab-coa" data-toggle="pill" href="#tab-coa" role="tab" aria-controls="tab-coa" aria-selected="false" tabindex="-1">Pengaturan Akun</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-sm" id="btn-tab-satuan" data-toggle="pill" href="#tab-satuan" role="tab" aria-controls="tab-satuan" aria-selected="false" tabindex="-1">Multi Satuan</a>
        </li>        
        <li class="nav-item">
          <a class="nav-link text-sm" id="btn-tab-sa" data-toggle="pill" href="#tab-sa" role="tab" aria-controls="tab-sa" aria-selected="false" tabindex="-1">Saldo Awal</a>
        </li>                
      </ul>
    </div>
    <div class="card-body card-outline-tabs-body px-0 mx-0">
      <div class="tab-content">
        <div class="tab-pane active show text-sm" id="tab-menu" role="tabpanel" aria-labelledby="btn-tab-menu">
          <div class="row mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Stok Min</label>
            <div class="col-sm-4">
              <input id="stokmin" type="text" class="form-control form-control-sm qty" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Stok Total</label>
            <div class="col-sm-4">
              <input id="stoktotal" type="text" class="form-control form-control-sm qty" value="0" disabled>
            </div>                          
          </div>
          <div class="row mx-0 d-none">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Satuan Default *</label>
            <div class="col-sm-2">
              <select id="satuanDef" name="satuanDef" class="form-control select2 w-100" style="width:100%" data-trigger="manual" data-placement="auto">
              </select>                         
            </div>
          </div>          
          <div class="row mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Stok Maks</label>
            <div class="col-sm-4">
              <input id="stokmaks" type="text" class="form-control form-control-sm qty" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Reorder</label>
            <div class="col-sm-4">
              <input id="stokreorder" type="text" class="form-control form-control-sm qty" value="0">
            </div>                          
          </div>                        
          <div class="row mt-3 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Satuan *</label>
            <div class="col-sm-4">
              <select id="satuanD" name="satuanD" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto">
              </select>                         
            </div>            
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Harga Beli</label>
            <div class="col-sm-4">
              <input id="hargabeli" type="text" class="form-control form-control-sm numeric" value="0">
            </div>                          
          </div>                              
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Jenis Item</label>
            <div class="col-sm-4">
              <select id="jenis" name="jenis" class="form-control select2 w-100" style="width:100%" data-trigger="manual" data-placement="auto" required>
                <option value="0">Persediaan</option>
                <option value="1">Jasa</option>
              </select>                         
            </div>            
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Harga Jual</label>
            <div class="col-sm-4">
              <input id="hargajual1" type="text" class="form-control form-control-sm numeric" value="0">
            </div>                          
          </div>                                                      
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Tipe</label>
            <div class="col-sm-4">
              <select id="tipe" name="tipe" class="form-control select2 w-100" style="width:100%" data-trigger="manual" data-placement="auto" required>
                <option value="0">Barang Jadi</option>                              
                <option value="1">Barang Setengah Jadi</option>
                <option value="2">Bahan Baku</option>                                                                                            
              </select>                         
            </div>            
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Harga Jual 2</label>
            <div class="col-sm-4">
              <input id="hargajual2" type="text" class="form-control form-control-sm numeric" value="0">
            </div>                          
          </div>                                                                              
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Status</label>
            <div class="col-sm-4">
              <select id="status" name="status" class="form-control select2 w-100" style="width:100%" required>
                <option value="0">Aktif</option>
                <option value="1">Tidak Aktif</option>
                <option value="2">Tidak Terpakai</option>
              </select>                         
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Harga Jual 3</label>
            <div class="col-sm-4">
              <input id="hargajual3" type="text" class="form-control form-control-sm numeric" value="0">
            </div>                          
          </div>
          <div class="row mt-0 mx-0">
            <div class="col-sm-6"></div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Harga Jual 4</label>
            <div class="col-sm-4">
              <input id="hargajual4" type="text" class="form-control form-control-sm numeric" value="0">
            </div>                          
          </div>                                                                                        
          <div class="row mt-0 mx-0 d-none">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Kategori</label>
            <div class="col-sm-4">
              <select id="kategori" name="kategori" class="form-control select2 w-100" style="width:100%">
              </select>                         
            </div> 
          </div>                                                                                        
          <div class="row mt-0 mx-0 d-none">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Sub Kategori</label>
            <div class="col-sm-4">
              <select id="subkategori" name="subkategori" class="form-control select2 w-100" style="width:100%">
              </select>                         
            </div>
          </div>
          <div style='clear:both'></div>
        </div>
        <div class="tab-pane fade text-sm" id="tab-coa" role="tabpanel" aria-labelledby="btn-tab-coa">
          <div class="row mx-0">
            <label id="labelcoa1" class="col-sm-3 col-form-label text-sm font-weight-normal">Akun Persediaan *</label>
            <div class="col-sm-9">
              <select id="coapersediaan" name="coapersediaan" class="form-control select2 w-100" required>
              </select>                         
            </div>                                                    
          </div>                                                                                                             
          <div class="row mx-0">
            <label class="col-sm-3 col-form-label text-sm font-weight-normal">Akun Pendapatan *</label>
            <div class="col-sm-9">
              <select id="coapendapatan" name="coapendapatan" class="form-control select2 w-100" required>
              </select>                         
            </div>                                                    
          </div>
          <div id="divcoahpp" class="row mx-0">
            <label class="col-sm-3 col-form-label text-sm font-weight-normal">Akun HPP *</label>
            <div class="col-sm-9">
              <select id="coahpp" name="coahpp" class="form-control select2 w-100" required>
              </select>                         
            </div>                                                    
          </div>                                                                     
          <div style='clear:both'></div>
        </div>
        <div class="tab-pane fade text-sm" id="tab-satuan" role="tabpanel" aria-labelledby="btn-tab-satuan">
          <div class="row mx-2 mt-2">
            <div class="col-sm-12 text-center">
              Fitur multi satuan tidak tersedia.
            </div>
          </div>
          <div style='clear:both'></div>
        </div>        
        <div class="tab-pane fade text-sm" id="tab-sa" role="tabpanel" aria-labelledby="btn-tab-sa">
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header card-header-sm p-0 border-bottom-0">
              </div>
              <div class="card-body card-outline-tabs-body px-0 mx-0 py-0 my-0">
                <div class="tab-content">
                  <div class="table-responsive bg-light" tabindex="-1" style="border:1px solid #dee2e6;height:calc(100vh - 420px);overflow: auto;">
                        <table id="tsaldo" class="table table-hover table-sm table-transaksi">
                          <thead class="bg-primary">
                            <tr>
                              <th class="text-sm text-label text-left px-1 border-0 font-weight-normal" style="width: 100px">Nomor #</th>
                              <th class="text-sm text-label text-left px-1 border-0 font-weight-normal" style="width: 100px">Tanggal</th>
                              <th class="text-sm text-label text-left px-1 border-0 font-weight-normal" style="width: 80px">Gudang</th>
                              <th class="text-sm text-label text-right px-1 border-0 font-weight-normal" style="width: 120px">Harga Pokok</th>
                              <th class="text-sm text-label text-right px-1 border-0 font-weight-normal" style="width: 70px">Qty</th>
                              <th class="text-sm text-label text-left px-1 border-0 font-weight-normal" style="width: 200px">Kontak</th>
                              <th class="text-sm text-label text-center border-0" style="width: 40px"></th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                          <tfoot>
                          </tfoot>
                        </table>
                  </div>
                  <div class="row mt-2 px-2">
                      <button type="button" id="baddrow" class="btn btn-light text-sm">
                          <i class="fa fa-plus px-1 text-primary"></i> Tambah
                      </button>
                  </div>
                </div>
              </div>
            </div>  
            <div style='clear:both'></div>
        </div>                
      </div>
    </div>
  </div>
</div>
</div>
</div>
<div class="modal-footer">
  <div class="form-group">
      <div class="col-sm-offset-3">
          <a class="text-sm mx-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal</a>
          <button type="button" id="submit" name="submit" class="btn btn-primary btn-sm">Simpan</button>
      </div>
  </div>                
</div>
</form>                            
<script src="<? echo app_url('assets/dist/js/modul/master/form-item.js'); ?>"></script> 