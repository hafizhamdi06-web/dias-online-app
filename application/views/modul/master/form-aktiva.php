<form class='form-horizontal' method="post">
<input type="hidden" id="id" name="id">
<div class="modal-body">
<div class="row mx-2">
  <label for="" class="col-sm-2 col-form-label text-sm font-weight-normal">Kode *</label>                    
  <div class="col-sm-4">
    <input type="text" class="form-control form-control-sm" id="kode" name="kode" autocomplete="off" data-trigger="manual" data-placement="auto">
  </div>
  <label for="" class="col-sm-2 col-form-label text-sm font-weight-normal">No Serial</label>                    
  <div class="col-sm-4">
    <input type="text" class="form-control form-control-sm" id="serial" name="serial" autocomplete="off">
  </div>              
</div>                              
<div class="row mx-2">
  <label for="" class="col-sm-2 col-form-label text-sm font-weight-normal">Nama *</label>                    
  <div class="col-sm-4">
    <input type="text" class="form-control form-control-sm" id="nama" name="nama" autocomplete="off" data-trigger="manual" data-placement="auto">
  </div>
  <label for="" class="col-sm-2 col-form-label text-sm font-weight-normal">Divisi</label>                    
  <div class="col-sm-4">
    <select id="divisi" name="divisi" class="form-control select2" style="width:100%">
    </select>                         
  </div>
</div>                
<div class="row mx-2">
  <label for="" class="col-sm-2 col-form-label text-sm font-weight-normal">Kelompok *</label>                    
  <div class="col-sm-4">
    <select id="kelaktiva" name="kelaktiva" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto">
    </select>                         
  </div>
  <label for="" class="col-sm-2 col-form-label text-sm font-weight-normal">Lokasi</label>                    
  <div class="col-sm-4">
    <input type="text" class="form-control form-control-sm" id="lokasi" name="lokasi" autocomplete="off">
  </div>              
