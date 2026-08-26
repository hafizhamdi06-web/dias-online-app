<form class='form-horizontal' method="post">
<input type="hidden" id="id" name="id">
<div class="modal-body">
<div class="row mx-2">
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Kode</label>                    
  <div class="col-sm-4">
    <input type="text" class="form-control form-control-sm" placeholder="" id="kode" name="kode" autocomplete="off" data-trigger="manual" data-placement="auto">
  </div>
  <div class="col-sm-3">
    <div class="form-check py-1">
      <input type="checkbox" class="form-check-input" id="chkdefault">
      <label class="form-check-label text-sm" for="chkdefault">Default</label>
    </div>                
  </div>
</div>                              
<div class="row mx-2">
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nama</label>                    
  <div class="col-sm-9">
    <input type="text" class="form-control form-control-sm" placeholder="" id="nama" name="nama" autocomplete="off" data-trigger="manual" data-placement="auto">
  </div>
</div>
<div class="row mx-2">
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Divisi</label>                    
  <div class="col-sm-9">
    <select id="divisi" name="divisi" class="form-control select2 w-100" required>
    </select>                         
  </div>
</div>
<div class="row mx-2">
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Kontak</label>                    
  <div class="col-sm-9">
    <input type="text" class="form-control form-control-sm" placeholder="" id="kontak" name="kontak" autocomplete="off">
  </div>
</div>                                                                                      
<div class="row mx-2">
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Alamat</label>                    
  <div class="col-sm-9">
    <textarea class="form-control form-control-sm" rows="2" id="alamat" name="alamat" autocomplete="off" style="height:4em;"></textarea>
  </div>
</div>
<div class="row mt-1 mx-2">
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Kota</label>                    
  <div class="col-sm-9">
    <input type="text" class="form-control form-control-sm" placeholder="" id="kota" name="kota" autocomplete="off">
  </div>
</div>                                                                                    
<div class="row mx-2">
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Provinsi</label>                    
  <div class="col-sm-9">
    <input type="text" class="form-control form-control-sm" placeholder="" id="provinsi" name="provinsi" autocomplete="off">
  </div>
</div>                                                                                    
<div class="row mx-2">
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Negara</label>                    
  <div class="col-sm-9">
    <input type="text" class="form-control form-control-sm" placeholder="" id="negara" name="negara" autocomplete="off">
  </div>
</div>                                                                                      
<div class="row mx-2">
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Telp</label>                    
  <div class="col-sm-3">
    <input type="text" class="form-control form-control-sm" placeholder="" id="telp" name="telp" autocomplete="off">
  </div>
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Faks</label>                    
  <div class="col-sm-3">
    <input type="text" class="form-control form-control-sm" placeholder="" id="faks" name="faks" autocomplete="off">
  </div>              
</div>           
<div class="row mx-2 d-none">
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Keterangan</label>                    
  <div class="col-sm-9">
    <input type="text" class="form-control form-control-sm" placeholder="" id="ket" name="ket" autocomplete="off">
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
<script src="<? echo app_url('assets/dist/js/modul/master/form-gudang.js'); ?>"></script> 