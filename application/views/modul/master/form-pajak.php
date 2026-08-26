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
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Tipe Pajak</label>                    
  <div class="col-sm-9">
    <select class="form-control form-control-sm select2" id="tipe" name="tipe" data-trigger="manual" data-placement="auto">
      <option value="1">Pajak Pertambahan Nilai</option>
      <option value="2">Pajak Penghasilan</option>      
    </select>
  </div>
</div>                                              
<div class="row mx-2">
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nama Pajak</label>                    
  <div class="col-sm-9">
    <input type="text" class="form-control form-control-sm" placeholder="" id="nama" name="nama" autocomplete="off" data-trigger="manual" data-placement="auto">
  </div>
</div>
<div class="row mx-2">
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nilai</label>                    
  <div class="col-sm-4">
    <div class="input-group">    
      <input type="text" class="form-control form-control-sm numeric" id="nilai" name="nilai" value="0" autocomplete="off" data-trigger="manual" data-placement="auto">
    <div class="input-group-append">
        <div class="input-group-text bg-white border-0">%</div>
    </div>
    </div>    
  </div>
</div>
<div class="row mx-2">
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">COA Pembelian</label>                    
  <div class="col-sm-9">
    <select class="form-control form-control-sm select2" id="coain" name="coain" data-trigger="manual" data-placement="auto">
    </select>
  </div>
</div>                                            
<div class="row mx-2">
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">COA Penjualan</label>                    
  <div class="col-sm-9">
    <select class="form-control form-control-sm select2" id="coaout" name="coaout" data-trigger="manual" data-placement="auto">
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
<script src="<? echo app_url('assets/dist/js/modul/master/form-pajak.js'); ?>"></script> 