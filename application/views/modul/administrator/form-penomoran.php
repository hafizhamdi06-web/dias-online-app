<form class='form-horizontal' method="post">
    <input type="hidden" id="id" name="id">
    <div class="modal-body">
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Kode</label>                    
      <div class="col-sm-4">
        <input type="text" class="form-control form-control-sm" placeholder="" id="kode" name="kode" autocomplete="off" data-trigger="manual" data-placement="auto">
      </div>
    </div>                              
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Keterangan</label>                    
      <div class="col-sm-9">
        <input type="text" class="form-control form-control-sm" placeholder="" id="keterangan" name="keterangan" autocomplete="off" data-trigger="manual" data-placement="auto">
      </div>
    </div>                
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nama Tabel</label>                    
      <div class="col-sm-9">
        <select class="form-control form-control-sm select2" id="tabel" name="tabel" data-trigger="manual" data-placement="auto">
        </select>
      </div>
    </div>                
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Tanggal</label>                    
      <div class="col-sm-9">
        <select class="form-control form-control-sm select2" id="tanggal" name="tanggal" data-trigger="manual" data-placement="auto">
        </select>
      </div>
    </div>
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Sumber</label>                    
      <div class="col-sm-9">
        <select class="form-control form-control-sm select2" id="sumber" name="sumber" data-trigger="manual" data-placement="auto">
        </select>
      </div>
    </div>
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">No Transaksi</label>                    
      <div class="col-sm-9">
        <select class="form-control form-control-sm select2" id="notrans" name="notrans" data-trigger="manual" data-placement="auto">
        </select>
      </div>
    </div>    
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Kontak</label>                    
      <div class="col-sm-9">
        <select class="form-control form-control-sm select2" id="kontak" name="kontak" data-trigger="manual" data-placement="auto">
        </select>
      </div>
    </div>
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Uraian</label>                    
      <div class="col-sm-9">
        <select class="form-control form-control-sm select2" id="uraian" name="uraian" data-trigger="manual" data-placement="auto">
        </select>
      </div>
    </div>    
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Total Transaksi</label>                    
      <div class="col-sm-9">
        <select class="form-control form-control-sm select2" id="total" name="total" data-trigger="manual" data-placement="auto">
        </select>
      </div>
    </div>    
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">ID</label>                    
      <div class="col-sm-9">
        <select class="form-control form-control-sm select2" id="idtrans" name="idtrans" data-trigger="manual" data-placement="auto">
        </select>
      </div>
    </div>
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">NFA</label>                    
      <div class="col-sm-9">
        <select class="form-control form-control-sm select2" id="nfa" name="nfa" data-trigger="manual" data-placement="auto">
          <option value="0">Tidak</option>
          <option value="1">Ya</option>          
        </select>
      </div>
    </div>    
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Menu</label>                    
      <div class="col-sm-9">
        <select class="form-control form-control-sm select2" id="menu" name="menu" data-trigger="manual" data-placement="auto">
        </select>
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

<script src="<? echo app_url('assets/dist/js/modul/administrator/form-penomoran.js'); ?>"></script> 