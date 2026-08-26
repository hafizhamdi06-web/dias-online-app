  <form class='form-horizontal' method="post">
  <input type="hidden" id="id" name="id">
    <div class="modal-body">
    <div class="row px-1">
      <input type="hidden" id="jmlbayar">
      <label class="col-sm-4 col-form-label text-sm font-weight-normal">Pajak</label>
      <div class="col-sm-5">
        <select id="pajak" name="pajak" class="form-control form-control-sm select2" data-trigger="manual" data-placement="auto">
        </select>
      </div>
    </div>
    <div class="row px-1">
      <label class="col-sm-4 col-form-label text-sm font-weight-normal">No. Bukti Potong</label>
      <div class="col-sm-8">
          <input type="text" id="nomorbupot" name="nomorbupot" class="form-control form-control-sm" autocomplete="off" data-trigger="manual" data-placement="auto">
      </div>
    </div>    
    <div class="row px-1">
      <label class="col-sm-4 col-form-label text-sm font-weight-normal">Jumlah</label>
      <div class="col-sm-5">
            <input type="text" id="jmlpph" name="jmlpph" class="form-control form-control-sm numeric" autocomplete="off" value="0" data-trigger="manual" data-placement="auto">
      </div>
      <div id="spinner" class="col-sm-1 pt-1 d-none"><i class="fas fa-spinner fa-spin"></i></div>
    </div> 
    <div class="row px-1 d-none">
      <label class="col-sm-4 col-form-label text-sm font-weight-normal">Disetor sendiri</label>
      <div class="col-sm-5">
        <div class="form-check pt-1">
          <input type="checkbox" class="form-check-input" id="chksetorsendiri" tabindex="-1">
          <label class="form-check-label text-sm" for="chksetorsendiri" role="button">Ya</label>
        </div>         
      </div>
    </div>
    <div class="row pl-3 pt-2 d-none">
      <i class="text-sm text-red">Catatan : Jika disetor sendiri maka nilai pembayaran tidak dipotong pph dan akan menjadi hutang pajak</i>
    </div>        
    </div>
    <div class="modal-footer">
      <div class="row px-0 mx-0">
          <a class="btn btn-outline-primary btn-sm mx-1" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal</a>
          <button type="button" id="bhapus" name="bhapus" class="btn btn-info btn-sm mx-2">Hapus</button>          
          <button type="button" id="bsimpan" name="bsimpan" class="btn btn-primary btn-sm px-2">Simpan</button>
      </div>       
    </div>
</form>

<script src="<? echo app_url('assets/dist/js/modul/transaksi/penjualan/potongan-pph.js'); ?>"></script> 