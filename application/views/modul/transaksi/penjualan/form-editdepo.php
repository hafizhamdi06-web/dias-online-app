 <form class='form-horizontal' method="post">
  <input type="hidden" id="id" name="id">
  <div class="modal-body">
  <div class="row mx-2">
    <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">No Depo</label>                    
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm kuncitext" placeholder="[Auto]" id="nodepo" name="nodepo" autocomplete="off" data-trigger="manual" data-placement="auto" >
    </div>
    <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">No IP</label>                    
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm kuncitext" placeholder="" id="noip" name="noip" autocomplete="off" data-trigger="manual" data-placement="auto">
    </div>
  </div>                    
  <div class="row mx-2">
    <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Tanggal Depo</label>                    
    <div class="col-sm-4">
       
                  <div class="input-group date">
                    <input type="text" id="tanggaldepo" name="tanggaldepo" class="form-control form-control-sm datepicker kuncitext" autocomplete="off">
                    <div id="dTgl" class="input-group-append">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                  </div>                
                  
                  
    </div>
    <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Tanggal IP</label>                    
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm kuncitext" placeholder="" id="tanggalip" name="tanggalip" autocomplete="off" data-trigger="manual" data-placement="auto">
    </div>
  </div>  
  <div class="row mx-2">
    <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Cabang</label>                    
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm kuncitext" placeholder="" id="cabang" name="cabang" autocomplete="off" data-trigger="manual" data-placement="auto">
      <input type="hidden" class="form-control form-control-sm" placeholder="" id="idcabang" name="idcabang">
    </div>
  
    <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Pasien</label>                    
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm kuncitext"  placeholder="" id="namapasien" name="namapasien" autocomplete="off" data-trigger="manual" data-placement="auto">
    </div>
  </div> 
  
   
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header card-header-sm p-0 border-bottom-0"> Nama Tindakan
              </div>
              <div class="card-body card-outline-tabs-body px-0 mx-0 py-0 mt-2 w-100">
                <div class="tab-content">
                  <div class="table-responsive" tabindex="-1" style="border:1px solid #dee2e6;height:calc(100vh - 750px);overflow: auto;">
                        <table id="tdatatindakan" class="table table-hover table-sm table-transaksi">
                          <thead class="bg-light">
                            <tr>
                              <th class="text-sm text-label text-left px-1 border-0" style="width: 80px">Tindakan #</th>
                              <th class="text-sm text-label text-right px-1 border-0" style="width: 40px">Qty</th>
                              <th class="text-sm text-label text-right px-1 border-0" style="width: 80px">Nilai</th>
                              <th class="text-sm text-label text-left px-1 border-0" style="width: 40px">No Ref</th> 
                              <th class="text-sm text-label text-center border-0" style="width: 30px">Pilih</th>
                              <th class="text-sm text-label text-center border-0" style="width: 30px">Reset</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                          <tfoot>
                          </tfoot>
                        </table>
                  </div> 
                </div>
              </div>
            </div>  
            <div style='clear:both'></div> 
  
   
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header card-header-sm p-0 border-bottom-0"> Data Alkes
              </div>
              <div class="card-body card-outline-tabs-body px-0 mx-0 py-0 mt-2 w-100">
                <div class="tab-content">
                  <div class="table-responsive" tabindex="-1" style="border:1px solid #dee2e6;height:calc(100vh - 650px);overflow: auto;">
                        <table id="tdataalkes" class="table table-hover table-sm table-transaksi">
                          <thead class="bg-light">
                            <tr>
                              <th class="text-sm text-label text-left px-1 border-0" style="width: 100px">Nama Alkes #</th>
                              <th class="text-sm text-label text-right px-1 border-0" style="width: 40px">Qty</th>
                              <th class="text-sm text-label text-right px-1 border-0" style="width: 60px">Qty Standar</th>
                              <th class="text-sm text-label text-left px-1 border-0" style="width: 80px">Satuan</th>  
                              <th class="text-sm text-label text-center border-0" style="width: 40px">Hapus</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                          <tfoot>
                          </tfoot>
                        </table>
                  </div>
                  <div class="row mt-2 px-2">
                     
                  </div>
                </div>
              </div>
            </div>  
            <div style='clear:both'></div> 
          
          
          
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
<script src="<? echo app_url('assets/dist/js/modul/transaksi/penjualan/form-editdepo.js'); ?>"></script> 