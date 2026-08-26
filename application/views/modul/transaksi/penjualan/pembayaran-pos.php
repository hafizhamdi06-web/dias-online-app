  <form class='form-horizontal' method="post">
  <input type="hidden" id="id" name="id">
    <div class="modal-body">
    <div class="row">
      <label class="col-sm-5 col-form-label text-sm font-weight-normal">Tunai / Cash</label>
      <div class="col-sm-7">
          <div class="input-group">        
            <input type="text" id="cash" name="cash" class="form-control form-control-sm numeric" autocomplete="off" value="0" data-trigger="manual" data-placement="auto">
            <div id="calcash" class="input-group-append" role="button">
                <div class="input-group-text px-1 py-0 bg-white"><i class="fas fa-calculator text-secondary"></i></div>
            </div>
          </div>
      </div>
    </div>
    <hr/>
    <div class="row">
      <label class="col-sm-5 col-form-label text-sm font-weight-normal">Debit Card</label>
      <div class="col-sm-7">
          <div class="input-group">
            <input type="text" id="debit" name="debit" class="form-control form-control-sm numeric" autocomplete="off" value="0" data-trigger="manual" data-placement="auto">
            <div id="caldebit" class="input-group-append" role="button">
                <div class="input-group-text px-1 py-0 bg-white"><i class="fas fa-calculator text-secondary"></i></div>
            </div>            
          </div>
      </div>
    </div>                          
    <div class="row">
      <label class="col-sm-5 col-form-label text-sm font-weight-normal">Bank</label>
      <div class="col-sm-7">
          <select id="bankdebit" class="form-control form-control-sm select2"></select>
      </div>
    </div>                              
    <div class="row">
      <label class="col-sm-5 col-form-label text-sm font-weight-normal">Nomor</label>
      <div class="col-sm-7">
          <input type="text" id="debitno" name="debitno" class="form-control form-control-sm" autocomplete="off">
      </div>
    </div>
    <hr/>
    <div class="row">
      <label class="col-sm-5 col-form-label text-sm font-weight-normal">Credit Card</label>
      <div class="col-sm-7">        
        <div class="input-group">
            <input type="text" id="credit" name="credit" class="form-control form-control-sm numeric" autocomplete="off" value="0" data-trigger="manual" data-placement="auto">
            <div id="calcredit" class="input-group-append" role="button">
                <div class="input-group-text px-1 py-0 bg-white"><i class="fas fa-calculator text-secondary"></i></div>
            </div>  
        </div>
      </div>
    </div>                          
    <div class="row">
      <label class="col-sm-5 col-form-label text-sm font-weight-normal">Bank</label>
      <div class="col-sm-7">
          <select id="bankcredit" class="form-control form-control-sm select2"></select>
      </div>
    </div>                              
    <div class="row">
      <label class="col-sm-5 col-form-label text-sm font-weight-normal">Nomor</label>
      <div class="col-sm-7">
          <input type="text" id="creditno" name="creditno" class="form-control form-control-sm" autocomplete="off">
      </div>
    </div>
    <hr/>
    <div class="row">
      <label class="col-sm-5 col-form-label text-sm font-weight-normal">Total Transaksi</label>
      <div class="col-sm-7">
          <input type="text" id="ttrans" name="ttrans" class="total form-control form-control-sm border-0 numeric" value="0" disabled>
      </div>
    </div>                              
    <div class="row">
      <label class="col-sm-5 col-form-label text-sm font-weight-normal">Total Bayar</label>
      <div class="col-sm-7">
          <input type="text" id="tbayar" name="tbayar" class="total form-control form-control-sm border-0 numeric" value="0" data-trigger="manual" data-placement="auto" disabled>
      </div>
    </div>                                  
    <div class="row">
      <label class="col-sm-5 col-form-label text-sm font-weight-normal">Sisa</label>
      <div class="col-sm-7">
          <input type="text" id="tsisa" name="tsisa" class="total form-control form-control-sm border-0 numeric" value="0" data-trigger="manual" data-placement="auto" disabled>
      </div>
    </div>                  
    </div>
    <div class="modal-footer">
      <div class="row px-0 mx-0">
            <a class="btn btn-outline-primary btn-sm mx-2" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal [Esc]</a>
            <button type="button" id="bsimpan" name="bsimpan" class="btn btn-primary btn-sm px-2">Simpan [F12]</button>
      </div>       
    </div>
</form>

<script src="<? echo app_url('assets/dist/js/modul/transaksi/penjualan/pembayaran-pos.js'); ?>"></script> 