</div>                            
<div class="row mt-4">
<div class="col-sm-12">
    <div id="tabAktiva" class="card card-primary card-outline card-outline-tabs" style="box-shadow: none">
      <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
          <li class="nav-item bg-white">
            <a class="nav-link text-sm active" id="btn-tab-menu" data-toggle="pill" href="#tab-menu" role="tab" aria-controls="tab-menu" aria-selected="true">Aktiva</a>
          </li>
          <li class="nav-item bg-white">
            <a class="nav-link text-sm" id="btn-tab-coa" data-toggle="pill" href="#tab-coa" role="tab" aria-controls="tab-coa" aria-selected="false">Pengaturan Akun (COA)</a>
          </li>
        </ul>
      </div>
        <div class="card-body card-outline-tabs-body px-2">
        <div class="tab-content">
          <div class="tab-pane fade active show text-sm" id="tab-menu" role="tabpanel" aria-labelledby="btn-tab-menu">
            <div class="row mt-1 mx-2">
              <label for="" class="col-sm-2 col-form-label text-sm font-weight-normal">Tgl Beli *</label>
              <div class="col-sm-4">
                <div style="position:relative">
                <div class="input-group date">
                  <input type="text" class="form-control form-control-sm datepicker" id="tglBeli" name="tglBeli" data-trigger="manual" data-placement="auto">
                  <div id="dTglBeli" class="input-group-append" role="button">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>
                </div>                
              </div>
              <label for="" class="col-sm-2 col-form-label text-sm font-weight-normal">Tgl Pakai *</label>
              <div class="col-sm-4">
                <div style="position:relative">
                <div class="input-group date">
                  <input type="text" class="form-control form-control-sm datepicker" id="tglPakai" name="tglPakai" data-trigger="manual" data-placement="auto">
                  <div id="dTglPakai" class="input-group-append" role="button">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>
                </div>                
              </div>                          
            </div>                              
            <div class="row mt-0 mx-2">
              <label for="" class="col-sm-2 col-form-label text-sm font-weight-normal">Perolehan *</label>
              <div class="col-sm-4">
                <input type="tel" class="form-control form-control-sm numeric" value="0" data-trigger="manual" data-placement="auto" autocomplete="off" id="hargabeli" name="hargabeli">
              </div>
              <label for="" class="col-sm-2 col-form-label text-sm font-weight-normal">Beban/bln</label>
              <div class="col-sm-4">
                <input type="tel" class="form-control form-control-sm numeric" value="0" autocomplete="off" id="beban" name="beban" readonly>
              </div>                          
            </div>                                                      
            <div class="row mt-0 mx-2">
              <label for="" class="col-sm-2 col-form-label text-sm font-weight-normal">Umur *</label>
              <div class="col-sm-1">
                  <input type="tel" class="form-control form-control-sm numeric numeric-single" value="0" id="utahun" name="utahun" data-trigger="manual" data-placement="auto">
              </div>
              <label for="" class="col-sm-1 col-form-label text-sm font-weight-normal">Thn</label>
              <div class="col-sm-1">
                  <input type="tel" class="form-control form-control-sm numeric numeric-single" value="0" id="ubulan" name="ubulan" data-trigger="manual" data-placement="auto">
              </div>                
              <label for="" class="col-sm-1 col-form-label text-sm font-weight-normal">Bln</label>
              <label for="" class="col-sm-2 col-form-label text-sm font-weight-normal">Akumulasi</label>
              <div class="col-sm-4">
                <input type="tel" class="form-control form-control-sm numeric" value="0" autocomplete="off" id="totalakum" name="totalakum" readonly>
              </div>                          
            </div>                                                                              
            <div class="row mt-0 mx-2">
              <label for="" class="col-sm-2 col-form-label text-sm font-weight-normal">Nilai Residu</label>
              <div class="col-sm-4">
                <input type="tel" class="form-control form-control-sm numeric" value="0" autocomplete="off" id="residu" name="residu">
              </div>
              <label for="" class="col-sm-2 col-form-label text-sm font-weight-normal">Penyusutan</label>
              <div class="col-sm-4">
                <select id="metode" name="metode" class="form-control select2 w-100" required>
                  <option value="0">Tanpa Penyusutan</option>
                  <option value="1" selected>Garis Lurus</option>
                  <option value="2">Saldo Menurun</option>                                                            
                </select>                         
              </div>                          
            </div>
            <div class="row mt-0 mx-2">
              <label for="" class="col-sm-2 col-form-label text-sm font-weight-normal">Nilai Buku</label>
              <div class="col-sm-4">
                <input type="tel" class="form-control form-control-sm numeric" value="0" autocomplete="off" id="nilaibuku" name="nilaibuku" readonly>
              </div>                          
            </div>                                                                                        
            <div class="row mt-0 mx-2">
              <label for="" class="col-sm-2 col-form-label text-sm font-weight-normal">Qty *</label>
              <div class="col-sm-4">
                <input type="tel" class="form-control form-control-sm numeric" value="1" id="qty" name="qty">
              </div>
            </div>
            <div class="row mt-2 mx-2">
              <div class="col-sm-12 py-1">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="chkintangible">
                  <label class="form-check-label text-sm" for="chkintangible">Aktiva Tidak Berwujud</label>
                </div>                            
              </div>
            </div>                        
            <div class="row mt-0 mx-2">
              <div class="col-sm-12 py-1">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="chk15">
                  <label class="form-check-label text-sm" for="chk15">Perolehan Diatas Tanggal 15 Dibebankan ke Bulan Berikutnya</label>
                </div>                            
              </div>
            </div>                                                
            <div style='clear:both'></div>
          </div>
          <div class="tab-pane fade text-sm" id="tab-coa" role="tabpanel" aria-labelledby="btn-tab-coa">
            <div class="row mt-2 mx-2">
              <label for="" class="col-sm-3 col-form-label text-sm font-weight-normal">Aktiva</label>
              <div class="col-sm-7">
                <select id="coaaktiva" name="coaaktiva" class="form-control select2 w-100">
                </select>                         
              </div>                          
            </div>
            <div class="row mt-0 mx-2">
              <label for="" class="col-sm-3 col-form-label text-sm font-weight-normal">Akum. Penyusutan</label>
              <div class="col-sm-7">
                <select id="coapenyusutan" name="coapenyusutan" class="form-control select2 w-100">
                </select>                         
              </div>                          
            </div>
            <div class="row mt-0 mx-2">
              <label for="" class="col-sm-3 col-form-label text-sm font-weight-normal">Biaya Penyusutan</label>
              <div class="col-sm-7">
                <select id="coabiaya" name="coabiaya" class="form-control select2 w-100">
                </select>                         
              </div>                          
            </div>                                                                        
            <div class="row mt-0 mx-2">
              <label for="" class="col-sm-3 col-form-label text-sm font-weight-normal">Write Off</label>
              <div class="col-sm-7">
                <select id="coawo" name="coawo" class="form-control select2 w-100">
                </select>                         
              </div>                          
            </div>                                                                                                
            <div style='clear:both'></div>
          </div>
        </div>
      </div>
      <!-- /.card -->
    </div>
</div>
</div>
</div>
<div class="modal-footer">
    <div class="form-group">
        <div class="col-sm-offset-3">
            <a class="text-sm mx-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal</a>
            <button type="button" id="submit" name='submit' class="btn btn-primary btn-sm">Simpan</button>
        </div>
    </div>                
</div>
</form>
<script src="<? echo app_url('assets/dist/js/modul/master/form-aktiva.js'); ?>"></script> 