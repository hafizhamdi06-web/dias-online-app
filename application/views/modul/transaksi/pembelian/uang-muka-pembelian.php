    <form class='form-horizontal' method="post">
    <input type="hidden" id="id" name="id">
    <div class="modal-body">
    <div class="row">
      <div class="col-sm-6">
      </div>            
      <div class="col-sm-3">
        <div class="form-group">
          <label class="text-sm text-brown px-2 bg-light font-weight-normal">Tanggal *</label>
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
          <label class="text-sm text-brown px-2 bg-light font-weight-normal">Nomor Transaksi</label>
          <input type="text" id="nomor" name="nomor" class="form-control form-control-sm" placeholder="[Auto]" autocomplete="off">
        </div>
      </div>    
    </div>                            
    <div class="row mt-2">
      <label class="col-sm-4 col-form-label text-sm text-brown px-3 font-weight-normal">Uraian</label>
      <div class="col-sm-7">
        <textarea class="form-control form-control-sm" rows="1" id="uraian" name="uraian" autocomplete="off" data-trigger="manual" data-placement="auto"></textarea>
      </div>
    </div>
    <div class="row">
      <label class="col-sm-4 col-form-label text-sm text-brown px-3 font-weight-normal">COA Uang Muka (D) *</label>
      <div class="col-sm-7">
          <select id="coadp" name="coadp" class="form-control form-control-sm select2" data-trigger="manual" data-placement="top" style="width: 100%">
          </select>     
      </div>
    </div>
    <div class="row d-none">
      <label class="col-sm-4 col-form-label text-sm text-brown px-3 font-weight-normal">Pajak</label>
      <div class="col-sm-4">
        <select id="pajak" name="pajak" class="form-control form-control-sm select2" style="width:100%">
          <option value="0" selected>Tanpa Pajak</option>
          <option value="1">Belum Termasuk Pajak</option>
          <option value="2">Termasuk Pajak</option>
        </select>     
      </div>
    </div>
    <div id="colpjk2" class="row d-none">
      <label class="col-sm-4 col-form-label text-sm text-brown px-3 font-weight-normal">No Faktur Pajak</label>
      <div class="col-sm-7">
          <input type="text" id="nofakturpjk" name="nofakturpjk" class="form-control form-control-sm" autocomplete="off">
      </div>
    </div>
    <div id="colpjk1" class="row d-none">
      <label class="col-sm-4 col-form-label text-sm text-brown px-3 font-weight-normal">Tgl Faktur Pajak</label>
      <div class="col-sm-4">
        <div class="input-group date">
          <input type="text" id="tglpajak" name="tglpajak" class="form-control form-control-sm datepicker" autocomplete="off">
          <div id="dTglPajak" class="input-group-append" role="button">
              <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
          </div>
        </div>                        
      </div>
    </div>                        
    <div class="row d-none">
      <label class="col-sm-4 col-form-label text-sm text-brown px-3 font-weight-normal">COA Kas/Bank (K)</label>
      <div class="col-sm-8">
          <select id="coadpkas" name="coadpkas" class="form-control form-control-sm select2" data-trigger="manual" data-placement="top" style="width: 100%">
          </select>     
      </div>
    </div>    
    <div class="row">
      <label class="col-sm-4 col-form-label text-sm text-brown px-3 font-weight-normal">Jumlah *</label>
      <div class="col-sm-4">
        <div class="input-group">
        <div class="input-group-append">
          <div class="input-group-text bg-white border-right-0 py-0 px-2">Rp</div>
        </div>
        <input type="tel" class="form-control form-control-sm numeric" id="jumlah" name="jumlah" autocomplete="off" data-trigger="manual" data-placement="auto" value="0">
        </div>        
      </div>
    </div>                
    <div id="colpjk3" class="row d-none">
      <label class="col-sm-4 col-form-label text-sm text-brown px-3 font-weight-normal">Jumlah Pajak</label>
      <div class="col-sm-4">
        <div class="input-group">
        <div class="input-group-append">
          <div class="input-group-text border-right-0 py-0 px-2">Rp</div>
        </div>
        <input type="tel" class="form-control form-control-sm numeric" id="jumlahpjk" name="jumlahpjk" autocomplete="off" data-trigger="manual" data-placement="auto" value="0" disabled>
        </div>        
      </div>
    </div>                
    <div id="colpjk4" class="row d-none">
      <label class="col-sm-4 col-form-label text-sm text-brown px-3 font-weight-normal">Total</label>
      <div class="col-sm-4">
        <div class="input-group">
        <div class="input-group-append">
          <div class="input-group-text border-right-0 py-0 px-2">Rp</div>
        </div>
        <input type="tel" class="form-control form-control-sm numeric" id="total" name="total" autocomplete="off" data-trigger="manual" data-placement="auto" value="0" disabled>
        </div>        
      </div>
    </div>                        
    <div style='clear:both'></div>
    </div>
    <div class="modal-footer">
        <div class="form-group">
            <div class="col-sm-offset-3">
                <a class="text-sm mx-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal</a>
                <button type="button" id='bsimpan' name='bsimpan' class="btn btn-primary btn-sm">Simpan</button>
            </div>
        </div>                
    </div>
  </form>

  <script src="<? echo app_url('assets/dist/js/modul/transaksi/pembelian/uang-muka-pembelian.js'); ?>"></script> 