<form class='form-horizontal' method="post">
<input type="hidden" id="id" name="id">
<div class="modal-body">
<div class="row mx-2">
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nama</label>                    
  <div class="col-sm-9">
    <input type="text" class="form-control form-control-sm" placeholder="" id="nama" name="nama" autocomplete="off" data-trigger="manual" data-placement="auto">
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
<script src="<? echo app_url('assets/dist/js/modul/master/form-kategori-kontak.js'); ?>"></script> 