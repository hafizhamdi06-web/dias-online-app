<form class='form-horizontal' method="post">
  <input type="hidden" id="id" name="id">
  <div class="modal-body">
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Kode</label>                    
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm" placeholder="" id="kode" name="kode" autocomplete="off" data-trigger="manual" data-placement="auto">
    </div>
  </div>                              
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nama</label>                    
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" placeholder="" id="nama" name="nama" autocomplete="off" data-trigger="manual" data-placement="auto">
    </div>
  </div>
  <div class="row mx-2 d-none">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nilai</label>                    
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm numeric" placeholder="" id="nilai" name="nilai" value="1" autocomplete="off">
    </div>
  </div>
  <div class="row mx-2 d-none">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Satuan Dasar</label>                    
    <div class="col-sm-4">
      <div class="form-check py-1">
        <input type="checkbox" class="form-check-input" id="chkdasar">
        <label class="form-check-label text-sm" for="chkdasar"></label>
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
<script src="<? echo app_url('assets/dist/js/modul/master/form-satuan.js'); ?>"></script> 